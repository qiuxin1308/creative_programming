<?php
	header("Content-Type: application/json");
	require 'database.php';

	if (empty($_POST['username'])) {
		echo json_encode(array(
	  		"success" => false,
	  		"message" => "Username cannot be empty."
	  	));
	  	exit;
	}elseif (empty($_POST['password'])) {
	  	echo json_encode(array(
	  		"success" => false,
	  		"message" => "Password cannot be empty."
	  	));
	  	exit;
	}else{
	  	$username = trim($_POST['username']);
	  	$username = htmlspecialchars($username);
	  	$password = trim($_POST['password']);
	  	$password = htmlspecialchars($password);

	  	$stmt = $mysqli->prepare("select userId,password from user where username=?");
	  	$stmt->bind_param('s',$username);
	  	$stmt->execute();
	  	$stmt->bind_result($userId,$pwd_hash);
	  	$stmt->fetch();

	  	if (empty($pwd_hash)) {
	  		echo json_encode(array(
	  			"success" => false,
	  			"message" => "Username does not exist."
	  		));
	  		exit;
	  	}
	  	$password = substr($password, 0,60);
	  	if (password_verify($password,$pwd_hash)) {
	  		ini_set("session.cookie_httponly", 1);
	  		session_start();
	  		$_SESSION['userId'] = $userId;
	  		$_SESSION['username'] = $username;
	  		$_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
	  		echo json_encode(array(
	  			"success" => true,
	  			"userId" => htmlentities($userId),
	  			"username" => htmlentities($username),
	  			"token" => htmlentities($_SESSION['token'])
	  		));
	  		exit;
	  	}else{
	  		echo json_encode(array(
	  			"success" => false,
	  			"message" => "Password is incorrect."
	  		));
	  		exit;
	  	}
	  	$stmt->close();
	} 
 ?>