<?php
//takes in a subject and how many questions to retrieve as parameter then
//  queries database and returns an array with x random questions from the database
//  it will retrieve as many as it can without over drawing. so it will always return
// x <= number of questions to retrieve
function getQuestion($subject,$amountOfQuestions)
{

  // open mysql
  $connection = new mysqli ("mysql.eecs.ku.edu", "qwiley", "asdf", "qwiley");
  // check connection
  if ($connection === false) {
     exit ();
  }

  $query = "SELECT * FROM 368_questions WHERE subject = '$subject'";
  $result = $connection ->query($query);
  $num = $result -> num_rows;
  //ensures not to overdraw questions from database
  if($amountOfQuestions > $num)
  {
    $amountOfQuestions = $num;
  }


  // select all from 368_questions table and randomize them
  $query = "SELECT * FROM 368_questions WHERE subject = '$subject' ORDER BY RAND() LIMIT $amountOfQuestions";
  $result = $connection->query($query);
  $num = $result -> num_rows;
  if(!$result){
    echo "Question Not Found<br>";
    exit;
  }

//create array of arrays containing questions
  $out = array("questions" => []);

  for($i = 0; $i < $num; $i++)
  {
    $returnQuestions = array();
    $row = $result -> fetch_assoc();
    $returnQuestions["question"] = $row["question"];
    $returnQuestions["answers"] = array($row["answer"],$row["fake1"],$row["fake2"],$row["fake3"]);
    shuffle($returnQuestions["answers"]);
    $returnQuestions["author"] = $row["author"];
    $returnQuestions["id"] = $row["id"];
    array_push($out, $returnQuestions);
  }
  $connection -> close();
//return array containing arrays that has question, author, id, and an array of answers
  return $out;
}

print_r(getQuestion("biology",2));

 ?>
