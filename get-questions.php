<?php

session_start();

error_reporting(E_ALL);
ini_set("display_errors", 1);

if (isset($_GET["subject"]) && !empty($_GET["subject"])) {
  $conn = new mysqli("mysql.eecs.ku.edu", "qwiley", "asdf", "qwiley");

  if($conn == false) {
    echo "connection failed";
    $conn->close();
    exit();
  }

  $max_results = 1;
  if (isset($_GET["max_results"])) {
    $max_results = intval($_GET["max_results"]);
  }

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

  header('Content-Type: application/json');
  echo json_encode(getQuestions($conn,$_GET["subject"],$max_results));

} else {
  echo "ERROR: Bad request.";
}
?>
