<?php
  session_start();

  include("checkLogin.php");
  $oldToken = $_SESSION['token'];
  $newToken = $_POST['token'];

  if ((isset($_POST['editComment'])) and (hash_equals($oldToken,$newToken))) {
  	 
  	 $userName = $_SESSION['userName'];
  	 $news_id = $_POST['news_id'];
  	 $comment_id = $_POST['comment_id'];
  	 $comment_content = $_POST['content'];
     require 'database.php';
  	 #$serverName = "localhost";
  	 #$username = "wustl_inst";
  	 #$password = "wustl_pass";
  	 #$dbname = "newsSites";

  	 #$conn = new mysqli($serverName,$username,$password,$dbname);
  	 #$sql = "update comment set comment_content='".$comment_content."' where news_id='".$news_id."' and comment_id='".$comment_id."'";
     $stmt = $mysqli->prepare("update comment set comment_content=? where news_id=? and comment_id=?");
     if (!$stmt) {
       printf("Query Prep Failed: %s\n", $mysqli->error);
          #echo "Fail to create an account.";
          exit;
     }

     $stmt->bind_param('sdd',$comment_content,$news_id,$comment_id);
     $stmt->execute();
     $stmt->close();

     header("Location: Success.html");


  	 #if ($conn->query($sql) == True) {
  	 #	header("Location: Success.html");
  	 #}else{
  	 #	header("Location: Fail.html");
  	 #}

  	 #$conn->close();


  }else{
  	header("Location: Fail.html");
  }

 ?>