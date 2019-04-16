<!DOCTYPE html>
<html>
<head>
	<title>Post News</title>
	<link rel="stylesheet" type="text/css" href="theStyle.css">
</head>
<body>
	<div id="container">
		<div id="header">
			<h1>Welcome to the News</h1>
		</div>
		<div id="nav">
		</div>

		<div id="main">
			<h1>Search News</h1>
			    <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST">
			      <div>
			  	     <input name="searchContent" size="70" type="text" required>
			  	  </div>
			  	  <br><input id="commentType" type="submit" name="search" value="Search">
			  	  &nbsp;&nbsp;&nbsp;<input id="searchBackType" type="button" name="goBack" value="Back" onclick="window.location.href='LoginPage.php'">
			  	</form>
			 <ul class="news">
			 	<?php 

			 	  	 if (isset($_POST['search'])) {
			 	  	     require 'database.php';
			 	  	     $searchContent = $_POST['searchContent'];

			 	  	     $stmt = $mysqli->prepare("select news_id,news_title,news_content,news_link,user_name from news where news_content like '%$searchContent%'");
			 	  	     if (!$stmt) {
			 	  	 	    printf("Query Prep Failed: %s\n", $mysqli->error);
			 	  	 	    exit;
			 	  	     }

			 	  	     $stmt->execute();
			 	  	     $stmt->store_result();
			 	  	     $stmt->bind_result($news_id,$news_title,$news_content,$news_link,$user_name);

			 	  	     if ($stmt->num_rows() == 0) {
			 	  	     	echo "<h3>No Result.</h3>";
			 	  	     }else{
			 	  	     	while ($stmt->fetch()) {
			 	  	 	        echo "<li>\n";
				  	            echo "<h3>".$news_title."</h3>";
				  	            echo "<p id=\"authorType\">Author: ".$user_name."</p>";
				  	            echo "<p>".$news_content."</p>";
				  	            echo "<a href=".$news_link." target = \"_blank\">Source: ".$news_link."</a><br>";
			 	  	        } 

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