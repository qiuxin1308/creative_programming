<?php 
  session_start();

  include("checkLogin.php");
  $oldToken = $_SESSION['token'];
  $newToken = $_POST['token'];

  if ((isset($_POST['edit'])) and hash_equals($oldToken,$newToken)) {
  	 $title = $_POST['title'];
  	 $content = $_POST['content'];
  	 $newsLink = $_POST['newsLink'];
  	 $news_id = $_POST['news_id'];
  	 $userName = $_SESSION['userName'];
  	 $serverName = "localhost";
  	 $username = "wustl_inst";
  	 $password = "wustl_pass";
  	 $dbname = "newsSites";

  	 $conn = new mysqli($serverName,$username,$password,$dbname);
  	 $sql = "update news set news_title='".$title."',news_content='".$content."',news_link='".$newsLink."',user_name='".$userName."' where news_id='".$news_id."'";

  	 if ($conn->query($sql) == True) {
  	 	header("Location: Success.html");
  	 }else{
  	 	header("Location: Fail.html");
  	 }

  	 $conn->close();
  }else{
  	header("Location: Fail.html");
  }


 ?>