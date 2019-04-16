var todayDates = new Date(); //get the current date: Fri Mar 16 2018 22:36:13 GMT-0500 (CDT)
// var today = todayDates.getFullYear()+(todayDates.getMonth()+1)+todayDates.getDate();
var currentMonth = new Month(todayDates.getFullYear(), todayDates.getMonth());

//hide all the dialog forms
$("#registerForm").hide();
$("#loginForm").hide();
$("#addEventForm").hide();
$("#displayEventsForm").hide();
$("#editEventForm").hide();
$("#shareEventForm").hide();
$("#shareCalendarForm").hide();
$("#checkValidForm").hide();

document.addEventListener("DOMContentLoaded",updateCalendar,false);

document.getElementById("prev_month_btn").addEventListener("click",function(event){
	currentMonth = currentMonth.prevMonth();
	if (document.getElementById("displayUsername").textContent === "") {
		updateCalendar();
	}else{
		updateUserCalendar();
	}
	//alert("The new month is "+currentMonth.month+" "+currentMonth.year);
},false);
document.getElementById("next_month_btn").addEventListener("click",function(event){
	currentMonth = currentMonth.nextMonth();
	if (document.getElementById("displayUsername").textContent === "") {
		updateCalendar();
	}else{
		updateUserCalendar();
	}
	//alert("The new month is "+currentMonth.month+" "+currentMonth.year);
},false);



function updateCalendar(){
	var weeks = currentMonth.getWeeks();
	var months = ["January","February","March","April","May","June","July","August","September","October","November","December"];
	var weekDays = ["Sun","Mon","Tue","Wed","Thu","Fri","Sat"];
	//show current year and month
	document.getElementById("showCurrentMonth").innerHTML = months[currentMonth.month];
	document.getElementById("showCurrentYear").innerHTML = currentMonth.year;
	document.getElementById("weekdays").innerHTML = "";
	document.getElementById("days").innerHTML = "";

	//show weekDays title
	for(var i = 0; i < weekDays.length; i++) {
		document.getElementById("weekdays").innerHTML += '<li>'+weekDays[i]+'</li>';
	}

	// //iterate the weeks, for each week get its days
	for(var w = 0; w < weeks.length; w++){
		var days = weeks[w].getDates(); 

		for(var d = 0; d < days.length; d++){
			//for the first week, there will be some days in the prev month
			if(days[d].getMonth()!==currentMonth.month){
				document.getElementById("days").innerHTML += '<li id="notThisMonth">'+days[d].getDate()+'</li>';
			}else{
				if ((currentMonth.year == todayDates.getFullYear()) && (currentMonth.month == todayDates.getMonth()) && (days[d].getDate() == todayDates.getDate())) {
						document.getElementById("days").innerHTML += '<li><span id="active">'+days[d].getDate()+'</span></li>';
				}else{
						document.getElementById("days").innerHTML += '<li>'+days[d].getDate()+'</li>';
				}
			}
		}
	}
}


function updateUserCalendar(){
	//connect database to get current user's events
	var xmlHttp = new XMLHttpRequest();
	xmlHttp.open("POST", "viewEvent_ajax.php", true);
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xmlHttp.addEventListener("load",displayAllEventsAjax,false); //parse events JSON data to displayEventAjax
	xmlHttp.send(null);
}

