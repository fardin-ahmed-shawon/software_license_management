<?php
// licence creation and verification example
function generate_license_key() {
    return strtoupper(bin2hex(random_bytes(4))) . '-' . strtoupper(bin2hex(random_bytes(4)));
}

$license = generate_license_key();
echo "Generated License: " . $license;


$license_key = 'ABC123-XYZ789';
$current_domain = $_SERVER['HTTP_HOST'];

$verify_url = "https://your-license-server.com/api/verify.php?license={$license_key}&domain={$current_domain}";

$response = file_get_contents($verify_url);
$data = json_decode($response, true);

if ($data['status'] === 'valid') {
    echo "✅ License Verified!";
    // continue running app
} elseif ($data['status'] === 'invalid_domain') {
    die("❌ This license is not valid for this domain.");
} elseif ($data['status'] === 'blocked') {
    die("❌ This license has been blocked.");
} else {
    die("❌ Invalid License.");
}
