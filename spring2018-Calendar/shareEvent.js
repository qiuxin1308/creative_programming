var currentEventId;

function clickShareEvents(eventId){
	// console.log(eventId);
	$("#shareEventForm").show();
	$("#displayEventsForm").hide();
	document.getElementById("share_user").value = "";
	currentEventId = eventId;
}

function shareEvent(event){
	// console.log(currentEventId);
	// console.log("shareEvent");

	var username = document.getElementById("share_user").value;
	var dataString = "username=" + encodeURIComponent(username) + "&eventId=" + encodeURIComponent(currentEventId) + "&token=" + encodeURIComponent(token);

	//Initialize our XMLHttpRequest instance
	var xmlHttp = new XMLHttpRequest();
	xmlHttp.open("POST", "shareEvent_ajax.php", true);
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xmlHttp.addEventListener("load", function(event){
		var jsonData = JSON.parse(event.target.responseText);
		if (jsonData.success) {  //return success == true
			alert("You've share the event to other user.");
			$("#shareEventForm").hide();
			$("#displayEventsForm").hide();
		}else{
			alert(jsonData.message);
		}
	}, false);
	xmlHttp.send(dataString);
}

document.getElementById("submit_share").addEventListener("click",shareEvent,false);

