<?php 
  session_start();

  include("checkLogin.php");
  $oldToken = $_SESSION['token'];
  $newToken = $_POST['token'];

  if ((isset($_POST['postComment'])) and (hash_equals($oldToken,$newToken))) {
  	$userName = $_SESSION['userName'];
  	$news_id = $_POST['news_id'];
  	$comment_content = $_POST['content'];


  	require 'database.php';

  	$stmt = $mysqli->prepare("insert into comment (news_id,user_name,comment_content) values (?,?,?)");

  	if (!$stmt) {
  		header("Location: Fail.html");
  		exit;
  	}

  	$stmt->bind_param('iss',$news_id,$userName,$comment_content);
  	$stmt->execute();
  	$stmt->close();

  	header("Location: Success.html");

  }else{
  	header("Location: Fail.html");
  }

 ?>