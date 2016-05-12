<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);
session_start();

$conn = new mysqli("mysql.eecs.ku.edu", "qwiley", "asdf", "qwiley");

if($conn == false) {
  echo "connection failed";
  $conn->close();
  exit();
}

$result = $conn->query("SELECT * FROM 368_subjects");

$select = $conn->prepare("SELECT incorrect_answers, correct_answers FROM 368_users WHERE user = ?");
$select->bind_param("s", $_SESSION["login"]);
$select->execute();
$select->bind_result($incorrect_answers, $correct_answers);
$select->fetch();
$select->close();

$out = array("correct" => $correct_answers, "incorrect" => $incorrect_answers);

header('Content-Type: application/json');
echo json_encode($out);

$result->close();

$conn->close();

?>
