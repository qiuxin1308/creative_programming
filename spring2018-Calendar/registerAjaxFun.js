function registerAjax(event){
	var username = document.getElementById("register_user").value;
	var password = document.getElementById("register_pwd").value;
	var answer1 = document.getElementById("answer1").value;
	var answer2 = document.getElementById("answer2").value;

	//Make a URL-encoded string for passing POST data
	var dataString = "username=" + encodeURIComponent(username) + "&password=" + encodeURIComponent(password) + "&answer1=" + encodeURIComponent(answer1) + "&answer2=" + encodeURIComponent(answer2);

	// Initialize XMLHttpRequest instance
	var xmlHttp = new XMLHttpRequest(); 
	xmlHttp.open("POST", "register_ajax.php", true); 
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	xmlHttp.addEventListener("load", function(event){
		var jsonData = JSON.parse(event.target.responseText);  // parse the JSON into a JavaScript object
		if(jsonData.success){  // in PHP, this was the "success" key in the associative array; in JavaScript, it's the .success property of jsonData
			alert("Register Successfully");
			$("#registerForm").hide();
		}else{
			alert(jsonData.message);
			// $("#registerForm").hide();
		}
	}, false); // Bind the callback to the load event
	xmlHttp.send(dataString); // Send the data
}
document.getElementById("submit_Register").addEventListener("click", registerAjax, false);