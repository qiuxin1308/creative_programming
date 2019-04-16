function loginAjax(event) {
	var username = document.getElementById("login_user").value;
	var password = document.getElementById("login_pwd").value;

	var dataString = "username=" + encodeURIComponent(username) + "&password=" + encodeURIComponent(password);

	//Initialize our XMLHttpRequest instance
	var xmlHttp = new XMLHttpRequest();
	xmlHttp.open("POST", "login_ajax.php", true);
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xmlHttp.addEventListener("load", function(event){
		var jsonData = JSON.parse(event.target.responseText);
		if (jsonData.success) {  //return success == true
			alert("You've been logged in.");
			$("#openLoginBtn").hide();
			$("#openRegisterBtn").hide();
			$("#loginForm").hide();
			$("#openShareCalendarBtn").show();
			$("#openLogoutBtn").show();
			$("#openAddEventBtn").show();
			document.getElementById("displayUsername").textContent = "Owned by " + jsonData.username;
			token = jsonData.token;
			updateUserCalendar();
			
			/**
			$(function(){
				$("button.displayEventsByBtn").click(function(event){
					event.preventDefault();
					var eventDate = $(this).val();
					console.log(eventDate);
					var dataString = "eventDate=" + encodeURIComponent(eventDate);

					var xmlHttp = new XMLHttpRequest();
					xmlHttp.open("POST", "displayEvents_ajax.php", true);
					xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
					xmlHttp.addEventListener("load", function(event){
						var jsonData = JSON.parse(event.target.responseText);
						if (jsonData.success) {
							for (var i = 0; i < jsonData.message.length; i++) {
								document.getElementById("displayEvents").innerHTML += '<br>Event Name: <label>'+jsonData.message[i].eventTitle+'</label><br>Event Content: <label>'+jsonData.message[i].eventContent+'</label><br>Event Date: <label>'+jsonData.message[i].eventDate+'</label><br>Event Time: <label>'+jsonData.message[i].eventTime+'</label>';
							}	
						}else{
							alert(jsonData.message);
						}
					}, false);
					xmlHttp.send(dataString);
				});
			});
			**/
		}else{
			alert(jsonData.message);
			// $("#loginForm").hide();
		}
	}, false);
	xmlHttp.send(dataString);
}
document.getElementById("submit_Login").addEventListener("click",loginAjax,false);
document.getElementById("submit_Forgot").addEventListener("click",openCheckValid,false);


