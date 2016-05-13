<?php
  session_start();

  if (!isset ($_SESSION['login'])) {
    header('Location: index.html');
      exit ();
  }


  error_reporting(E_ALL);
  ini_set("display_errors", 1);
  echo "<center>";
  function add_question()
  {
//retrieve data from $_POST array
    $question = $_POST["question"];
    $answer = $_POST["answer"];
    $fake1 = $_POST["fake1"];
    $fake2 = $_POST["fake2"];
    $fake3 = $_POST["fake3"];
    $subject = $_POST["subject"];
//this function will prevent injection
    function force_input($data) {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
    }
    /*
    trim the user input to prevent injection
    -user has already been trimmed on initialization
    */
    $subject = force_input($subject);
    $question = force_input($question);
    $answer = force_input($answer);
    $fake1 = force_input($fake1);
    $fake2 = force_input($fake2);
    $fake3 = force_input($fake3);
    $author = $_SESSION['login'];
    //print table of the user input, shows user that it will be
    echo "<table>";
    echo "<tr><td>Author: </td><td>" . $author . "</td></tr><tr><td>Subject: </td><td>" . $subject . "</td></tr><tr><td>Question: </td><td>";
    echo $question . "</td></tr><tr><td>Answer: </td><td> " . $answer . "</td></tr><tr><td>Fake1: </td><td>" . $fake1 . "</td></tr><tr><td>Fake2: </td><td> ";
    echo $fake2 . "</td></tr><tr><td>Fake3: </td><td> " . $fake3 . "</td></tr>";

    $conn = new mysqli ("mysql.eecs.ku.edu", "qwiley", "asdf", "qwiley");

    if($conn == false)
    {
      //if unable to connect
      echo "connection failed";
      $conn -> close();
      exit();
    }

//put values into the database, table 368_questions
    $insert = "INSERT INTO 368_questions (author, subject, question, answer, fake1, fake2, fake3) VALUES ('$author','$subject','$question','$answer','$fake1','$fake2','$fake3')";
    if($conn->query($insert))
      echo "<tr><h3>Question has been added.</h3></tr>";
    else {
        echo "<tr><h3>Question has NOT been added. You may have entered some special characters.</h3></tr>";
    }
    echo "</table>";

    //check if subject exists in database [subjects]
    $subject = $_POST["subject"];
    $check = "SELECT subject FROM 368_subjects";
    $boolAnswer = true;
    if($result = $conn->query($check)){
      while($row = $result -> fetch_assoc ()){
        $subject_name = $row ["subject"];
        if($subject_name == $subject){
          $boolAnswer = false;
        }
      }
    }
//if the subject does not exist then create new subject in the 368_subjects table
    if($boolAnswer){
      $insert2 = "INSERT INTO 368_subjects (subject) VALUES ('$subject')";
      $conn->query($insert2);
    }
    //end checking


    $conn -> close();
  }


//if the post array has the entries from the form then call add question function.
  if (isset($_POST['question']) && isset($_POST['answer']) && isset($_POST['fake1']) && isset($_POST['fake2']) && isset($_POST['fake3']) && isset($_POST['subject'])) {
      add_question();
  }
  echo "</center>";
?>


 <DOCTYPE html>
 <html>
   <head>
     <meta charset='utf-8'>
     <meta http-equiv='X-UA-Compatible' content='IE=edge'>
     <meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no'>
     <title>Quiz Website - Add Question</title>
     <link rel='stylesheet' href='css/bootstrap.min.css'>
   </head>

 <body>

   <!-- toolbar with home link and correct score -->
   <nav class="navbar navbar-default">
     <div class="container-fluid">
       <div class="navbar-header">
         <a class="navbar-brand" href="." id="home">QuizzWebsite</a>
       </div>

       <!-- change questions -->
       <ul class="nav navbar-nav navbar-left">
         <li><a href="add_question.php">Add Question</a></li>
         <li><a href="delete_subject.php">Delete Subject</a></li>
       </ul>

     </div>
   </nav>

  <div class="container">
   <h1 id="main">Add Questions</h1>

   <div id="myForm">
     <form onsubmit="return checkQuestion(this)" action="add_question.php" method="post">
     <!--  create form for users to fill our in order to create question, and store it in $_POST, and reload this page -->
     <table>
       <tr class ="question"><td>Question:</td><td><input type="text" size="80" name="question" required></td></tr>
       <tr class ="correct"><td>Correct Answer: </td><td> <input type="text" size="80" name="answer" required></td></tr>
       <tr class ="false"><td>False answer 1: </td><td> <input type="text" size="80" name="fake1" required></td></tr>
       <tr class ="false"><td>False answer 2: </td><td> <input type="text" size="80" name="fake2" required></td></tr>
       <tr class ="false"><td>False answer 3: </td><td> <input type="text" size="80" name="fake3" required></td></tr>
       <tr class ="subject"><td>Enter subject: </td><td> <input type="text" size="80" name="subject" required></td></tr>
     </table>

     <?php
         //display subjects in dropdown menu
         echo "<br><font color=\"#6E6E6E\">Current subjects in database:";
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
       echo " </font>";
       // close mysql
       $connection -> close ();

           ?>
      <br>
       <button type="submit">SUBMIT</button>
     </form>
   </div>

   <p id="second">
     The question will be added to your list of made questions.<br>
     <a href="index.html">Home Page</a>
   </p>
  </div>
 </body>
 </html>
