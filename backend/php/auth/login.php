<?php
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Access-Control-Allow-Credentials: true');


if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}


require_once '../connect.php';

$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

$username = $data['username'] ?? '';
$plain_pw = $data['password'] ?? '';
if($username == '' || $plain_pw ==''){
    http_response_code(400);
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
    $user_id = $user['id'];
    $token = bin2hex(random_bytes(32));
    $expires_at = date('Y-m-d H:i:s', time() + 360000);

    $sql_token = "INSERT INTO auth_tokens (user_id, token, expires_at) VALUES (?, ?, ?) 
                  ON DUPLICATE KEY UPDATE token = VALUES(token), expires_at = VALUES(expires_at)";
    $stmt_token = $db->prepare($sql_token);
    // Assumes 'token' is unique or you are replacing an old one for this user
    $stmt_token->bind_param("iss", $user_id, $token, $expires_at);

    if ($stmt_token->execute()) {
        $stmt_token->close();
        
        // Success: Return the token to the client
        header("Content-Type: application/json; charset=UTF-8");
        echo json_encode(["message" => "Login successful", "token" => $token]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Could not issue authentication token."]);
    }
} else{
    http_response_code(401); // Unauthorized
    header("Content-Type: application/json; charset=UTF-8");
    echo json_encode(["error" => "Invalid username or password"]);
}