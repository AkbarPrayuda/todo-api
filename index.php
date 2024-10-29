<?php
// index.php


// Include the routes file
require 'routes.php';

require './lib/response.php';

// Get the requested URL path
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remove the base path if necessary (e.g., "/todo-app")
$basePath = '/todo-app'; // Adjust this to your actual base path
if ($requestUri !== null) {
    $requestUri = str_replace($basePath, '', $requestUri);
} else {
    $requestUri = ''; // Atau Anda bisa mengatur nilai default lainnya
}



// Route the request
route($requestUri);
?>