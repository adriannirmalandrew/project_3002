<?php
	//If a session cookie is already present:
	if(isset($_COOKIE["session_id"])) {
		//Redirect user to /action/login.php:
		header("Location: /action/login.php");
	}
?>

<!--Front page. Will give the user a choice of login or continue directly-->
<!DOCTYPE html>
<html>
<head>
	<title>HealthApp</title>
	<link rel="stylesheet" type="text/css" href="/css/index.css"></link>
</head>
<body>
	<div id="title">
		<h1>Diagnosticizer</h1>
		<h3>The complete medical diagnostics service</h3>
	</div>
	<!--Login form-->
	<div id="login-form">
	<form action="/action/login.php" method="post">
		<fieldset id="login-fields">
			<legend>Login to Get Started</legend>
			<p>
				<label for="username">Username</label>
				<input type="text" name="username" required>
			</p>
			<p>
				<label for="password">Password</label>
				<input type="password" name="password" required>
			</p>
			<p><input type="submit" value="Login"></p>
		</fieldset>
	</form>
	</div>
	<!--Options to register or continue without signing in-->
	<div id="links">
		<a class="control-link" href="/register.php">Register</a>
		<a class="control-link" href="/action/anonymous_login.php">Continue without Login</a>
	</div>
	<!--Authors-->
	<div id="authors">
		<h4>A CSE3002 Project by:</h4>
		<h5>Adrian Nirmal Andrew 18BCE1164</h5>
		<h5>Madhurima Magesh 18BCE1009</h5>
		<h4>Faculty: Dr Justus S</h4>
	</div>
</body>
</body>
</html>
