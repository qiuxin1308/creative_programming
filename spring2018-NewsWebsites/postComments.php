<!DOCTYPE html>
<html>
<head>
	<title>Post Comments</title>
	<link rel="stylesheet" type="text/css" href="theStyle.css">
</head>
<body>
	<div id="container">
		<div id="header">
			<h1>
				<?php
				  include("checkLogin.php");
				  echo $_SESSION['userName']." is posting comments..."; 
				 ?>
			</h1>
		</div>
		<div id="nav">
		</div>

		<div id="main">
			<h1>Post Comments</h1>
			<?php

			  if (isset($_POST['submitPostComment'])) {

			   	 $news_id = trim($_POST['news_id']);
			   	 $news_id = htmlspecialchars($news_id);
			   	 $userName = $_SESSION['userName'];
			   	 $token = $_POST['token'];

			   	 echo "<form name=\"input\" action=\"saveComments.php\" method=\"POST\">";
			  	 echo "<div>";
			  	 echo "<br><label>Content:</label><br>";
			  	 echo "<textarea name=\"content\" cols=\"77\" rows=\"20\"></textarea>";
			  	 echo "</div>";
			  	 echo "<input type=\"hidden\" name=\"news_id\" value=".$news_id.">";
			  	 echo "<input type='hidden' name=\"token\" value=".$token.">";
			  	 echo "<br><input type=\"submit\" name=\"postComment\" value=\"Post\">";
			  	 echo "<input type=\"button\" name=\"goBack\" value=\"Back\" onclick='history.go(-2)'>";
			  	 echo "</form>";

			   }else{
			   	 printf("Wrong message!");
			   	 exit;
			   } 
			 ?>
		</div>
	</div>
</body>
</html>