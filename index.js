// post to create-user.php on create user
$('#create-user').off('click');
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

// used for fosucing in on inputs as needed
$('#email-input').focus(function(event) {
  $('#create-user').addClass('hide');
  $('#user-not-found').addClass('hide');
  $('#bad-password').addClass('hide');
  $('#already-exists').addClass('hide');
  $('#password').removeClass('hide');
});

// used for fosucing in on inputs as needed
$('#password-input').focus(function(event) {
  $('#user-not-found').addClass('hide');
  $('#bad-password').addClass('hide');
  $('#login').removeClass('hide');
});

var letters = 'abcdefghijklmnopqrstuvwxyz';

// called to practice a subject
function practice(subject, max_results) {
  var questions = [];

  function nextQuestion() {
    $('#check').removeClass('hide');
    $('#next').addClass('hide');
    $('#quizwebsite-subject-modal').addClass('hide');
    $('#quizwebsite-question-modal').removeClass('hide');
    if (questions.length > 0) {
      loadQuestion(questions.pop());
    } else {
      // TODO: add status page?
      home();
    }
  }

  $('#next').off('click');
  $('#next').click(function() {
    nextQuestion();
  });

  function loadQuestion(question) {
    $('#check').off('click');
    $('#check').click(function() {
      var answer = $('.quizz-question-answer-selected .quizz-question-answer-answer').first().text();
      $.post('check-question.php', {id: question.id, answer: answer}, function(data) {
        $('#score').text(Math.round(data.correct / (data.correct + data.incorrect) * 100) + '% correct');
        if (data.success) {
          $('#next').removeClass('hide');
          $('#check').addClass('hide');
          $('<i class="glyphicon glyphicon-ok-circle answer-icon"></i>')
            .appendTo('.quizz-question-answer-selected');
        } else {
          $('<i class="glyphicon glyphicon-remove-circle answer-icon"></i>')
            .appendTo('.quizz-question-answer-selected');
          $('.quizz-question-answer-selected')
            .addClass('quizz-question-answer-wrong')
            .removeClass('quizz-question-answer-selected');
        }
      }, 'json');
    })

    $('#quizz-questions').empty();
    question.answers.forEach(function(answer, i) {
      var letter = letters[i];
      $('<div class="quizz-question-answer">' +
        '<span class="quizz-question-answer-id">' + letter +
        '.</span><span class="quizz-question-answer-answer">' + answer +
        '</span></div>')
      .click(function() {
        if (!$('#check').hasClass('hide') && !$(this).hasClass('quizz-question-answer-wrong')) {
          $('.quizz-question-answer').removeClass('quizz-question-answer-selected');
          $(this).addClass('quizz-question-answer-selected');
        }
      })
      .appendTo('#quizz-questions');
    });
    $('#quizz-question-header').text(question.question);
  }

  $.getJSON('get-questions.php?max_results=' + max_results + '&subject=' + subject.subject)
  .done(function(data) {
    questions = data.questions;
    if (questions.length == 0) {
      home();
      alert("no questions for that subject");
    } else {
      nextQuestion();
    }
  });
}

// called when user has successfully logged in
function login_success() {
  $('#quizwebsite-login').addClass('hide');
  $('#quizwebsite-subject-modal').removeClass('hide');
  $.getJSON('subjects.php')
  .done(function(data) {
    $('#subjects').empty();
    data.subjects.forEach(function(subject) {
      var el = $('<div class="panel-body">' +
        '<h1>' + subject.subject + '</h1>' +
        '</div>'
      );
      var ell = $('<div class="btn-group btn-group-justified"></div>');
      $('<div class="btn-group" role="group"></div>')
        .append($('<button type="button" class="btn btn-lg">Practice x1</button>')
          .css('color', '#FFFFFF')
          .css('background-color', '#F0AD4E')
          .css('border-color', '#EEA236')
          .click(function() {
            practice(subject, 1);
          })
        ).appendTo(ell);
      $('<div class="btn-group" role="group"></div>')
        .append($('<button type="button" class="btn btn-primary btn-lg">Practice x5</button>')
          .click(function() {
            practice(subject, 5);
          })
        ).appendTo(ell);
      $('<div class="btn-group" role="group"></div>')
        .append($('<button type="button" class="btn btn-lg">Practice x10</button>')
          .css('color', '#FFFFFF')
          .css('background-color', '#C9302C')
          .css('border-color', '#AC2925')
          .click(function() {
            practice(subject, 10);
          })
        ).appendTo(ell);
      el.append(ell);
      $('<div class="panel panel-default"></div>').append(el).appendTo('#subjects');
    });
  });
}

// called to login
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

// called when login form is submitted
$('#login-form').submit(function(event) {
  event.preventDefault();
  login();
});

// open home page of subjects
function home() {
  $('#quizwebsite-login').addClass('hide');
  $('#quizwebsite-subject-modal').addClass('hide');
  $('#quizwebsite-question-modal').addClass('hide');

  $.get('login.php')
    .done(function (data) {
      if (data == 'false') {
        $('#quizwebsite-login').removeClass('hide');
      } else if (data == 'true') {
        login_success();
      }
    });

  $.getJSON('user.php')
  .done(function(data) {
    $('#score').text(Math.round(data.correct / (data.correct + data.incorrect) * 100) + '% correct');
    $('#user-name-nav').text(data.user);
  });
}

// home link on toolbar
$('#home').click(function() {
  event.preventDefault()
  home();
});

home();
