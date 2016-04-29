

<?php
	session_start();
	session_destroy();
	header('location: practice_login.html');
	exit;
?>