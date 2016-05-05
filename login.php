<?php
session_start();

function createUser()
{
  $person = $_POST["name"];
  $password = $_POST["pass"];
  $accepted = false;

  //force input will clean the data to stop injection
  function force_input($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }


  $person = force_input($person);
  $password = force_input($password);


  $conn = new mysqli ("mysql.eecs.ku.edu", "qwiley", "asdf", "qwiley");

  if($conn == false)
  {
    echo "connection failed";
    $conn -> close();
    exit();
  }

  #echo "<br>connection successfull<br>";

  $select = "SELECT pass FROM 368_users WHERE user = '$person' LIMIT 1";
  $result = $conn -> query($select);

  if($result -> num_rows == 0)
  {
    echo "user does not exist";
  }
  else {
    $row = $result->fetch_assoc();
    $passFromData = $row["pass"];
    if(password_verify($password,$passFromData) == true)
    {
  	$accepted = true;
    }
    else {
      echo "$password does not match $passFromData";
      echo "Invalid username/password.";
    }
  }


  $conn -> close();


  if($accepted)
  {
    $_SESSION['login'] = $person;
    header("Location:index.php");
  }

}

if (isset($_POST['name']) && isset($_POST['pass'])) {
    createUser(($_POST['name']), ($_POST['pass']));
}

?>


<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" type="text/css" href="practiceCss.css">
  <title>practice</title>
</head>

<body>
  <p id="main">LOG IN HERE:</p>

<div id="myForm">
  <form onsubmit="return checkForm(this)" action="?" method="post">
    Username: <input type="text" name="name" required><br>
    Password: <input type="text" name="pass" required>
    <button type="submit">SUBMIT</button>
  </form>
</div>

<p id="second">
Or Create User Here:<br>
<a href="practice.html">CREATE USER</a>
</p>


</body>
</html>
