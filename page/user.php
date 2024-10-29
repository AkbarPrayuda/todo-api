<?php 

require './lib/db.php';

$db = new DB();

error_reporting(E_ALL & ~E_NOTICE); // Hanya laporkan kesalahan, abaikan peringatan
ini_set('display_errors', 0); // Jangan tampilkan kesalahan di layar


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Register user
    
     // Ambil data dari formulir
     $username = $_POST['username'];
     $email = $_POST['email'];
     $password = $_POST['password'];
 
     // Validasi data
     if (empty($username) || empty($email) || empty($password)) {
         echo Response::error("All field must fill!");
         exit;
     }
 
     // Hash password
     $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
 
     // Simpan ke database
     try {
         $db = new DB();
         $sql = "INSERT INTO user (username, email, password) VALUES (:username, :email, :password)";
         $stmt = $db->query($sql, [
             ':username' => $username,
             ':email' => $email,
             ':password' => $hashedPassword
         ]);
 
         echo json_encode([
             "message" => "Registrasi berhasil! Silakan login.",
             "status" => 200
         ]);
     } catch (PDOException $e) {
         echo json_encode([
             "message" => "Terjadi kesalahan: " . $e->getMessage(),
             "status" => 500
         ]);
     }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Mengambil semua data user
    $id = $_GET['id'] ?? null;

    // Validate the 'id' parameter
    if (empty($id)) {
        echo Response::error("ID parameter is required", 400);
        exit;
    }
    
    try {
        // Contoh penggunaan query
        $sql = "SELECT * FROM user WHERE id = :id"; // Ganti dengan nama tabel yang sesuai
        $stmt = $db->query($sql, [':id' => $id]);

        // Mengambil hasil
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$result){
            throw new Exception("User not found!", 1);
            
        }
        
        echo Response::success("Success get Users data",  $result);

    } catch (\Throwable $th) {
        echo Response::error($th->getMessage());
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT'  || $_SERVER['REQUEST_METHOD'] === 'PATCH') {

    parse_str(file_get_contents("php://input"), $_PUT); // Parse input
    $id = $_PUT['id'] ?? null; // ID pengguna yang ingin diperbarui
    $username = $_PUT['username'] ?? null; // Username baru
    $email = $_PUT['email'] ?? null; // Email baru
    $password = $_PUT['password'] ?? null; // Password baru (opsional)
    
    // Validasi parameter
    if (empty($id) || empty($username) || empty($email)) {
        echo Response::error("ID, username, dan email must fillable.", 400);
        exit;
    }

    // Hash password jika disediakan
    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    }

    try {
        $sql = "UPDATE user SET username = :username, email = :email" . 
               (!empty($password) ? ", password = :password" : "") . 
               " WHERE id = :id"; // Siapkan pernyataan UPDATE

        $params = [
            ':username' => $username,
            ':email' => $email,
            ':id' => $id
        ];

        // Tambahkan password ke parameter jika disediakan
        if (!empty($password)) {
            $params[':password'] = $hashedPassword;
        }

        $stmt = $db->query($sql, $params);

        // Cek apakah ada baris yang diperbarui
        if ($stmt->rowCount() > 0) {
            echo  Response::success("Success update Users data");
        } else {
            echo json_encode([
                "message" => "Pengguna tidak ditemukan atau tidak ada perubahan.",
                "status" => 404 // Not Found 
            ]);
            echo  Response::error("User not found or nothing change");
        }
    } catch (PDOException $e) {
        echo Response::error($e->getMessage());
    }
    
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
     // Retrieve the 'id' parameter from the request body
     parse_str(file_get_contents("php://input"), $_DELETE); // Parse the input
     $id = $_DELETE['id'] ?? null; // Use null coalescing to handle missing parameter

     // Validate the 'id' parameter
    if (empty($id)) {
        echo Response::error("ID parameter is required", 400);
        exit;
    }

    try {
        $sql = "DELETE FROM user WHERE id = :id"; // Prepare the DELETE statement
        $stmt = $db->query($sql, [':id' => $id]);

        // Check if any row was deleted
        if ($stmt->rowCount() > 0) {
            echo Response::success("Success delete user!", []);

        } else {
            echo Response::error("User  not found or already deleted.");
        }
    } catch (PDOException $e) {
        echo Response::error($e->getMessage());
    }
} else {
    echo "Request method tidak dikenali.";
}