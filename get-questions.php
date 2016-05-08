<?php

$conn = new mysqli("mysql.eecs.ku.edu", "qwiley", "asdf", "qwiley");

if($conn == false) {
  echo "connection failed";
  $conn->close();
  exit();
}

$select = $conn->prepare("SELECT author, question, answer, fake1, fake2, fake3, id FROM 368_questions WHERE subject = ? ORDER BY RAND()");
$select->bind_param("s", $_GET["subject"]);
$select->execute();
$select->bind_result($author, $questionText, $answer, $fake1, $fake2, $fake3, $id);

$max_results = 1;
if (isset($_GET["max_results"])) {
  $max_results = intval($_GET["max_results"]);
}

$out = array("questions" => []);
while ($select->fetch()) {
  if (count($out["questions"]) >= $max_results) {
    break;
  }

  $question = array();
  $question["author"] = $author;
  $question["question"] = $questionText;
  $question["answers"] = array($answer, $fake1, $fake2, $fake3);
  shuffle($question["answers"]);
  $question["id"] = $id;

  $out["questions"][] = $question;
}
echo json_encode($out);

$select->close();
$conn->close();

?>
