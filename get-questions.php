<?php

if (isset($_GET["subject"]) && !empty($_GET["subject"])){

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

$select = $conn->prepare("SELECT author, question, answer, fake1, fake2, fake3, id FROM 368_questions WHERE subject = ? ORDER BY RAND() LIMIT $max_results");
$select->bind_param("s", $_GET["subject"]);
$select->execute();
$select->bind_result($author, $questionText, $answer, $fake1, $fake2, $fake3, $id);

$out = array("questions" => []);
while ($select->fetch()) {
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

}else{
    echo "ERROR: Bad request.";
}
?>
