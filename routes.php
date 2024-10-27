<?php
// routes.php

function route($uri) {
    if ($uri == '' || $uri == '/') {
        include 'page/todo/index.php'; // Your home page
    } elseif ($uri == '/about') {
        require 'views/about.php'; // Your about page
    } else {
        echo "Tolol";
    }
}