<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

$conn = new mysqli("mysql.eecs.ku.edu", "qwiley", "asdf", "qwiley");

if($conn == false) {
  echo "connection failed";
  $conn->close();
  exit();
}

$result = $conn->query("SELECT * FROM 368_subjects");

$out = array("subjects" => []);
while ($row = $result->fetch_assoc()) {
  $out["subjects"][] = $row;
}

header('Content-Type: application/json');
echo json_encode($out);

$result->close();

$conn->close();

?>
