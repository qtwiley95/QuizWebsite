<html>
<header>
  <title>Page</title>
  <link rel="stylesheet" type="text/css" href="indexcss.css">
</header>
<h1>
  <?php
  session_start();
  if(!isset($_SESSION['login'])){
      echo "\nMust Sign in First.<br>";
      header("Location:practice_login.html");
    }else{
      echo "Welcome, ";
      echo $_SESSION['login'];
    }
  ?>
      <a href="logout.php"><button type="button">LOG OUT</button></a>
</h1>


<body>


  <div id="main">
    in the main. this is where things will be and stuff will happen.<br>
    <a href="add_question.php">add question to your pool</a>
  </div>


  <div id="logout">
  	logout here:<br>
    <a href="logout.php"><button type="button">LOG OUT</button></a>
  </div>

</body>
</html>
