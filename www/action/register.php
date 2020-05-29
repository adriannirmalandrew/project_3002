<?php
	//Print an error page:
	function printErrorPage($errorMsg) {
?>
<html>
<head>
	<title>Registration Error</title>
	<style type="text/css">
		body {
			text-align: center;
			font-family: sans-serif;
		}
	</style>
</head>
<body>
	<h1>Registration Error</h1>
	<h2><?php echo $errorMsg?></h2>
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
	
	//Connect to DB:
	$connect=new PDO("mysql:host=localhost;dbname=cse3002", "project", "project");
	//Get account details:
	$username=$_POST["username"];
	$password=$_POST["password"];
	$confirm_password=$_POST["confirm_password"];
	//Check if passwords match:
	if($password!=$confirm_password) {
		printErrorPage("Passwords do not match!");
		exit();
	}
	//Check if account already exists:
	$accountExists=$connect->prepare("select username from login where username=?");
	$accountExists->execute([$username]);
	$accountRow=$accountExists->fetch(PDO::FETCH_ASSOC);
	//If account exists, print error page and exit:
	if($accountRow["username"]==$username) {
		printErrorPage("Account already exists!");
		exit();
	}
	//Else, add the new account:
	else {
		//Hash the password:
		$password_hash=hash("sha256", $password);
		//Insert login into DB:
		$newAccount=$connect->prepare("insert into login(username, password_hash) values(?,?)");
		$newAccount->execute([$username, $password_hash]);
		//Get patient details:
		$first_name=$_POST["first_name"];
		$last_name=$_POST["last_name"];
		$dob=$_POST["dob"];
		$sex=$_POST["sex"];
		//Insert details into table:
		$insertNewRecord=$connect->prepare("insert into patients values(?,?,?,?,?)");
		$insertNewRecord->execute([$username, $first_name, $last_name, $dob, $sex]);
		//Redirect user to login.php with username and password, to generate session_id:
		header("Location: /");
	}
?>
