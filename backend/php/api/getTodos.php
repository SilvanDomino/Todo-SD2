<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

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