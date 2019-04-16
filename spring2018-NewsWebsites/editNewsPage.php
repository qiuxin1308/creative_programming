<!DOCTYPE html>
<html>
<head>
	<title>Edit News</title>
	<link rel="stylesheet" type="text/css" href="theStyle.css">
</head>
<body>
	<div id="container">
		<div id="header">
			<h1>
				<?php
				  include("checkLogin.php");
				  echo $_SESSION['userName']." is editing news...";
				 ?>
			</h1>
		</div>
		<div id="nav"></div>
		<div id="main">
			<h1>Edit News</h1>
			<?php 
			  
			  if (isset($_POST['submitEdit'])) {
			  	require 'database.php';

			  	$userName = $_SESSION['userName'];
			  	$token = $_POST['token'];
			  	$editUserName = trim($_POST['user_name']);
			  	$editUserName = htmlspecialchars($editUserName);
			  	$news_id = trim($_POST['news_id']);
			  	$news_id = htmlspecialchars($news_id);

			  	if ($userName == $editUserName) {
			  		$stmt = $mysqli->prepare("select * from news where news_id = ?");
			  		if (!$stmt) {
			  			printf("Query Prep Failed: %s\n", $mysqli->error);
			  			exit;
			  		}

			  		$stmt->bind_param('s',$news_id);
			  		$stmt->execute();
			  		$stmt->bind_result($news_id,$news_title,$news_content,$news_link,$user_name);

			  		while ($stmt->fetch()) {
			  			echo "<form name=\"input\" action=\"editNews.php\" method=\"POST\">";
			  			echo "<div>";
			  			echo "<label>Title:</label><br>";
			  			echo "<textarea name=\"title\" cols=\"77\" rows=\"2\">".htmlspecialchars($news_title)."</textarea>";
			  			echo "</div>";
			  			echo "<div>";
			  			echo "<br><label>Content:</label><br>";
			  			echo "<textarea name=\"content\" cols=\"77\" rows=\"20\">".htmlspecialchars($news_content)."</textarea>";
			  			echo "</div>";
			  			echo "<div>";
			  			echo "<br><label>Source link:</label><br>";
			  			echo "<input type=\"text\" size=\"85\" name=\"newsLink\" value=".htmlspecialchars($news_link).">";
			  			echo "</div>";
			  			echo "<input type='hidden' name=\"token\" value=".$token.">";
			  			echo "<input type=\"hidden\" name=\"news_id\" value=".htmlspecialchars($news_id).">";
			  			echo "<br><input type=\"submit\" name=\"edit\" value=\"Save\">";
			  			echo "<input type=\"button\" name=\"goBack\" value=\"Back\" onclick='history.go(-1)'>";
			  			echo "</form>";
			  		}
			  		$stmt->close();
			  	}else{
			  		header("Location: Fail.html");
			  	}
			  }

			 ?>
		</div>
	</div>
</body>
</html>