<?php
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

  $query = "DELETE FROM 368_subjects WHERE subject = '$subject'";
  $connection->query($query);

  $query = "DELETE FROM 368_questions WHERE subject = '$subject'";
  $connection->query($query);

  $connection -> close();
}

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
  <form action="?" method="post">
    subject to delete:<input type="text" name="subDelete" required>
     <button type="submit">SUBMIT</button>
  </form>
  <a href="/index.html">HomePage</a>
</body>
</html>
