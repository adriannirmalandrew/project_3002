<?php
	//If a session cookie is already present:
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
		$username=$tempRow["username"];
	}
	//Else, go back to home page:
	else {
		//Redirect to /index.php
		header("Location: /index.php");
	}
?>

<!--User dashboard. User can get a diagnosis or check their history-->
<!DOCTYPE html>
<html>
<head>
	<title>Patient Dashboard</title>
	<link rel="stylesheet" type="text/css" href="/css/dashboard.css"></link>
</head>
<body>
	<div id="title">
		<h2>Patient Dashboard</h2>
	</div>
	<div id="username">
		<h3>
			Currently Logged in as: <?php echo $username; ?>
			<br>
			<a href="/action/logout.php">Logout</a>
		</h3>
	</div>
	<!--Link to get new diagnosis-->
	<a href="/symptoms.php">Get new Diagnosis</a>
	<!--Section with patient's diagnosis history-->
</body>
</html>
