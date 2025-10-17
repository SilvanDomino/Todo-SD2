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

$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

$todo_text = $data['text'] ?? null;
$todo_status = 'todo'; 
if (!$todo_text) {
    exit("Error: 'text' field is missing.");
}

$sql = "INSERT INTO todos(text, status) VALUES(?, ?)";

$stmt = $db->prepare($sql);
if (!$stmt) {
    // Handle error (e.g., echo $db->error)
    exit("Error preparing statement: " . $db->error);
}

$stmt->bind_param("ss", $todo_text, $todo_status);


$result = $stmt->execute();
$stmt->close();

if ($result) { echo "Success!"; } else { echo "Failure!"; }
