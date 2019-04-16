<!DOCTYPE html>
<html>
<head>
	<title>UserPage</title>
	<link rel="stylesheet" type="text/css" href="theStyle.css">
</head>
<body>
	<div id="container">
		<div id="header">
			<h1>Welcome to the News</h1>
		</div>
		<div id="nav">
			<ul>
				<li><a class="active" href="userPage.php">Show News</a></li>
				<li><a href="postNews.php">Post News</a></li>
				<li><a href="searchNewsWithUser.php">Search News</a></li>
			</ul>
		</div>
		<div id="main">
			<h1>News</h1>
			<ul class="news">
				<?php
				  include("checkLogin.php");
				  
				  $host = 'mysql:host=localhost;dbname=newsSites';
				  $database = new PDO($host,'wustl_inst','wustl_pass');
				  $stmtQuery = $database->query('select * from news');

				  while ($row=$stmtQuery->fetch()) {
				  	 echo "<li>\n";
				  	 echo "<h3>".$row['news_title']."</h3>";
				  	 echo "<p id=\"authorType\">Author: ".$row['user_name']."</p>";
				  	 echo "<p>".$row['news_content']."</p>";
				  	 echo "<a href=".$row['news_link']." target = \"_blank\">Source: ".$row['news_link']."</a><br>";
				  	 echo "<form name=\"input\" action=\"editNewsPage.php\" method=\"POST\">";
				  	 echo "<input type='hidden' name=\"token\" value=".$_SESSION['token'].">";
				  	 echo "<input type='hidden' name=\"user_name\" value=".$row['user_name'].">";
				  	 echo "<input type=\"hidden\" name=\"news_id\" value=".$row['news_id'].">";
				  	 echo "<br><input id=\"editType\" type=\"submit\" value=\"Edit News\" name=\"submitEdit\">";
				  	 echo "</form>";
				  	 echo "<form name=\"input\" action=\"deleteNews.php\" method=\"POST\">";
				  	 echo "<input type='hidden' name=\"token\" value=".$_SESSION['token'].">";
				  	 echo "<input type='hidden' name=\"user_name\" value=".$row['user_name'].">";
				  	 echo "<input type=\"hidden\" name=\"news_id\" value=".$row['news_id'].">";
				  	 echo "<p></p>";
				  	 echo "<input id=\"deleteType\" type=\"submit\" value=\"Delete News\" name=\"submitDelete\">";
				  	 echo "</form>";
				  	 

				  	 $commentSql = $database->query('select count(*) from comment where news_id='.$row['news_id'].'');
				  	 $c = $commentSql->fetch();

				  	 echo "<form name=\"input\" action=\"commentNewsPage.php\" method=\"POST\">";
				  	 echo "<input type=\"hidden\" name=\"news_id\" value=".$row['news_id'].">";
				  	 echo "<p></p>";
				  	 echo "<input id=\"commentType\" type=\"submit\" value=\"Comment(".$c['count(*)'].")\" name=\"submitComment\">";
				  	 echo "</form>";


				  	 echo "</li>\n";
				  }
				 ?>	
			</ul>
		</div>

		<div id="sidebar">
			<h3 id="sidebarTitle">
				<?php 
				  echo "UserName: ".$_SESSION['userName'];
				 ?>
			</h3>
			<form name="logoutPage" action="logoutPage.php" method="GET">
				<input id="logoutType" type="submit" name="logout" value="Logout">
				<p></p>
				<p></p>
			</form>
		</div>

	</div>
</body>
</html>