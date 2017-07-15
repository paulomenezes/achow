<?php
	session_start();

	session_destroy();
	$_SESSION['userID'] = null;
	$_SESSION = array();
	unset($_SESSION);

	header("Location:login.php");
?>