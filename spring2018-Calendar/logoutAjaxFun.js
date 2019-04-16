
function logoutAjax(event) {
	var xmlHttp = new XMLHttpRequest();
	xmlHttp.open("POST", "logout_ajax.php", true);
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xmlHttp.addEventListener("load", function(event){
		var jsonData = JSON.parse(event.target.responseText);
		if (jsonData.success) {
			alert("You've been logged out.");
			$("#openLoginBtn").show();
			$("#openRegisterBtn").show();
			$("#openLogoutBtn").hide();
			$("#openAddEventBtn").hide();
			$("#showEventBtn").hide();
			//$("#openShareBtn").hide();
			$("#openShareCalendarBtn").hide();
			document.getElementById("displayUsername").textContent = "";
			updateCalendar();
		}else{
			alert("You did something wrong.");
		}
	}, false);
	xmlHttp.send(null);
}

document.getElementById("openLogoutBtn").addEventListener("click", logoutAjax, false);