//Check if DOB is invalid:
function validateDob(year, month, day) {
	//Get today's date:
	var todayDate=new Date();
	//Compare dates:
	if(year>todayDate.getFullYear()) return false;
	else {
		if(month>(todayDate.getMonth()+1)) return false;
		else if(day>todayDate.getDay()) return false;
	}
	//If date <= today's date:
	return true;
}

function validateForm() {
	//Alert box text:
	alertText="";
	
	//Check if passwords are equal:
	//Get values of password and confirm_password:
	var password=document.getElementById("password").value;
	var confirm_password=document.getElementById("confirm_password").value;
	//Add alertText:
	if(password.length>0 && password!=confirm_password) {
		alertText+="Passwords do not match!\n";
	}
	
	//Validate given Date of Birth:
	//Get date from form:
	var dob=document.getElementById("dob").value.split("-");
	var formYear=parseInt(dob[0]);
	var formMonth=parseInt(dob[1]);
	var formDay=parseInt(dob[2]);
	//If DOB is invalid (<=today), add alertText:
	/*
	This doesn't work:
	if(validateDob(formYear, formMonth, formDay)===false) {
		alertText+="Invalid Date of Birth!";
	}
	*/
	
	//If form data is invalid:
	if(alertText.length>0) {
		//Show alert:
		alert(alertText);
		//Disable form action:
		document.getElementById("register-form").action="#";
	}
}
