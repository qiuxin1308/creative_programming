function forgotPwdAjax(event) {
	var a1 = document.getElementById("a1_check").value;
	var a2 = document.getElementById("a2_check").value;
	var new_pwd = document.getElementById("new_pwd").value;
	var cur_user = document.getElementById("cur_user").value;

	var dataString = "a1=" + encodeURIComponent(a1) + "&a2=" + encodeURIComponent(a2) + "&new_pwd=" + encodeURIComponent(new_pwd) + "&cur_user=" + encodeURIComponent(cur_user);

	var xmlHttp = new XMLHttpRequest();
	xmlHttp.open("POST", "forgotPwd_ajax.php", true);
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xmlHttp.addEventListener("load",function(event){
		var jsonData = JSON.parse(event.target.responseText);
		if (jsonData.success) {
			alert(jsonData.message);
			$("#checkValidForm").hide();
		}else{
			alert(jsonData.message);
		}
	}, false);
	xmlHttp.send(dataString);
}
document.getElementById("submit_CheckValid").addEventListener("click",forgotPwdAjax,false);
document.getElementById("close_CheckValid").addEventListener("click",closeCheckValid,false);