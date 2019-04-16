<?php 
  session_start();

  include("checkLogin.php");
  $oldToken = $_SESSION['token'];
  $newToken = $_POST['token'];

  if ((isset($_POST['submitDeleteComment'])) and (hash_equals($oldToken,$newToken))) {
  	 $userName = $_SESSION['userName'];
  	 $deleteUserName = trim($_POST['user_name']);
  	 $deleteUserName = htmlspecialchars($deleteUserName);

  	 if ($userName == $deleteUserName) {
  	 	 $serverName = "localhost";
  	     $username = "wustl_inst";
  	     $password = "wustl_pass";
  	     $dbname = "newsSites";
  	     $news_id = $_POST['news_id'];
  	     $comment_id = $_POST['comment_id'];

  	     $conn = new mysqli($serverName,$username,$password,$dbname);
  	     $sql = "delete from comment where news_id='".$news_id."' and comment_id='".$comment_id."'";

  	     if ($conn->query($sql) == True) {
  	     	header("Location: Success.html");
  	     }else{
  	     	header("Location: Fail.html");
  	     }
  	     $conn->close();
  	 }else{
  	 	header("Location: Fail.html");
  	 }
  }else{
  	header("Location: Fail.html");
  }

 ?>