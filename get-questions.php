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
    $questions =  $db->query($sql)->fetch_assoc();

    if($questions != NULL){
      $questionsArray = array("questions" => []);
      foreach ($questions as $question) {
        $questionsArray["questions"][] = [
          "question" => $question["question"],
          "answers" => shuffle(["answers","fake1","fake2","fake3"]),
          "author" => $quesiton["author"],
          "id" => $quesiton["id"]
        ]
      }
      return $questionsArray;
    } else {
      return "No quesitons found.";
    }
  }

  header('Content-Type: application/json');
  echo json_encode(getQuestions($_GET["subject"], $max_results));

} else {
  echo "ERROR: Bad request.";
}
?>
