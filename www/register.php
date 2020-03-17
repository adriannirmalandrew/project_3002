<?php
	//If a session cookie is already present:
	if(isset($_COOKIE["session_id"])) {
		//Redirect user to /action/login.php:
		header("Location: /action/login.php");
	}
?>

<!--Registration form-->
<!DOCTYPE html>
<html>
<head>
	<title>Registration</title>
	<script type="text/javascript" src="/scripts/register.js"></script>
	<link rel="stylesheet" href="/css/register.css">
</head>
<body>
	<div id="title">
		<h2>Register New Patient</h2>
	</div>
	<!--Registration form-->
	<div id="register-form">
	<form action="/action/register.php" method="post">
		<fieldset id="account-details">
			<legend>Account Details</legend>
			<p>
				<label for="username">Username</label>
				<input type="text" name="username" required>
			</p>
			<p>
				<label for="password">Password</label>
				<input type="password" id="password" name="password" required>
			</p>
			<p>
				<label for="confirm_password">Confirm Password</label>
				<input type="password" id="confirm_password" name="confirm_password" required>
			</p>
		</fieldset>
		<fieldset id="patient-details">
			<legend>Patient Details</legend>
			<p>
				<label for="first_name">First Name</label>
				<input type="text" name="first_name" required>
			</p>
			<p>
				<label for="last_name">Last Name</label>
				<input type="text" name="last_name" required>
			</p>
			<p>
				<label for="dob">Date of Birth</label>
				<input type="date" id="dob" name="dob" required>
			</p>
			<p>
				<label for="sex">Sex</label>
				<input type="radio" name="sex" value="M" required>Male
				<input type="radio" name="sex" value="F" required>Female
			</p>
		</fieldset>
		<input type="submit" value="Register" onclick="validateForm()">
	</form>
	</div>
</body>
</html>
