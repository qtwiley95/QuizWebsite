<?php

session_start();

$conn = new mysqli("mysql.eecs.ku.edu", "qwiley", "asdf", "qwiley");

if($conn == false) {
  echo "connection failed";
  $conn->close();
  exit();
}

$result = $conn->query("SELECT correct_answers, incorrect_answers FROM 368_users WHERE user = '".$_SESSION['user']."'");

$row = $result->fetch_assoc();
$performanceCorrect = $row["correct_answers"];
$performanceIncorrect = $row["incorrect_answers"];

$result->close();

$conn->close();

?>
