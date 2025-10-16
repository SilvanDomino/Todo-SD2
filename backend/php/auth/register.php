<?php
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Access-Control-Allow-Credentials: true');

require_once '../connect.php';

$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

$username = $data['username'] ?? '';
$plain_pw = $data['password'] ?? '';
if($username == '' || $plain_pw ==''){
    exit("Error, username or pw are missing");
}

$hashed_pw = password_hash($plain_pw, PASSWORD_DEFAULT);

$sql = "INSERT INTO users(username, password) VALUES(?, ?)";
$stmt = $db->prepare($sql);
$stmt->bind_param("ss", $username, $hashed_pw);

if($stmt->execute()){
    http_response_code(200);
    echo "Registration succesful";
} else {
    http_response_code(400);
    echo "registration failed";
}
$stmt->close();
?>

