<?php
	//Remove the session_id cookie
	if(isset($_COOKIE["session_id"])) {
		//Get session_id:
		$session_id = $_COOKIE["session_id"];
		//Check if login is anonymous:
		if($session_id == "ANONYMOUS") {
			//Unset session_id cookie:
			setcookie("session_id", "", time()-1, "/");
			//Redirect to front page:
			header("Location: /index.php");
		}
		else {
			//Connect to DB:
			$dbConn=new PDO("mysql:host=localhost;dbname=cse3002", "project", "project");
			//Remove session_id from DB:
			$getUsername=$dbConn->prepare("update login set session_id=? where session_id=?");
			$getUsername->execute(["none", $session_id]);
			//Remove age cookie:
			setcookie("age", "", time()-1, "/");
			//Redirect to /action/login.php:
			header("Location: /action/login.php");
		}
	}
	else {
		//Redirect user to /index.php
		header("Location: /index.php");
	}
?>