function displayAllEventsAjax(event){
	var jsonData = JSON.parse(event.target.responseText);
	if (jsonData.success) {
		var weeks = currentMonth.getWeeks();
		var months = ["January","February","March","April","May","June","July","August","September","October","November","December"];
		var weekDays = ["Sun","Mon","Tue","Wed","Thu","Fri","Sat"];
		document.getElementById("showCurrentMonth").innerHTML = months[currentMonth.month];
		document.getElementById("showCurrentYear").innerHTML = currentMonth.year;
		document.getElementById("weekdays").innerHTML = "";
		document.getElementById("days").innerHTML = "";
		var is_emergency = 0;
		for(var i = 0; i < weekDays.length; i++) {
			document.getElementById("weekdays").innerHTML += '<li>'+weekDays[i]+'</li>';
		}

		for (var ws = 0; ws < weeks.length; ws++) {
			var days = weeks[ws].getDates();

			for (var ds = 0; ds < days.length; ds++) {
				var day = days[ds]; //date object
				var dd = ("0" + day.getDate()).slice(-2);
				var mm = ("0" + (day.getMonth() + 1)).slice(-2);
				var yy = day.getFullYear();
				var dayString = yy + "-" + mm + "-" + dd;
			
				//for the first week, there will be some days in the prev month
				// and the last week, there will be some days in the next month
				if(days[ds].getMonth()!==currentMonth.month){
					document.getElementById("days").innerHTML += '<li id="notThisMonth">'+days[ds].getDate()+'</li>';
				}else{
					if ((currentMonth.year == todayDates.getFullYear()) && (currentMonth.month == todayDates.getMonth()) && (days[ds].getDate() == todayDates.getDate())) {
						document.getElementById("days").innerHTML += '<li id="'+days[ds].getDate()+'"><span id="active">'+days[ds].getDate()+'</span></li>';
					}else{
						document.getElementById("days").innerHTML += '<li id="'+days[ds].getDate()+'">'+days[ds].getDate()+'</li>';
					}

					for (var j = 0; j < jsonData.message.length; j++) {
						if (String(jsonData.message[j].eventDate) == String(dayString)) {  //
							if (is_emergency > 0) {
								if ((currentMonth.year == todayDates.getFullYear()) && (currentMonth.month == todayDates.getMonth()) && (days[ds].getDate() == todayDates.getDate())) {
									var e1 = document.getElementById(days[ds].getDate());
									e1.parentNode.removeChild(e1);
									document.getElementById("days").innerHTML += '<li id="'+days[ds].getDate()+'"><span id="active">'+days[ds].getDate()+'</span><button class="display_eventsEmergency" type="button" value="'+String(dayString)+'" onclick="clickDisplayEvents(\''+String(dayString)+'\')"></button></li>';
								}else{
									var e2 = document.getElementById(days[ds].getDate());
									e2.parentNode.removeChild(e2);
									document.getElementById("days").innerHTML += '<li id="'+days[ds].getDate()+'">'+days[ds].getDate()+'<button class="display_eventsEmergency" type="button" value="'+String(dayString)+'" onclick="clickDisplayEvents(\''+String(dayString)+'\')"></button></li>';
								}
							}else{
								if ((currentMonth.year == todayDates.getFullYear()) && (currentMonth.month == todayDates.getMonth()) && (days[ds].getDate() == todayDates.getDate())) {
									var e3 = document.getElementById(days[ds].getDate());
									e3.parentNode.removeChild(e3);
									//console.log(jsonData.categoryValue);
									if (jsonData.message[j].category === 1) {
										is_emergency = 1;
										document.getElementById("days").innerHTML += '<li id="'+days[ds].getDate()+'"><span id="active">'+days[ds].getDate()+'</span><button class="display_eventsEmergency" type="button" value="'+String(dayString)+'" onclick="clickDisplayEvents(\''+String(dayString)+'\')"></button></li>';
									}else if (jsonData.message[j].category === 0) {
										document.getElementById("days").innerHTML += '<li id="'+days[ds].getDate()+'"><span id="active">'+days[ds].getDate()+'</span><button id="display_events" type="button" value="'+String(dayString)+'" onclick="clickDisplayEvents(\''+String(dayString)+'\')"></button></li>';
									}
								}else{
									var e4 = document.getElementById(days[ds].getDate());
									e4.parentNode.removeChild(e4);
									if (jsonData.message[j].category === 1) {
										is_emergency = 1;
										document.getElementById("days").innerHTML += '<li id="'+days[ds].getDate()+'">'+days[ds].getDate()+'<button class="display_eventsEmergency" type="button" value="'+String(dayString)+'" onclick="clickDisplayEvents(\''+String(dayString)+'\')"></button></li>';
									}else if (jsonData.message[j].category === 0) {
										document.getElementById("days").innerHTML += '<li id="'+days[ds].getDate()+'">'+days[ds].getDate()+'<button id="display_events" type="button" value="'+String(dayString)+'" onclick="clickDisplayEvents(\''+String(dayString)+'\')"></button></li>';
									}
								}
							}
						}
					}

				}
			}
		}
	}
}

