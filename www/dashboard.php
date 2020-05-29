<?php
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
		//Set username:
		$username = "Anonymous";
		//Set data fields:
		$first_name = "Anonymous";
		$last_name = "N/A";
		$dob = "N/A";
		$sex = "N/A";
		//Set last diagnosis:
		$last_diagnosis = "N/A";
		$last_symptoms = "N/A";
		$last_date = "N/A";
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
		
		//Get patient details:
		$getPatientDetails = $dbConn->prepare("select * from patients where username=?");
		$getPatientDetails->execute([$username]);
		$detailsRow = $getPatientDetails->fetch(PDO::FETCH_ASSOC);
		//Get fields:
		$first_name = $detailsRow["first_name"];
		$last_name = $detailsRow["last_name"];
		$dob = $detailsRow["dob"];
		$sex = $detailsRow["sex"];
		//Calculate age:
		$curDate = getdate()[0];
		$dobDate = strtotime($dob);
		$dateDiff = abs($curDate - $dobDate);
		$age = floor($dateDiff / (365*60*60*24));
		//Set age cookie:
		setcookie("age", $age, time()+60*60*24*365, "/");
		
		//Get last diagnosis:
		$getLastDiagnosis = $dbConn->prepare("select * from diagnoses where diag_datetime=(select max(diag_datetime) from diagnoses) and username=?");
		$getLastDiagnosis->execute([$username]);
		$lastDiagnosis = $getLastDiagnosis->fetch(PDO::FETCH_ASSOC);
		//Get fields:
		if(!$lastDiagnosis) {
			//If no diagnoses made:
			$last_date = "None";
			$last_disease = "None";
			$last_symptoms = "None";
		}
		else {
			$last_date = $lastDiagnosis["diag_datetime"];
			$last_disease = $lastDiagnosis["disease"];
			$last_symptoms = $lastDiagnosis["symptoms"];
		}
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
		<h3>Currently Logged in as: <?php echo $username; ?></h3>
		<a class="control-link" href="/action/logout.php">Logout</a>
	</div>
	<div id="diagnosis">
		<fieldset id="diagnosis-controls">
			<legend>Diagnosis</legend>
			<!--Link to get new diagnosis-->
			<h3><a class="control-link" href="/symptoms.php">Get a Diagnosis</a></h3>
			<!--Section with patient's latest diagnosis-->
			<fieldset>
				<legend>Last Diagnosis</legend>
				<table id="last-diagnosis-details">
					<tr>
						<th>Diagnosed Disease:</th>
						<td><?php echo $last_disease; ?></td>
					</tr>
					<tr>
						<th>Symptoms:</th>
						<td><?php echo $last_symptoms; ?></td>
					</tr>
					<tr>
						<th>Date of Diagnosis:</th>
						<td><?php echo $last_date; ?></td>
					</tr>
				</table>
				<?php if($session_id != "ANONYMOUS") { ?>
					<br>
					<a class="control-link" href="/history.php">Show full history</a>
				<?php } ?>
			</fieldset>
		</fieldset>
	</div>
	<div id="patient">
		<!--Patient Details-->
		<fieldset>
			<legend>Patient Details</legend>
			<table id="patient-details">
				<tr>
					<th>First Name:</th>
					<td><?php echo $first_name; ?></td>
				</tr>
				<tr>
					<th>Last Name:</th>
					<td><?php echo $last_name; ?></td>
				</tr>
				<tr>
					<th>Date of Birth:</th>
					<td><?php echo $dob; ?>&nbsp;(Age: <?php echo $age; ?> years)</td>
				</tr>
				<tr>
					<th>Sex:</th>
					<td><?php echo $sex; ?></td>
				</tr>
			</table>
		</fieldset>
	</div>
</body>
</html>

<?php
	//Close Connection:
	$dbConn->close();
?>