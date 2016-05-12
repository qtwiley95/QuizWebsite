
  $("#home").click(function(event) {
    event.preventDefault();
  });

  $("#create-user").off("click");
  $('#create-user').click(function(event) {
    $('#create-user').addClass('active');
    $.post('create-user.php', {
      name: $('#email-input').val(),
      pass: $('#password-input').val()
    }).done(function(data) {
      $('#create-user').removeClass('active');
      if (data == 'okay') {
        $('#login').addClass('active');
        login();
      } else if (data == 'user already exists'){
        $('#already-exists').removeClass('hide');
      }
    });
  });

  $('#email-input').focus(function(event) {
    $('#create-user').addClass('hide');
    $("#user-not-found").addClass('hide');
    $("#bad-password").addClass('hide');
    $('#already-exists').addClass('hide');
    $('#password').removeClass('hide');
  });

  $('#password-input').focus(function(event) {
    $("#user-not-found").addClass('hide');
    $('#bad-password').addClass('hide');
    $('#login').removeClass('hide');
  });

  var letters = 'abcdefghijklmnopqrstuvwxyz';

  function practice(subject) {
    function nextQuestion() {
      $("#check").removeClass('hide');
      $("#next").addClass('hide');
      $("#quizwebsite-subject-modal").addClass('hide');
      $.getJSON("get-questions.php?subject=" + subject.subject)
      .done(function(data) {
        $("#quizwebsite-question-modal").removeClass('hide');
        loadQuestion(data.questions[0]);
      });
    }

    $("#next").off("click");
    $("#next").click(function() {
      nextQuestion();
    });

    function loadQuestion(question) {
      $("#check").off("click");
      $("#check").click(function() {
        var answer = $(".quizz-question-answer-selected .quizz-question-answer-answer").first().text();
        $.post("check-question.php", {id: question.id, answer: answer}, function(data) {
          $("#score").text(Math.round(data.correct / (data.correct + data.incorrect) * 100) + "% correct");
          if (data.success) {
            $("#next").removeClass("hide");
            $("#check").addClass("hide");
            $("<i class='glyphicon glyphicon-ok-circle answer-icon'></i>")
              .appendTo(".quizz-question-answer-selected");
          } else {
            $("<i class='glyphicon glyphicon-remove-circle answer-icon'></i>")
              .appendTo(".quizz-question-answer-selected");
            $(".quizz-question-answer-selected")
              .addClass("quizz-question-answer-wrong")
              .removeClass("quizz-question-answer-selected");
          }
        }, "json");
      })

      $("#quizz-questions").empty();
      question.answers.forEach(function(answer, i) {
        var letter = letters[i];
        $("<div class='quizz-question-answer'>" +
          "<span class='quizz-question-answer-id'>" + letter +
          ".</span><span class='quizz-question-answer-answer'>" + answer +
          "</span></div>")
        .click(function() {
          $(".quizz-question-answer").removeClass("quizz-question-answer-selected");
          $(this).addClass("quizz-question-answer-selected");
        })
        .appendTo("#quizz-questions");
      });
      $("#quizz-question-header").text(question.question);
    }

    nextQuestion();
  }

  function login_success() {
    $('#quizwebsite-login').addClass("hide");
    $('#quizwebsite-subject-modal').removeClass("hide");
    $.getJSON('subjects.php')
    .done(function(data) {
      $("#subjects").empty();
      data.subjects.forEach(function(subject) {
        $("<div class='panel panel-default'>" +
          "<div class='panel-body'>" +
          "<h1>" + subject.subject + "</h1>" +
          "<a class='btn btn-primary btn-lg' href='#' role='button'>Practice</a></div>" +
          "</div></div>")
          .click(function() {
            practice(subject);
          })
          .appendTo("#subjects");
      });
    });
  }

  function login() {
    $('#login').addClass('active');
    $.post('login.php', {
      name: $('#email-input').val(),
      pass: $('#password-input').val()
    }).done(function(data) {
      $('#login').removeClass('active');
      if (data == 'okay') {
        login_success();
      } else if (data == 'user does not exist') {
        $('#create-user').removeClass('hide');
      } else if (data == 'invalid password') {
        $('#bad-password').removeClass('hide');
      }
    });
  }

  $('#login-form').submit(function(event) {
    event.preventDefault();
    login();
  });

  function getStatus(method, url, cb) {
    var request = new XMLHttpRequest();
    request.onreadystatechange = function() {
      if (this.readyState == this.DONE && request.status == 200) {
        if (this.responseText == 'true')
          cb(true);
        else if (this.responseText == 'false')
          cb(false);
      }
    };
    request.open(method, url);
    request.send();
  }

  function home() {
    $('#quizwebsite-login').addClass("hide");
    $('#quizwebsite-subject-modal').addClass("hide");
    $('#quizwebsite-question-modal').addClass("hide");
    getStatus('GET', 'login.php', function(bool) {
      if (!bool) {
        $('#quizwebsite-login').removeClass('hide');
      } else {
        login_success();
      }
    });
  }

  $("#home").click(function() {
    home();
  });

  home();
