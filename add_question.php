<?php
  session_start();

  session_start ();
  if (!isset ($_SESSION['login'])) {
    header('Location: index.html');
      exit ();
  }


  error_reporting(E_ALL);
  ini_set("display_errors", 1);

  function add_question()
  {

    $question = $_POST["question"];
    $answer = $_POST["answer"];
    $fake1 = $_POST["fake1"];
    $fake2 = $_POST["fake2"];
    $fake3 = $_POST["fake3"];
    $subject = $_POST["subject"];

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


    $insert = "INSERT INTO 368_questions (author, subject, question, answer, fake1, fake2, fake3) VALUES ('$author','$subject','$question','$answer','$fake1','$fake2','$fake3')";
    if($conn->query($insert))
      echo "question has been added to table 368_questions";
    else {
        echo "question has NOT been added to table 368_questions";
    }


    //check if subject exists in database [subjects]
    $subject = $_POST["subject"];
    echo "<br>". $subject;
    $check = "SELECT user_id FROM subjects";
    $boolAnswer = true;
    if($result = $conn->query($check)){
      while($row = $result -> fetch_assoc ()){
        $subject_name = $row ["user_id"];
        echo $subject_name;
        if($subject_name == $subject){
          $boolAnswer = false;
        }
      }
    }

    if($boolAnswer){
      $insert = "INSERT INTO subjects (user_id) VALUES ('$subject')";
      $insert2 = "INSERT INTO 368_subjects (subject) VALUES ('$subject')";
      if($conn->query($insert))
        echo "Subject inserted!";
      if($conn->query($insert2))
        echo "Subject inserted! (2)";
    }
    //end checking


    $conn -> close();
  }


  if (isset($_POST['question']) && isset($_POST['answer']) && isset($_POST['fake1']) && isset($_POST['fake2']) && isset($_POST['fake3']) && isset($_POST['subject'])) {
      add_question();
  }
?>


 <DOCTYPE html>
 <html>
   <header>
     <script type="text/javascript" src="practice.js"></script>
     <link rel="stylesheet" type="text/css" href="practiceCss.css">
   </header>

 <body>

   <p id="main">Add Questions</p>

 <div id="myForm">
   <form onsubmit="return checkQuestion(this)" action="add_question.php" method="post">
   <!--  Subject: <br><input type="text" name="subject" required><br> -->
     question: <br><input type="text" name="question" required><br>
     correct answer: <br><input type="text" name="answer" required><br>
     false answer 1: <br><input type="text" name="fake1" required><br>
     false answer 2: <br><input type="text" name="fake2" required><br>
     false answer 3: <br><input type="text" name="fake3" required><br>
     Enter subject: <br><input type="text" name="subject" required><br>


   <?php
       //display subjects in dropdown menu
       echo "Current subjects in database: ";
       // open mysql
       $connection = new mysqli ("mysql.eecs.ku.edu", "qwiley", "asdf", "qwiley");                // check connection
       if ($connection === false) {
         echo "connect failed";
         exit ();
       }

     // get table of users
     $select = "SELECT subject FROM 368_subjects";
     $result = $connection -> query ($select);
     $num = $result -> num_rows;

     // print table of users
     for ($i = 0; $i < $num; $i++) {
       $row = $result -> fetch_assoc ();
       $user = $row ["subject"];
       echo ($i+1). ". ". $user. " ";
     }

     // close mysql
     $connection -> close ();

         ?>

     <button type="submit">SUBMIT</button>
   </form>
 </div>

 <p id="second">
   The question will be added to your list of made questions.<br>
   <a href="index.html">Home Page</a>
 </p>

 </body>
 </html>
