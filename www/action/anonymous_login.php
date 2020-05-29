<?php
	//Anonymous login
	//Sets the session_id cookie to "ANONYMOUS":
	setcookie("session_id", "ANONYMOUS", time()+60*60*24*365, "/");
	//Redirects to login.php:
	header("Location: /dashboard.php");
?>
