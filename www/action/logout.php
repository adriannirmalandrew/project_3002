<?php
	//Remove the session_id cookie
	if(isset($_COOKIE["session_id"])) {
		//Get session_id:
		$session_id=$_COOKIE["session_id"];
		//Connect to DB:
		$dbConn=new PDO("mysql:host=localhost;dbname=cse3002", "project", "project");
		//Remove session_id from DB:
		$getUsername=$dbConn->prepare("update login set session_id=? where session_id=?");
		$getUsername->execute(["none", $session_id]);
		//Redirect to /action/login.php:
		header("Location: /action/login.php");
	}
	//Redirect user to /index.php
	header("Location: /index.php");
?>
