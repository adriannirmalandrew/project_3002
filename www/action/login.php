<?php
	//Create a session_id:
	function createSessionId($uname): string {
		//Get current system time in milliseconds:
		$time=(string)time();
		//Return session_id string:
		return $uname."_".$time;
	}
	
	//Connect to database:
	$loginDbConn=new PDO("mysql:host=localhost;dbname=cse3002", "project", "project");
	//If session_id cookie exists:
	if(isset($_COOKIE["session_id"])) {
		$session_id=$_COOKIE["session_id"];
		//Check validity
		//Get row from database:
		$getSessionIdMatch=$loginDbConn->prepare("select username from login where session_id=?");
		$getSessionIdMatch->execute([$session_id]);
		$sessionIdRow=$getSessionIdMatch->fetch(PDO::FETCH_ASSOC);
		//If session_id is valid:
		if($sessionIdRow) {
			//Redirect to User dashboard:
			header("Location: /dashboard.php");
		}
		else {
			//Unset session_id cookie:
			setcookie("session_id", "", time()-1, "/");
			//Redirect to front page:
			header("Location: /index.php");
		}
	}
	else {
		//Get username and password:
		$username=$_POST["username"];
		$password=$_POST["password"];
		//Hash password:
		$passwordHash=hash("sha256", $password);
		//Check username and (hashed) password:
		//Create DB statement:
		$authCheck=$loginDbConn->prepare("select username from login where username=? and password_hash=?");
		$authCheck->execute([$username, $passwordHash]);
		$resultRow=$authCheck->fetch(PDO::FETCH_ASSOC);
		//Check if the record matches:
		if($resultRow["username"]==$username) {
			$session_id=createSessionId($username);
			//Set the session_id cookie:
			setcookie("session_id", $session_id, time()+60*60*24*365, "/");
			//Insert session_id into DB:
			$insertSessionId=$loginDbConn->prepare("update login set session_id=? where username=?");
			$insertSessionId->execute([$session_id, $username]);
			//Redirect user to User dashboard:
			header("Location: /dashboard.php");
		}
		else {
			?>
<html>
<head>
	<title>Invalid Login</title>
	<style type="text/css">
		body {
			text-align: center;
			font-family: sans-serif;
		}
	</style>
</head>
<body>
	<h1>Invalid Login</h1>
	<h2>Incorrect Username/Password</h2>
	<h4>Redirecting...</h4>
</body>
<script type="text/javascript">
	function indexRedirect() {
		window.location="/";
	}
	setTimeout("indexRedirect()", 3000);
</script>
</html>
			<?php
		}
	}
?>
