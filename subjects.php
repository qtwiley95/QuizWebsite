<?php

$conn = new mysqli("mysql.eecs.ku.edu", "qwiley", "asdf", "qwiley");

if($conn == false) {
  echo "connection failed";
  $conn->close();
  exit();
}

$result = $conn->query("SELECT * FROM 368_subjects");

$out = array("subjects" => []);
while ($row = $result->fetch_assoc()) {
  $out["subjects"][] = array(
    "name" => $row["subject"],
    "description" => $row["description"]
  );
}
echo json_encode($out);

$result->close();

$conn->close();

?>
