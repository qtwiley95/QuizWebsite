<?php

session_start();

$conn = new mysqli("mysql.eecs.ku.edu", "qwiley", "asdf", "qwiley");

if($conn == false) {
  echo "connection failed";
  $conn->close();
  exit();
}

function send($b) {
  if ($b) {
    echo "true";
  } else {
    echo "false";
  }
}

if (isset($_POST["name"]) && isset($_POST["pass"])) {
  $select = $conn->prepare("SELECT pass FROM 368_users WHERE user = ? LIMIT 1");
  $select->bind_param("s", $_POST["name"]);
  $select->execute();
  $select->bind_result($pass);
  $select->fetch();
  $select->close();

  if(!isset($pass)) {
    echo "user does not exist";
  } else {
    if(password_verify($_POST["pass"], $pass) == true) {
  	  echo "okay";
      $_SESSION['login'] = $_POST["name"];
    } else {
      echo "invalid password";
    }
  }
} else if (isset($_POST["name"])) {
  $select = $conn->prepare("SELECT * FROM 368_users WHERE user = ? LIMIT 1");
  $select->bind_param("s", $_POST["name"]);
  $result = $select->execute();
  $select->close();

  send($result->num_rows == 1);
} else if ($_SERVER["REQUEST_METHOD"] == "GET") {
  send(isset($_SESSION["login"]));
} else {
  echo "fail";
}

$conn->close();

?>
