<!DOCTYPE html>
<html>
<head>
	<title>Post News</title>
	<link rel="stylesheet" type="text/css" href="theStyle.css">
</head>
<body>
	<div id="container">
		<div id="header">
			<h1>
				<?php
				  include("checkLogin.php");
				  echo $_SESSION['userName']." is posting news..."; 
				 ?>
			</h1>
		</div>
		<div id="nav">
			<ul>
				<li><a href="userPage.php">Show News</a></li>
				<li><a class="active" href="postNews.php">Post News</a></li>
				<li><a href="searchNewsWithUser.php">Search News</a></li>
			</ul>
		</div>

		<div id="main">
			<h1>Post News</h1>
			<?php
			    echo "<form name=\"input\" action=\"saveNews.php\" method=\"POST\">";
			    echo "<div>";
			  	echo "<label>Title:</label><br>";
			  	echo "<input name=\"title\" size=\"85\" type=\"text\" required>";
			  	echo "</div>";
			  	echo "<div>";
			  	echo "<br><label>Content:</label><br>";
			  	echo "<textarea name=\"content\" cols=\"77\" rows=\"20\" required></textarea>";
			  	echo "</div>";
			  	echo "<div>";
			  	echo "<br><label>Source link:</label><br>";
			  	echo "<input type=\"text\" size=\"85\" name=\"newsLink\" required>";
			  	echo "</div>";
			  	echo "<input type='hidden' name=\"token\" value=".$_SESSION['token'].">";
			  	echo "<br><input type=\"submit\" name=\"post\" value=\"Post\">";
			  	echo "<input type=\"button\" name=\"goBack\" value=\"Back\" onclick='history.go(-1)'>";
			  	echo "</form>"; 
			 ?>
		</div>
	</div>
</body>
</html>