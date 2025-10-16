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

$headers = getallheaders();
$auth_header = $headers['Authorization'] ?? $headers['authorization'] ?? '';

$token = null;
// Expecting "Bearer <token>"
if (preg_match('/Bearer\s(\S+)/', $auth_header, $matches)) {
    $token = $matches[1];
}
if (!$token) {
    http_response_code(401); // Unauthorized
    exit("Token is missing or invalid in Authorization header.");
}
// 1. Look up the token in the database and check expiration
$sql_token_check = "SELECT user_id FROM auth_tokens WHERE token = ? AND expires_at > NOW()";
$stmt_token = $db->prepare($sql_token_check);
$stmt_token->bind_param("s", $token);
$stmt_token->execute();
$result_token = $stmt_token->get_result();
$auth_data = $result_token->fetch_assoc();
$stmt_token->close();
if (!$auth_data) {
    http_response_code(401); // Unauthorized
    exit("Invalid or expired token. You must be logged in to do that.");
}

// The user is authenticated! Get the user_id for authorization logic.
$authenticated_user_id = $auth_data['user_id'];


$sql = "SELECT * FROM todos";
$result = $db->query($sql);

if ($result->num_rows > 0) {
    $todoList = array();
  while($row = $result->fetch_assoc()) {
    $todo = array("id"=>$row["id"], "text"=>$row["text"], "status"=>$row["status"], "last_updated"=>$row["last_updated"]);
    $todoList[] = $todo;
  }
  header("Content-Type: application/json; charset=UTF-8");
  echo json_encode($todoList);
} else {
  echo json_encode([]);

}
if (isset($stmt_todos)) {
    $stmt_todos->close();
}
?>