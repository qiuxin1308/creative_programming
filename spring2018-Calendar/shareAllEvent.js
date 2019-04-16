function shareAllEvent(event){
	// console.log(currentEventId);
	// console.log("shareEvent");
	var currentUsername = document.getElementById("displayUsername").value;
	var username = document.getElementById("submit_share_all").value;
	var dataString = "currentUsername=" + encodeURIComponent(currentUsername) + "&username=" + encodeURIComponent(currentEventId);

	//Initialize our XMLHttpRequest instance
	var xmlHttp = new XMLHttpRequest();
	xmlHttp.open("POST", "shareEvent_ajax.php", true);
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xmlHttp.addEventListener("load", function(event){
		var jsonData = JSON.parse(event.target.responseText);
		if (jsonData.success) {  //return success == true
			alert("You've share the event to other user.");
			$("#shareEventForm").hide();
			$("#displayEventsForm").show();
		}else{
			alert(jsonData.message);
			// $("#loginForm").hide();
		}
	}, false);
	xmlHttp.send(dataString);
}

document.getElementById("submit_share_all").addEventListener("click",shareAllEvent,false);