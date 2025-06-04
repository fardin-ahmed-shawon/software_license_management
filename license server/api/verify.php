<?php
header('Content-Type: application/json');

// database connection
require '../../dbConnection.php';

// configuration



// Validate Input
$license = $_POST['license'] ?? '';
$domain  = $_POST['domain'] ?? '';

if (empty($license) || empty($domain)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'License or domain missing.']);
    exit;
}

// Query License
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
