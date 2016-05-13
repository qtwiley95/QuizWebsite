<?php

session_start();

error_reporting(E_ALL);
ini_set("display_errors", 1);

// Check if is a valid request
if (isset($_GET["subject"]) && !empty($_GET["subject"])) {
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

  // Set the maximum number of questions to be returned
  $max_results = 1;
  if (isset($_GET["max_results"])) {
    $max_results = intval($_GET["max_results"]);
  }

  /**
   * Works with the database to fetch the questions
   * according with the subject given.
   *
   * @param msqli_connection $db
   * @param string           $subject
   * @param int              $maxResults
   * @return Array
   */
  function getQuestions($db,$subject,$maxResults)
  {
    $sql = "SELECT * FROM 368_questions WHERE subject = '$subject' ORDER BY RAND() LIMIT $maxResults";
    $questions =  $db->query($sql);

    if($questions != NULL){
      $questionsArray = array("questions" => []);
      while ($question = $questions->fetch_assoc()) {
        $answers = [$question["answer"],$question["fake1"],$question["fake2"],$question["fake3"]];
        shuffle($answers);
        $questionsArray["questions"][] = [
          "question" => $question["question"],
          "answers" => $answers,
          "author" => $question["author"],
          "id" => $question["id"]
        ];
      }
      return $questionsArray;
    } else {
      return "No quesitons found.";
    }
  }

  // Set the response header and show the response in json format
  header('Content-Type: application/json');
  echo json_encode(getQuestions($conn,$_GET["subject"],$max_results));

} else {
  echo "ERROR: Bad request.";
}
?>
