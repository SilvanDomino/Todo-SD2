<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS, PATCH");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once '../connect.php';

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