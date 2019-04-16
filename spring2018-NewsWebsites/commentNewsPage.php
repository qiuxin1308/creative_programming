<!DOCTYPE html>
<html>
<head>
	<title>Comment Page</title>
	<link rel="stylesheet" type="text/css" href="theStyle.css">
</head>
<body>
	<div id="container">
		<div id="header">
			<h1>Comments</h1>
		</div>

		<div id="nav">
			<?php
			    include("checkLogin.php");
			    $news_id = trim($_POST['news_id']);
				$news_id = htmlspecialchars($news_id);

				echo "<form name=\"input\" action=\"postComments.php\" method=\"POST\">";
				echo "<input type='hidden' name=\"token\" value=".$_SESSION['token'].">";
				echo "<input type=\"hidden\" name=\"news_id\" value=".$news_id.">";
				echo "<p></p>";
				echo "<input id=\"postCommentType\" type=\"submit\" value=\"Post Comments\" name=\"submitPostComment\">";
				echo "</form>"; 
			?>
		</div>

		<div id="main">
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
				  	     echo "<form name=\"input\" action=\"editCommentPage.php\" method=\"POST\">";
				  	     echo "<input type='hidden' name=\"user_name\" value=".htmlspecialchars($user_name).">";
				  	     echo "<input type='hidden' name=\"token\" value=".$_SESSION['token'].">";
				  	     echo "<input type='hidden' name=\"comment_id\" value=".htmlspecialchars($comment_id).">";
				  	     echo "<input type=\"hidden\" name=\"news_id\" value=".htmlspecialchars($news_id).">";
				  	     echo "<br><input id=\"editType\" type=\"submit\" value=\"Edit Comments\" name=\"submitEditComment\">";
				  	     echo "</form>";
				  	     echo "<form name=\"input\" action=\"deleteComment.php\" method=\"POST\">";
				  	     echo "<input type='hidden' name=\"user_name\" value=".htmlspecialchars($user_name).">";
				  	     echo "<input type='hidden' name=\"token\" value=".$_SESSION['token'].">";
				  	     echo "<input type='hidden' name=\"comment_id\" value=".htmlspecialchars($comment_id).">";
				  	     echo "<input type=\"hidden\" name=\"news_id\" value=".htmlspecialchars($news_id).">";
				  	     echo "<p></p>";
				  	     echo "<input id=\"deleteCommentType\" type=\"submit\" value=\"Delete Comments\" name=\"submitDeleteComment\">";
				  	     echo "</form>";
				  	     echo "</li>\n";
				  	  }

				  	  $stmt->close();
				  }
				 ?>

			</ul>
		</div>

		<div id="sidebar">
			<h3 id="sidebarTitle">
				<?php 
				  include("checkLogin.php");
				  echo "UserName: ".$_SESSION['userName'];
				 ?>
			</h3>
			<form name="logoutPage" action="logoutPage.php" method="GET">
				<input id="logoutType" type="submit" name="logout" value="Logout">
				<p></p>
				<p></p>
			</form>
			<input id="backType" type="button" name="goBack" onclick="history.go(-1)" value="Back">
			<p></p>
		    <p></p>
		</div>

	</div>
</body>
</html>