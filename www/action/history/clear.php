<?php
	//Clear's a patient's diagnosis history:
	//If a session cookie is present:
	if(isset($_COOKIE["session_id"])) {
		//Get session ID:
		$session_id = $_COOKIE["session_id"];
	}
	else {
		//Else, redirect to home page:
		header("Location: /index.php");
	}
	
	//Check if login is anonymous:
	if($session_id == "ANONYMOUS") {
		//Redirect:
		header("Location: /dashboard.php");
	}
	else {
		//Connect to database:
		$dbConn = new PDO("mysql:host=localhost;dbname=cse3002", "project", "project");
		//Validate cookie:
		$getSessionIdMatch = $dbConn->prepare("select username from login where session_id=?");
		$getSessionIdMatch->execute([$_COOKIE["session_id"]]);
		//If session_id is invalid, redirect to index page:
		$tempRow = $getSessionIdMatch->fetch(PDO::FETCH_ASSOC);
		if(!$tempRow) {
			//Unset session_id cookie:
			unset($_COOKIE["session_id"]);
			//Redirect to front page:
			header("Location: /index.php");
		}
		//Set username:
		$username = $tempRow["username"];
		
		//Delete records from DB:
		$deleteHistory = $dbConn->prepare("delete from diagnoses where username=?");
		$deleteHistory->execute([$username]);
		//Redirect to history page:
		header("Location: /history.php");
		//Close connection:
		$dbConn->close();
	}
?>