<?php
	//Get patient's diagnosis history:
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
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Patient History</title>
	<link rel="stylesheet" type="text/css" href="/css/history.css"></link>
</head>
<body>
	<div id="title">
		<h2>Diagnosis History</h2>
	</div>
	<div id="username">
		<h3>Showing history for: <?php echo $username; ?></h3>
		<a class="control-link" href="/dashboard.php">Go Back</a>
		<a class="control-link" href="/action/logout.php">Logout</a>
		<a class="control-link" href="/action/history/clear.php">Clear History</a>
	</div>
	<!--History of patient's diagnoses-->
	<div id="history-data">
		<table id="history-table">
			<tr id="history-table-header">
				<th>Date of Diagnoses</th>
				<th>Diagnosed Disease</th>
				<th>Symptoms</th>
			</tr>
			<?php
				//Get history from DB:
				$historyGetter = $dbConn->prepare("select diag_datetime,disease,symptoms from diagnoses where username=? order by diag_datetime desc");
				$historyGetter->execute([$username]);
				while(($historyRow = $historyGetter->fetch(PDO::FETCH_ASSOC)) != null) {
					$diag_datetime = $historyRow["diag_datetime"];
					$disease = $historyRow["disease"];
					$symptoms = $historyRow["symptoms"];
			?>
			<tr>
				<th><?php echo $diag_datetime; ?></th>
				<td><?php echo $disease; ?></td>
				<td><?php echo $symptoms; ?></td>
			</tr>
			<?php
				}
			?>
		</table>
	</div>
</body>
</html>

<?php
	//Close connection;
	$dbConn->close();
?>