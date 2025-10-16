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
    exit("You must be logged in to do that.");
}

require_once '../connect.php';

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
  echo "0 results";
}