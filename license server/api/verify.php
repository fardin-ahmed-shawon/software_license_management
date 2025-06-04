<?php
header('Content-Type: application/json');

// 🔐 Config
$dbHost = 'localhost';
$dbName = 'your_db_name';
$dbUser = 'your_db_user';
$dbPass = 'your_db_pass';

// 🔗 Connect to DB
$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed.']);
    exit;
}

// 🔍 Validate Input
$license = $_GET['license'] ?? '';
$domain  = $_GET['domain'] ?? '';

if (empty($license) || empty($domain)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'License or domain missing.']);
    exit;
}

// 🔎 Query License
$stmt = $conn->prepare("SELECT * FROM licenses WHERE license_key = ?");
$stmt->bind_param("s", $license);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    if ($row['status'] !== 'active') {
        echo json_encode(['status' => 'blocked']);
    } elseif ($row['domain'] !== $domain) {
        echo json_encode(['status' => 'invalid_domain']);
    } else {
        echo json_encode(['status' => 'valid', 'product' => $row['product_name']]);
    }
} else {
    echo json_encode(['status' => 'invalid']);
}

$conn->close();
