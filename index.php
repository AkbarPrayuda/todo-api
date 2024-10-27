<?php
// index.php


// Include the routes file
require 'routes.php';

// Include Database Configurationb
require './lib/db.php';

// Database credentials
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'todo';

// Create a new Database object
$db = new DB($host, $username, $password, $dbname);

// Get the connection
$conn = $db->getConnection();

// Get the requested URL path
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remove the base path if necessary (e.g., "/todo-app")
$basePath = '/todo-app'; // Adjust this to your actual base path
$requestUri = str_replace($basePath, '', $requestUri);

// Route the request
route($requestUri);
?>