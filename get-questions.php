<?php

include "qwiley_get_questions.php";

error_reporting(E_ALL);
ini_set("display_errors", 1);

if (isset($_GET["subject"]) && !empty($_GET["subject"])) {
  $max_results = 1;
  if (isset($_GET["max_results"])) {
    $max_results = intval($_GET["max_results"]);
  }

  header('Content-Type: application/json');

  echo json_encode(getQuestion($_GET["subject"], $max_results));
} else {
  echo "ERROR: Bad request.";
}
?>
