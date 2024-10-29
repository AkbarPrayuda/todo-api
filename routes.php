<?php
// routes.php

function route($uri) {
    if ($uri == '/user' ) {
        include 'page/user.php'; // Your home page
    } elseif ($uri == '/about') {
        require 'views/about.php'; // Your about page
    } else {
        echo Response::error("Not Found");
    }

}