function clickDisplayEvents(valueDate){
	document.getElementById("displayEvents").innerHTML = "";
	var eventDate = valueDate;
	var dataString = "eventDate=" + encodeURIComponent(eventDate);
	var xmlHttp = new XMLHttpRequest();
	xmlHttp.open("POST", "displayEvents_ajax.php", true);
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xmlHttp.addEventListener("load",displayEventsByAjax,false);
	xmlHttp.send(dataString);
}


function displayEventsByAjax(event) {
	var jsonData = JSON.parse(event.target.responseText);
	if (jsonData.success) {
		for (var i = 0; i < jsonData.message.length; i++) {
			// console.log(jsonData.message[i].eventId);
			document.getElementById("displayEvents").innerHTML += '<div></div>';
			document.getElementById("displayEvents").innerHTML += '('+(i+1)+')'+' Event Name: <label>'+jsonData.message[i].eventTitle+'</label><br><br>Event Content: <label>'+jsonData.message[i].eventContent+'</label><br><br>Event Date: <label>'+jsonData.message[i].eventDate+'</label><br><br>Event Time: <label>'+jsonData.message[i].eventTime+
			'</label><br><br>';
			if (jsonData.message[i].category === 1) {
				document.getElementById("displayEvents").innerHTML += 'Tag: <label>Emergency</label><br><br><input type="button" name="editBtn" id="edit_displayEvent" value="Edit" onclick="clickEditEvents(\''+jsonData.message[i].eventId+'\')"/>';
			}else if (jsonData.message[i].category === 0) {
				document.getElementById("displayEvents").innerHTML += 'Tag: <label>Normal</label><br><br><input type="button" name="editBtn" id="edit_displayEvent" value="Edit" onclick="clickEditEvents(\''+jsonData.message[i].eventId+'\')"/>';
			}
			//console.log(jsonData.message[i].is_group);
			if (jsonData.message[i].is_group === 1) {
				document.getElementById("displayEvents").innerHTML += '<input type="button" name="shareBtn" id="share_displayEvent" value="Add Members" onclick="clickShareEvents(\''+jsonData.message[i].eventId+'\')"/>';
			}
			$("#displayEventsForm").show();
		}
		document.getElementById("displayEvents").innerHTML += '<p></p><input type="button" name="closeBtn" id="close_displayEvent" value="Close" onclick="closeDisplayEvent()" />';	
	}else{
		alert(jsonData.message);
	}
}

/** edit events on the calendar */
function clickEditEvents(eventIdValue) {
	$("#displayEventsForm").hide();
	$("#editEventForm").show();
	document.getElementById("theEditEvent").innerHTML = "";
	var eventId = eventIdValue;

	var dataString = "eventId=" + encodeURIComponent(eventId);

	var xmlHttp = new XMLHttpRequest();
	xmlHttp.open("POST", "displayEditedEvents_ajax.php", true);
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xmlHttp.addEventListener("load",displayEditedEventsByAjax,false);
	xmlHttp.send(dataString);
}

