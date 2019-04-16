<!DOCTYPE html>
<html>
<head>
	<title>Login Page</title>
	<link rel="stylesheet" type="text/css" href="theStyle.css">
</head>
<body>
	<div id="container">
		<div id="header">
			<h1>Welcome to the News</h1>
		</div>

		<div id="nav">
			<?php
				echo "<input id=\"postCommentType\" type=\"button\" value=\"Search News\" name=\"submitSearch\" onclick=\"window.location.href='searchNewsPage.php'\">";
			 ?>
		</div>

		<div id="main">
			<h1>News</h1>
			<ul class="news">
				<?php
				  
				  $host = 'mysql:host=localhost;dbname=newsSites';
				  $database = new PDO($host,'wustl_inst','wustl_pass');
				  $stmtQuery = $database->query('select * from news');

				  while ($row=$stmtQuery->fetch()) {
				  	 echo "<li>\n";
				  	 echo "<h3>".$row['news_title']."</h3>";
				  	 echo "<p id=\"authorType\">Author: ".$row['user_name']."</p>";
				  	 echo "<p>".$row['news_content']."</p>";
				  	 echo "<a href=".$row['news_link']." target = \"_blank\">Source: ".$row['news_link']."</a><br>";
				  	 

				  	 $commentSql = $database->query('select count(*) from comment where news_id='.$row['news_id'].'');
				  	 $c = $commentSql->fetch();

				  	 echo "<form name=\"input\" action=\"commentPageNoUser.php\" method=\"POST\">";
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