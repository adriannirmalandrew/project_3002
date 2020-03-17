function showHospitals(hospitalString) {
	var hospitalDiv=document.getElementById("hospitals");
	hospitalDiv.innerHTML+=hospitalString;
}

function getHospitals() {
	//Get user's location:
	if('geolocation' in navigator) {
		navigator.geolocation.getCurrentPosition(function(position) {
			var latitude = position.coords.latitude;
			var longitude = position.coords.longitude;
			showHospitals(latitude + ", " + longitude);
			console.log(latitude);
			console.log(longitude);
		});
	}
	//Make AJAX query:
	
	//Add hospital names to document:
	showHospitals();
}
