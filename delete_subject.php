<?php
//ensures user is logged in, if not then it will redirect to login page
  session_start ();
  if (!isset ($_SESSION['login'])) {
    header('Location: index.html');
      exit ();
  }

function deleteSubject($subject){
  //will not delete the base 3 subjects.
  if($subject === "math" || $subject === "science" || $subject === "biology" || $subject === "GRE vocabulary"){
    echo "unable to delete this subject";
    exit;
  }

  // open mysql
  $connection = new mysqli ("mysql.eecs.ku.edu", "qwiley", "asdf", "qwiley");
  // check connection
  if ($connection === false) {
    exit ();
  }
//deletes the subject from the 368_subjects table
  $query = "DELETE FROM 368_subjects WHERE subject = '$subject'";
  $connection->query($query);
//deletes all questions from 368_questions table with that subject
  $query = "DELETE FROM 368_questions WHERE subject = '$subject'";
  $connection->query($query);

  $connection -> close();
}

//checks if the $_POST array is filled from the form
if(isset($_POST['subDelete'])){
  $sub = $_POST['subDelete'];
  echo "deleting subject, $sub";
  deleteSubject($sub);
}

?>

<html>
<head>
  <meta charset='utf-8'>
  <meta http-equiv='X-UA-Compatible' content='IE=edge'>
  <meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no'>
  <title>Quiz Website - Delete Subject</title>
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
        <li class="active"><a href="delete_subject.php">Delete Subject</a></li>
      </ul>
    </div>
  </nav>

  <div class="container">
    <!-- create very basic form that will reload same page and will fill $_POST upon submitting -->
    <form action="?" method="post">
      <h3>subject to delete:</h3><input type="text" name="subDelete" required>
       <button type="submit">SUBMIT</button>
    </form>
    <a href="index.html">HomePage</a>
  </div>
</body>
</html>
