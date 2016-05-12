<?php

session_start();

error_reporting(E_ALL);
ini_set("display_errors", 1);

if (isset($_POST["id"]) && !empty($_POST["id"]) && isset($_POST['answer']) && !empty($_POST["answer"]) && isset($_SESSION["login"])) {
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
  $select->close();

  $isCorrect = ($answer == $_POST["answer"]);

  if ($isCorrect) {
    $update = $conn->prepare("UPDATE 368_users SET correct_answers = correct_answers + 1 WHERE user = ?");
    $update->bind_param("s", $_SESSION["login"]);
    $update->execute();
    $update->close();
  } else {
    $update = $conn->prepare("UPDATE 368_users SET incorrect_answers = incorrect_answers + 1 WHERE user = ?");
    $update->bind_param("s", $_SESSION["login"]);
    $update->execute();
    $update->close();
  }

  $select = $conn->prepare("SELECT incorrect_answers, correct_answers FROM 368_users WHERE user = ?");
  $select->bind_param("s", $_SESSION["login"]);
  $select->execute();
  $select->bind_result($incorrect_answers, $correct_answers);
  $select->fetch();
  $select->close();

  $out = array("success" => $isCorrect, "correct" => $correct_answers, "incorrect" => $incorrect_answers);

  header('Content-Type: application/json');
  echo json_encode($out);

  $conn->close();
}else{
  echo "ERROR: Bad request.";
}
?>
