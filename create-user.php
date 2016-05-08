<?php

$conn = new mysqli("mysql.eecs.ku.edu", "qwiley", "asdf", "qwiley");

if($conn == false) {
  echo "connection failed";
  $conn->close();
  exit();
}

if (isset($_POST["name"]) && isset($_POST["pass"])) {
  $person = $_POST["name"];
  $passwordFromPost = $_POST["pass"];

  $hashed = password_hash($passwordFromPost, PASSWORD_BCRYPT, [
    'cost' => 11,
  ]);

  $insert = $conn->prepare("INSERT INTO 368_users (user, pass) VALUES (?, ?)");
  $insert->bind_param("ss", $person, $hashed);
  $result = $insert->execute();
  $insert->close();

  echo "okay";
} else {
  echo "fail";
}

$conn->close();
?>
