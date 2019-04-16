function shareCalendarAjax() {
	if (document.getElementById("share_calendarUser").value === "") {
		alert("Please enter username.");
		return;
	}else{
		var xmlHttp = new XMLHttpRequest();
		xmlHttp.open("POST", "share_selectById.php", true);
		xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xmlHttp.addEventListener("load",selectByIdAjax,false); //parse events JSON data to displayEventAjax
		xmlHttp.send(null);
	}
}

function selectByIdAjax(event) {
	var jsonData = JSON.parse(event.target.responseText);
	if (jsonData.success) {
		for (var i = 0; i < jsonData.message.length; i++) {
			var shareUserName = document.getElementById("share_calendarUser").value;
			//console.log(jsonData.message[i].eventId);
			var eventId = jsonData.message[i].eventId;
			var eventName = jsonData.message[i].eventTitle;
			var eventContent = jsonData.message[i].eventContent;
			var eventDate = jsonData.message[i].eventDate;
			var eventTime = jsonData.message[i].eventTime;
			var is_group = jsonData.message[i].is_group;
			var sharedEventId = jsonData.message[i].sharedEventId;
			var jsonDataLength = jsonData.message.length;

			var dataString = "eventName=" + encodeURIComponent(eventName) + "&eventId=" + encodeURIComponent(eventId) + "&eventContent=" + encodeURIComponent(eventContent) + "&eventDate=" + encodeURIComponent(eventDate) + "&eventTime=" + encodeURIComponent(eventTime) + "&is_group=" + encodeURIComponent(is_group) + "&sharedEventId=" + encodeURIComponent(sharedEventId) + "&shareUserName=" + encodeURIComponent(shareUserName) + "&jsonDataLength=" + encodeURIComponent(jsonDataLength) + "&countNumber=" + encodeURIComponent(i) + "&token=" + encodeURIComponent(token);
			var xmlHttp = new XMLHttpRequest();
			xmlHttp.open("POST", "share_insertById.php", true);
			xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			xmlHttp.addEventListener("load",shareInsertByIdAjax,false);
			xmlHttp.send(dataString); 
		}
	}
}

function shareInsertByIdAjax(event) {
	var jsonData = JSON.parse(event.target.responseText);
	$("#shareCalendarForm").hide();
	if (String(jsonData.countNumber) === String(jsonData.jsonDataLength - 1)) {
		alert("You've shared your calendar.");
		//console.log(jsonData.arrayResult);
		//$("#shareCalendarForm").hide();
	}else if (jsonData.success === false) {
		//alert(jsonData.message);
		console.log(jsonData.message);
	}
}

document.getElementById("submit_shareCalendar").addEventListener("click",shareCalendarAjax,false);