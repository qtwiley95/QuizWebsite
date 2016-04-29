<?php
  session_start();

  function add_question($subject, $question, $answer, $fake1, $fake2, $fake3)
  {

    function force_input($data) {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
    }
    /*
    trim the user input to prevent insertion
    -user has already been trimmed on initialization
    */
    $subject = force_input($subject);
    $question = force_input($question);
    $answer = force_input($answer);
    $fake1 = force_input($fake1);
    $fake2 = force_input($fake2);
    $fake3 = force_input($fake3);
    $author = $_SESSION['login'];

    echo "(" . $author . ", " . $subject . ", " . $question . ", " . $answer . ", " . $fake1 . ", " . $fake2 . ", " . $fake3 . ")<br>";



    $conn = new mysqli ("mysql.eecs.ku.edu", "qwiley", "asdf", "qwiley");

    if($conn == false)
    {
      echo "connection failed";
      $conn -> close();
      exit();
    }

    echo "<br>connection successful!<br>";


    $insert = "INSERT INTO 368_questions (author, subject, question, answer, fake1, fake2, fake3) VALUES ('$author','$subject','$question','$answer','$fake1','$fake2','$fake3')";
    $conn->query($insert);
    echo "question has been added to table 368_questions";


    $conn -> close();
  }

  $quest = $_POST["question"];
  $ans = $_POST["answer"];
  $fak1 = $_POST["fake1"];
  $fak2 = $_POST["fake2"];
  $fak3 = $_POST["fake3"];
  $subj = $_POST["subject"];

  echo "(" . $subj . ", " . $quest . ", " . $ans . ", " . $fak1 . ", " . $fak2 . ", " . $fak3 . ")<br>";

  add_question($subj, $quest, $ans, $fak1, $fak2, $fak3);


  function return_question($condition)
  {
    
  }


 ?>
