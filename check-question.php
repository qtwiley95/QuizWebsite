<?php

session_start();
error_reporting(E_ALL);
ini_set("display_errors", 1);

// Check if is a valid request
if (isset($_POST["id"]) && !empty($_POST["id"]) && isset($_POST['answer']) && isset($_SESSION["login"])) {
  /**
   * The connection with database
   *
   * @var mysqli_connection
   */
  $conn = new mysqli("mysql.eecs.ku.edu", "qwiley", "asdf", "qwiley");

  // Check connection with database
  if($conn == false) {
    echo "connection failed";
    $conn->close();
    exit();
  }

  /**
   * Use the helper functions to prepare the output of the request
   *
   * @param msqli_connection $db
   * @param int              $questionId
   * @param string           $givenAnswer
   * @return Array
   */
  function handleValidation($db,$questionId,$givenAnswer) {
  	 $isCorrect = validateAnswer($db,$questionId,$givenAnswer);
 	   $isCorrect ? addCorrect($db,$_SESSION["login"]) : addIncorrect($db,$_SESSION["login"]);
 	   $performance = getPerformance($db,$_SESSION["login"]);
 	   $out = array("success" => $isCorrect, "correct" => intval($performance["correct_answers"]), "incorrect" => intval($performance["incorrect_answers"]));
 	   return $out;
  }

  /**
   * Check the given answer with the correct answer
   * stored in the database
   *
   * @param msqli_connection $db
   * @param int              $id
   * @param string           $given
   * @return bool
   */
  function validateAnswer($db,$id,$given) {
  	 $sql = "SELECT answer FROM 368_questions WHERE id = $id";
  	 $answer =  $db->query($sql)->fetch_assoc();
  	 return $answer["answer"] == $given;
  }

  /**
   * Increment the number of correct answers
   * of a specific user
   *
   * @param msqli_connection $db
   * @param string           $user
   */
  function addCorrect($db,$user){
  	 $db->query("UPDATE 368_users SET correct_answers = correct_answers + 1 WHERE user = '$user'");
  }

  /**
   * Increment the number of incorrect answers
   * of a specific user
   *
   * @param msqli_connection $db
   * @param string           $user
   */
  function addIncorrect($db,$user){
  	 $db->query("UPDATE 368_users SET incorrect_answers = incorrect_answers + 1 WHERE user = '$user'");
  }

  /**
   * Use the database to get the current performance
   * of a specific user
   *
   * @param msqli_connection $db
   * @param string           $user
   * @return Array
   */
  function getPerformance($db,$user) {
	   $sql = "SELECT incorrect_answers, correct_answers FROM 368_users WHERE user = '$user'";
  	 $performance = $db->query($sql)->fetch_assoc();
  	 return $performance;
  }

  // Set the response header and show the response in json format
  header('Content-Type: application/json');
  echo json_encode(handleValidation($conn,$_POST["id"],$_POST["answer"]));

  $conn->close();
}else{
  echo "ERROR: Bad request.";
}
?>
