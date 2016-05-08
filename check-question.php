<?php

$conn = new mysqli("mysql.eecs.ku.edu", "qwiley", "asdf", "qwiley");

if($conn == false) {
  echo "connection failed";
  $conn->close();
  exit();
}

$select = $conn->prepare("SELECT answer FROM 368_questions WHERE id = ?");
$select->bind_param("s", $_POST["id"]);
$select->execute();
$select->bind_result($answer);
$select->fetch();

if ($answer == $_POST["answer"]) {
  $out = array("success" => True);
  echo json_encode($out);
} else {
  $out = array("success" => False);
  echo json_encode($out);
}

$select->close();
$conn->close();

?>
