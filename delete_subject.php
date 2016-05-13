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
</head>
<body>
  <!-- create very basic form that will reload same page and will fill $_POST upon submitting -->
  <form action="?" method="post">
    subject to delete:<input type="text" name="subDelete" required>
     <button type="submit">SUBMIT</button>
  </form>
  <a href="index.html">HomePage</a>
</body>
</html>
