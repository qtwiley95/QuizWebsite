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
  
  function handleValidation($db,$questionId,$givenAnswer) {
  	 $isCorrect = validateAnswer($db,$questionId,$givenAnswer);
 	 $isCorrect ? addCorrect($db,$_SESSION["login"]) : addIncorrect($db,$_SESSION["login"]);
 	 $performance = getPerformance($db,$_SESSION["login"]);
 	 $out = array("success" => $isCorrect, "correct" => $performance["correct_answers"], "incorrect" => $performance["incorrect_answers"]);
 	 return $out;
  }
  
  function validateAnswer($db,$id,$given) {
  	 $sql = "SELECT answer FROM 368_questions WHERE id = $id";
  	 $answer =  $db->query($sql)->fetch_assoc();
  	 return $answer["answer"] == $given;
  }
  
  function addCorrect($db,$user){
  	 $db->query("UPDATE 368_users SET correct_answers = correct_answers + 1 WHERE user = '$user'");
  }
  
  function addIncorrect($db,$user){
  	 $db->query("UPDATE 368_users SET incorrect_answers = correct_answers + 1 WHERE user = '$user'");
  }
  
  function getPerformance($db,$user) {
	 $sql = "SELECT incorrect_answers, correct_answers FROM 368_users WHERE user = '$user'";
  	 $performance = $db->query($sql)->fetch_assoc();
  	 return $performance;
  }
  
  header('Content-Type: application/json');
  echo json_encode(handleValidation($conn,$_POST["id"],$_POST["answer"]));

  $conn->close();
}else{
  echo "ERROR: Bad request.";
}
?>