function displayEditedEventsByAjax(event) {
	var jsonData = JSON.parse(event.target.responseText);
	if (jsonData.success) {
		document.getElementById("theEditEvent").innerHTML += '<label>Event Name: </label><br><input type="text" name="edit_eventName" id="edit_eventName" value="'+jsonData.message[0].eventTitle+'" /><br><br>';
		document.getElementById("theEditEvent").innerHTML += '<label>Event Content: </label><br><textarea name="edit_eventContent" id="edit_eventContent">'+jsonData.message[0].eventContent+'</textarea><br><br>';
		document.getElementById("theEditEvent").innerHTML += '<label>Date: </label><br><input type="Date" name="edit_eventDate" id="edit_eventDate" value="'+jsonData.message[0].eventDate+'" /><br><br>';
		document.getElementById("theEditEvent").innerHTML += '<label>Time: </label><br><input type="Time" name="edit_eventTime" id="edit_eventTime" value="'+jsonData.message[0].eventTime+'" /><br><br>';
		if (jsonData.message[0].category === 1) {
			document.getElementById("theEditEvent").innerHTML += '<label>Tag: </label><br><input type="radio" name="category" id="edit_CategoryEm" value="Emergency" checked/>Emergence<input type="radio" name="category" id="edit_CategoryNo" value="Normal"/>Normal<br><br>';
		}else if (jsonData.message[0].category === 0) {
			document.getElementById("theEditEvent").innerHTML += '<label>Tag: </label><br><input type="radio" name="category" id="edit_CategoryEm" value="Emergency"/>Emergence<input type="radio" name="category" id="edit_CategoryNo" value="Normal" checked/>Normal<br><br>';
		}
		document.getElementById("theEditEvent").innerHTML += '<input type="button" name="submitBtn" id="submit_EditEvents" value="Submit" onclick="submitClickEditEvents(\''+jsonData.message[0].eventId+'\')" />';
		document.getElementById("theEditEvent").innerHTML += '<input type="button" name="deleteBtn" id="delete_EditEvents" value="Delete" onclick="deleteClickEvents(\''+jsonData.message[0].eventId+'\')" />';
		document.getElementById("theEditEvent").innerHTML += '<input type="button" name="closeBtn" id="close_EditEvents" value="Close" onclick="closeDisplayEditedEvent()" />';
	}else{
		alert(jsonData.message);
	}
}

/** edit the event when you change data */
function submitClickEditEvents(eventIdValue) {
	var eventId = eventIdValue;
	var eventName = document.getElementById("edit_eventName").value;
	var eventContent = document.getElementById("edit_eventContent").value;
	var eventDate = document.getElementById("edit_eventDate").value;
	var eventTime = document.getElementById("edit_eventTime").value;
	var dataString = "";

	if (document.getElementById("edit_CategoryEm").checked) {
		dataString = "eventId=" + encodeURIComponent(eventId) + "&eventName=" + encodeURIComponent(eventName) + "&eventContent=" + encodeURIComponent(eventContent) + "&eventDate=" + encodeURIComponent(eventDate) + "&eventTime=" + encodeURIComponent(eventTime) + "&category=1" + "&token=" + encodeURIComponent(token);
	}else if (document.getElementById("edit_CategoryNo").checked) {
		dataString = "eventId=" + encodeURIComponent(eventId) + "&eventName=" + encodeURIComponent(eventName) + "&eventContent=" + encodeURIComponent(eventContent) + "&eventDate=" + encodeURIComponent(eventDate) + "&eventTime=" + encodeURIComponent(eventTime) + "&category=0" + "&token=" + encodeURIComponent(token);
	}
	var xmlHttp = new XMLHttpRequest();
	xmlHttp.open("POST", "editEvents_ajax.php", true);
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xmlHttp.addEventListener("load",addressEditEventsAjax,false);
	//console.log(dataString);
	xmlHttp.send(dataString);
}

function addressEditEventsAjax(event) {
	var jsonData = JSON.parse(event.target.responseText);
	if (jsonData.success) {
		alert(jsonData.message);
		$("#editEventForm").hide();
		updateUserCalendar();
	}else{
		alert(jsonData.message);
	}
}

/** delete the events */
function deleteClickEvents(eventIdValue) {
	//console.log(eventIdValue);
	var eventId = eventIdValue;

	var dataString = "eventId=" + encodeURIComponent(eventId) + "&token=" + encodeURIComponent(token);

	var xmlHttp = new XMLHttpRequest();
	xmlHttp.open("POST", "deleteEvents_ajax.php", true);
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xmlHttp.addEventListener("load",addressDeleteEventsAjax,false);
	xmlHttp.send(dataString);
}

function addressDeleteEventsAjax(event) {
	var jsonData = JSON.parse(event.target.responseText);
	if (jsonData.success) {
		alert("Deleted successfully.");
		$("#editEventForm").hide();
		updateUserCalendar();
	}else{
		alert(jsonData.message);
	}
}


