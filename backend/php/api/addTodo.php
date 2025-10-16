<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
require_once '../connect.php';

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
