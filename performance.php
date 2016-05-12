<?php include 'getPerformance.php'; ?>

<!doctype html>
<html>
  <head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no'>
    <title>Quiz Website</title>
    <link rel='stylesheet' href='css/bootstrap.min.css'>
    <style>
      .quizz-question-header {
        text-align: center;
        font-size: 44px;
      }
      .quizz-question-answer {
        margin-left: 25px;
        margin-bottom: 40px;
        padding: 25px;
        cursor: pointer;
        background-color: #f2f2f2;
      }
      .quizz-question-answer-selected {
        background-color: #008D24;
      }
      .quizz-question-answer-wrong {
        background-color: #B93400;
      }
      .quizz-question-answer-id {
        margin-right: 25px;
      }
      .quizz-question-answer-answer {
        font-size: 1.5em;
        font-weight: bold;
      }
      .answer-icon {
        float: right;
        font-size: 2em;
      }
      .button-icon {
      }
      #next {
        margin-left: 100px;
      }
    </style>
  </head>
  <body>
    <nav class="navbar navbar-default">
      <div class="container-fluid">
        <div class="navbar-header">
          <a class="navbar-brand" href="./" id="home">QuizzWebsite</a>
          <a class="navbar-brand" href="add_question.php">Admin Editor</a>
        </div>
      </div>
    </nav>
    <div class='container'>
     <div id="piechart" style="width: 900px; height: 500px;"></div>
    </div>
    <script src='js/jquery-2.2.3.min.js'></script>
    <script src='js/bootstrap.min.js'></script>
	 <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Performance', '#'],
          ['Correct',     <?php echo $performanceCorrect; ?>],
          ['Incorrect',   <?php echo $performanceIncorrect; ?>],
        ]);

        var options = {
          title: 'Quiz Performance'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
      }
    </script>

  </body>
</html>
