<?php
	/*
	Patient symptoms form
	This page goes to /diagnosis/diagnose with the symptoms list
	*/
	//Check if session ID is valid:
	if(isset($_COOKIE["session_id"])) {
		//Get session ID:
		$session_id = $_COOKIE["session_id"];
	}
	else {
		//Else, redirect to home page:
		header("Location: /index.php");
	}
	
	//If login is not anonymous:
	if($session_id != "ANONYMOUS") {
		//Verify user ID:
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
		
		if(isset($_COOKIE["age"])) {
			//Get age from cookie:
			$age = $_COOKIE["age"];
		}
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
				<?php if(!isset($age)) { ?>
					<input type="number" name="age" min="0" value="0" required>
				<?php } else { ?>
					<input type="hidden" name="age" value=<?php echo $age; ?>>
					<?php echo $age."<br>";
				}?>
				<br>
			</div>
			<div id="symptom-controls">
				<input type="button" value="Add new Symptom" onclick="addSymptom()">
				<input type="submit" value="Get Diagnosis" onclick="makeSymptomsString()">
			</div>
		</fieldset>
	</form>
	<a class="control-link" href="/dashboard.php">Cancel</a>
</body>
<!--Make sure there's at least one <select> element-->
<script type="text/javascript">addSymptom();</script>
</html>
