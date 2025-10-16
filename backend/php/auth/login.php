<?php

session_set_cookie_params([
    'samesite' => 'None',
    'secure' => true, // ⚠️ SameSite=None requires the Secure attribute
]);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

session_start();
require_once '../connect.php';

$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

$username = $data['username'] ?? '';
$plain_pw = $data['password'] ?? '';
if($username == '' || $plain_pw ==''){
    exit("Error, username or pw are missing");
}

$sql = "SELECT id, password FROM users WHERE username = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param("s", $username);

$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if($user && password_verify($plain_pw, $user['password'])){
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['logged_in'] = true;

    echo "Login in success";
} else{
    http_response_code(401);
    echo "Invalid username or password";
}