<!DOCTYPE html>
<html>
<head>
	<title>Edit Comments</title>
	<link rel="stylesheet" type="text/css" href="theStyle.css">
</head>
<body>
	<div id="container">
		<div id="header">
			<h1>
				<?php
				  include("checkLogin.php");
				  echo $_SESSION['userName']." is editing comments..."; 
				 ?>
			</h1>
		</div>
		<div id="nav">
		</div>

		<div id="main">
			<h1>Edit Comments</h1>
			<?php
			  if (isset($_POST['submitEditComment'])) {
			  	require 'database.php';

			  	$userName = $_SESSION['userName'];
			  	$editUserName = trim($_POST['user_name']);
			  	$editUserName = htmlspecialchars($editUserName);
			  	$news_id = trim($_POST['news_id']);
			  	$news_id = htmlspecialchars($news_id);
			  	$comment_id = trim($_POST['comment_id']);
			  	$comment_id = htmlspecialchars($comment_id);
			  	$token = $_POST['token'];

			  	if ($userName == $editUserName) {
			  		$stmt = $mysqli->prepare("select * from comment where news_id = ? and comment_id = ?");
			  		if (!$stmt) {
			  			printf("Query Prep Failed: %s\n",$mysqli->error);
			  			exit;
			  		}

			  		$stmt->bind_param('ii',$news_id,$comment_id);
			  		$stmt->execute();
			  		$stmt->bind_result($cid,$nid,$username,$content);

			  		while ($stmt->fetch()) {
			  			echo "<form name=\"input\" action=\"editComment.php\" method=\"POST\">";
			  			echo "<div>";
			  			echo "<br><label>Content:</label><br>";
			  			echo "<textarea name=\"content\" cols=\"77\" rows=\"20\">".htmlspecialchars($content)."</textarea>";
			  			echo "</div>";
			  			echo "<input type='hidden' name=\"token\" value=".$token.">";
			  			echo "<input type='hidden' name=\"comment_id\" value=".htmlspecialchars($cid).">";
			  			echo "<input type=\"hidden\" name=\"news_id\" value=".htmlspecialchars($nid).">";
			  			echo "<br><input type=\"submit\" name=\"editComment\" value=\"Save\">";
			  			echo "<input type=\"button\" name=\"goBack\" value=\"Back\" onclick='history.go(-2)'>";
			  			echo "</form>";
			  		}

			  		$stmt->close();

			  	}else{
			  		header("Location: Fail.html");
			  		exit;
			  	}


			   }else{
			   	 printf("Wrong message!");
			   	 exit;
			   } 
			 ?>
		</div>
	</div>
</body>
</html>