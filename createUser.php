<?php

function forgeUser()
{
$options = [
  'cost' => 11,
];

  $person = $_POST["name"];
  $passwordFromPost = $_POST["pass"];


  function force_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

  $person = force_input($person);
  $passwordFromPost = force_input($passwordFromPost);

  #come back to hashed pass to see if it indeed is stored in mysql since i dont see a pass value
  $hashed = password_hash($passwordFromPost, PASSWORD_BCRYPT, $options);

  #echo $hashed;

  $conn = new mysqli ("mysql.eecs.ku.edu", "qwiley", "asdf", "qwiley");

  if($conn == false)
  {
    echo "connection failed";
    $conn -> close();
    exit();
  }

  $select = "SELECT * FROM 368_users WHERE user = '$person'";
  $result = $conn -> query($select);

  if($result -> num_rows == 0)
  {
    $insert = "INSERT INTO 368_users (user, pass) VALUES ('$person','$hashed')";
    $conn->query($insert);
    echo "user has been added to table 368_user";
  }
  else {
    echo "user already exists, please choose another username<br>";
  }

  $conn -> close();
}


if (isset($_POST['name']) && isset($_POST['pass'])) {
    forgeUser(($_POST['name']), ($_POST['pass']));
}

 ?>

 <!DOCTYPE html>
 <html>
 <head>
   <link rel="stylesheet" type="text/css" href="practiceCss.css">
   <title>practice</title>
 </head>

 <body>

 <p id="main">
 	Please Create A UserName And Password:
 </p>

 <div id="myForm">
   <form onsubmit="return checkForm(this)" action="?" method="post">
     Username: <input type="text" name="name" required><br>
     Password: <input type="password" name="pass" required><br>
     <button type="submit"><b>SUBMIT</b></button>
   </form>
 </div>
 <br>
 <p id="second">
 	Or, Click Here To Log In:<br>
     <a href="login.php">LOG IN</a>
 </p>

 </body>
 </html>
