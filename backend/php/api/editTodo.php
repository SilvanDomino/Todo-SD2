<?php
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS");
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

$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

$todo_id = $data['id'] ?? null;
$todo_status = $data['status'] ?? null; 
if (!$todo_id || !$todo_status) {
    http_response_code(400); // Bad Request
    exit("Error: Both 'id' and 'status' fields are required.");
}
$todo_id = (int)$todo_id;

$sql = "UPDATE todos SET status=? WHERE id = ?";

$stmt = $db->prepare($sql);
if(!$stmt){
    exit("Error preparing statement: " . $db->error);
}
$stmt->bind_param("si", $todo_status, $todo_id);

$result = $stmt->execute();
$stmt->close();

if ($result) { echo "Success!"; } else { echo "Failure!"; }