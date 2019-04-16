function addEventAjax(event) {
	var eventName = document.getElementById("eventName").value;
	var eventContent = document.getElementById("eventContent").value;
	var eventDate = document.getElementById("eventDate").value;
	var eventTime = document.getElementById("eventTime").value;
	var dataString = "";

	if (document.getElementById("yes_group").checked) {
		if (document.getElementById("ca_emergency").checked) {
			dataString = "eventName=" + encodeURIComponent(eventName) + "&eventContent=" + encodeURIComponent(eventContent) + "&eventDate=" + encodeURIComponent(eventDate) + "&eventTime=" + encodeURIComponent(eventTime) + "&is_group=1" + "&category=1" + "&token=" + encodeURIComponent(token);
		}else if (document.getElementById("ca_normal").checked) {
			dataString = "eventName=" + encodeURIComponent(eventName) + "&eventContent=" + encodeURIComponent(eventContent) + "&eventDate=" + encodeURIComponent(eventDate) + "&eventTime=" + encodeURIComponent(eventTime) + "&is_group=1" + "&category=0" + "&token=" + encodeURIComponent(token);
		}else{
			alert("Tag cannot be empty.");
		}
	}else if (document.getElementById("no_group").checked) {
		if (document.getElementById("ca_emergency").checked) {
			dataString = "eventName=" + encodeURIComponent(eventName) + "&eventContent=" + encodeURIComponent(eventContent) + "&eventDate=" + encodeURIComponent(eventDate) + "&eventTime=" + encodeURIComponent(eventTime) + "&is_group=0" + "&category=1" + "&token=" + encodeURIComponent(token);
		}else if (document.getElementById("ca_normal").checked) {
			dataString = "eventName=" + encodeURIComponent(eventName) + "&eventContent=" + encodeURIComponent(eventContent) + "&eventDate=" + encodeURIComponent(eventDate) + "&eventTime=" + encodeURIComponent(eventTime) + "&is_group=0" + "&category=0" + "&token=" + encodeURIComponent(token);
		}else{
			alert("Tag cannot be empty");
		}
	}else{
		alert("Please choose if it is a group event or not.");
		return;
	}

	var xmlHttp = new XMLHttpRequest();
	xmlHttp.open("POST", "addEvent_ajax.php", true);
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xmlHttp.addEventListener("load", function(event){
		var jsonData = JSON.parse(event.target.responseText);
		if (jsonData.success) {
			alert("You've added an event.");
			$("#addEventForm").hide();
			updateUserCalendar();
		}else{
			alert(jsonData.message);
			//console.log(jsonData.aboutToken);
			//console.log(jsonData.aboutoldToken);
			//$("#addEventForm").hide();
		}
	}, false);
	xmlHttp.send(dataString);
}
document.getElementById("submit_AddEvent").addEventListener("click", addEventAjax, false);