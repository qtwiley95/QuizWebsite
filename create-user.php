<?php

$conn = new mysqli("mysql.eecs.ku.edu", "qwiley", "asdf", "qwiley");

if($conn == false) {
  echo "connection failed";
  $conn->close();
  exit();
}

if (isset($_POST["name"]) && isset($_POST["pass"])) {
  $hashed = password_hash($_POST["pass"], PASSWORD_BCRYPT, [
    'cost' => 11,
  ]);

  $select = $conn->prepare("SELECT * FROM 368_users WHERE user = ?");
  $select->bind_param("s", $_POST["name"]);
  $select->execute();
  $select->store_result();

  if($select->num_rows == 0) {
    $insert = $conn->prepare("INSERT INTO 368_users (user, pass) VALUES (?, ?)");
    $insert->bind_param("ss", $_POST["name"], $hashed);
    $result = $insert->execute();
    $insert->close();

    echo "okay";
  } else {
    echo "user already exists";
  }

  $select->free_result();
  $select->close();
} else {
  echo "fail";
}

$conn->close();
?>
