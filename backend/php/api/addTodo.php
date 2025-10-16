<?php
session_set_cookie_params([
    'samesite' => 'None',
    'secure' => true, // ⚠️ SameSite=None requires the Secure attribute
]);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

session_start(); 

// --- AUTHENTICATION CHECK ---
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    http_response_code(401); // Unauthorized
    exit(json_encode(["error" => "You must be logged in to do that."]));
}

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
