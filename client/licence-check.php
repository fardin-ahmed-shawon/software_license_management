<?php


// licence creation and verification example
function generate_license_key() {
    return strtoupper(bin2hex(random_bytes(4))) . '-' . strtoupper(bin2hex(random_bytes(4)));
}
// $license = generate_license_key();
// echo "Generated License: " . $license;


// Post data
$license_key = '123XYZ';
$current_domain = $_SERVER['HTTP_HOST'];
$current_domain = 'php.easytechx.com';


$verify_url = "http://localhost/test/software_license_management/license%20server/api/verify.php";

// Use cURL to send POST request
$post_fields = [
    'license' => $license_key,
    'domain'  => $current_domain
];

$ch = curl_init($verify_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);

$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);

if ($data['status'] === 'valid') {

    echo "License Verified!";

    // continue running app
} elseif ($data['status'] === 'invalid_domain') {

    die("Your license is not valid for this domain.");

} elseif ($data['status'] === 'blocked') {

    die("Your license has been blocked.");

} else {

    die("Invalid License.");
    
}

?>