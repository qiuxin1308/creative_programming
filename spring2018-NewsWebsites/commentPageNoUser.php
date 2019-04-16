<!DOCTYPE html>
<html>
<head>
	<title>Comment Page</title>
	<link rel="stylesheet" type="text/css" href="theStyle.css">
</head>
<body>
	<div id="container">
		<div id="header">
			<h1>Welcome to the News</h1>
		</div>

		<div id="nav">
			<form>
				<input id="postCommentType" type="button" name="goBack" onclick="history.go(-1)" value="< Back">
			</form>
		</div>

		<div id="main">
			<h1>Comments</h1>
			<ul class="news">
				
				<?php 

				  if (isset($_POST['submitComment'])) {
				  	  require 'database.php';

				  	  $news_id = trim($_POST['news_id']);
				  	  $news_id = htmlspecialchars($news_id);

				  	  $stmt = $mysqli->prepare("select * from comment where news_id=?");
				  	  if (!$stmt) {
				  	  	printf("Query Prep Failed: %s\n", $mysqli->error);
				  	  	exit;
				  	  }

				  	  $stmt->bind_param('i',$news_id);
				  	  $stmt->execute();
				  	  $stmt->bind_result($comment_id,$news_id,$user_name,$comment_content);

				  	  while ($stmt->fetch()) {
				  	  	 echo "<li>\n";
				  	     echo "<h4>UserName: ".htmlspecialchars($user_name)."</h4>";
				  	     echo "<p>".htmlspecialchars($comment_content)."</p>";
				  	     echo "</li>\n";
				  	  }
				  	  $stmt->close();
				  }
				 ?>

			</ul>
		</div>

		<div id="sidebar">
			<h3>Login</h3>
				<form name="input" action="checkLogin.php" method="POST">
				<div>
					<label>Username:</label><br>
					<input type="text" name="userName" required>
				</div>
				<div>
					<br><label>Password:</label><br>
					<input type="password" name="password" required>
				</div>
				<br><input type="submit" name="submit" value="Sign in">
			</form>
			<form name="createPage" action="createAccount.html">
				<input type="submit" name="create" value="Create">
			</form>
		</div>

	</div>
</body>
</html>