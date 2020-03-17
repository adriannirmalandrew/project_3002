<?php
	/*Symptoms form.This page goes to /diagnosis/diagnose with the symptoms list*/
	//Check if session ID is valid:
	if(isset($_COOKIE["session_id"])) {
		//Connect to database:
		$checkSessionId=new PDO("mysql:host=localhost;dbname=cse3002", "project", "project");
		//Validate cookie:
		$getSessionIdMatch=$checkSessionId->prepare("select username from login where session_id=?");
		$getSessionIdMatch->execute([$_COOKIE["session_id"]]);
		//If session_id is invalid, redirect to index page:
		$tempRow=$getSessionIdMatch->fetch(PDO::FETCH_ASSOC);
		if(!$tempRow) {
			//Unset session_id cookie:
			unset($_COOKIE["session_id"]);
			//Redirect to front page:
			header("Location: /index.php");
		}
	}
	//Else, go back to home page:
	else {
		//Redirect to /index.php
		header("Location: /index.php");
	}
?>

<html>
<head>
	<title>Get Diagnosis</title>
	<link rel="stylesheet" type="text/css" href="/css/symptoms.css">
	<script type="text/javascript" src="/scripts/symptoms.js"></script>
</head>
<body>
	<div id="title">
		<h2>Get Diagnosis</h2>
	</div>
	<form action="/diagnose" method="post" id="symptom-form">
		<fieldset>
			<legend>Select your symptoms</legend>
			<div id="symptom-inputs">
				<input type="hidden" id="symptom-string" name="symptoms">
				<label for="age">Age:</label>
				<input type="number" name="age" value="30" required><br>
			</div>
			<div id="symptom-controls">
				<input type="button" value="Add new Symptom" onclick="addSymptom()">
				<input type="submit" value="Get Diagnosis" onclick="makeSymptomsString()">
			</div>
		</fieldset>
	</form>
	<a href="/dashboard.php">Cancel</a>
</body>
<!--Make sure there's at least one <select> element-->
<script type="text/javascript">addSymptom();</script>
</html>
