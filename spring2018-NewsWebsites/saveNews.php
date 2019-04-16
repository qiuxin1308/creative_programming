<?php
  session_start();

  include("checkLogin.php");
  $oldToken = $_SESSION['token'];
  $newToken = $_POST['token'];

  if ((isset($_POST['post'])) and (hash_equals($oldToken,$newToken))) {
   	  $title = $_POST['title'];
   	  $content = $_POST['content'];
   	  $newsLink = $_POST['newsLink'];
   	  $userName = $_SESSION['userName'];

   	  require 'database.php';

   	  $stmt = $mysqli->prepare("insert into news (news_title,news_content,news_link,user_name) values (?,?,?,?)");
   	  if (!$stmt) {
   	  	 #printf("Query Prep Failed: %s\n", $mysqli->error);
         header("Location: Fail.html");
   	  	 exit;
   	  }

   	  $stmt->bind_param('ssss',$title,$content,$newsLink,$userName);
   	  $stmt->execute();
   	  $stmt->close();

   	  header("Location: Success.html");
   }else{
    header("Location:Fail.html");
   } 
 ?>