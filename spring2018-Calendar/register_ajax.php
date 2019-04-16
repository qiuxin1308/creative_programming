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
	}elseif (empty($_POST['answer1'])) {
	  	echo json_encode(array(
	  		"success" => false,
	  		"message" => "The first answer cannot be empty."
	  	));
	  	exit;
	}elseif (empty($_POST['answer2'])) {
	  	echo json_encode(array(
	  		"success" => false,
	  		"message" => "The second answer cannot be empty."
	  	));
	  	exit;
	}

	$username = htmlspecialchars(trim($_POST['username']));
	$password = htmlspecialchars(trim($_POST['password']));
	$ans1 = htmlspecialchars(trim($_POST['answer1']));
	$ans2 = htmlspecialchars(trim($_POST['answer2']));

	$stmt = $mysqli->prepare("select * from user where username=?");
	if(!$stmt){
		echo json_encode(array(
			"success" => false,
			"message" => "Cannot connect to database."
		));
		exit;
	}

	$stmt->bind_param('s', $username);
	$stmt->execute();
	$stmt->store_result();

	//valid username, add it to database
	if($stmt->num_rows() == 0){
		$stmt->close();
  		$pwd_hash = password_hash($password,PASSWORD_DEFAULT);
  		$stmt = $mysqli->prepare("insert into user(username, password, answer1, answer2) value(?,?,?,?)");
  		if(!$stmt){
  			echo json_encode(array(
  				"success" => false,
  				"message" => "Cannot connect to database."
  			));
  			exit;
  		}

  		$stmt->bind_param('ssss', $username, $pwd_hash, $ans1, $ans2);
  		$stmt->execute();
  		$stmt->close();

  		echo json_encode(array(
  			"success" => true
  		));
  		exit;
	}else{
		echo json_encode(array(
			"success" => false,
			"message" => "Username is existed"
		));
		exit;
	}	
?>