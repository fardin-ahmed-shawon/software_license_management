<?php

// Config
$dbHost = 'localhost';
$dbName = 'license_checker';
$dbUser = 'root';
$dbPass = '';

// Connect to DB
$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed.']);
    exit;
}

?>