<?php
	header("Content-Type: application/json");
	require 'database.php';

	if (empty($_POST['cur_user'])) {
  		echo json_encode(array(
  			"success" => false,
  			"message" => "Username cannot be empty."
  		));
  	exit;
  	}elseif (empty($_POST['new_pwd'])) {
	  	echo json_encode(array(
	  		"success" => false,
	  		"message" => "Password cannot be empty."
	  	));
	  	exit;
	}elseif (empty($_POST['a1'])) {
	  	echo json_encode(array(
	  		"success" => false,
	  		"message" => "The first answer cannot be empty."
	  	));
	  	exit;
	}elseif (empty($_POST['a2'])) {
	  	echo json_encode(array(
	  		"success" => false,
	  		"message" => "The second answer cannot be empty."
	  	));
	  	exit;
	}

	$username = htmlspecialchars(trim($_POST['cur_user']));
	$password = htmlspecialchars(trim($_POST['new_pwd']));
	$ans1 = htmlspecialchars(trim($_POST['a1']));
	$ans2 = htmlspecialchars(trim($_POST['a2']));


  	    $stmt = $mysqli->prepare("select answer1,answer2 from user where username=?");
		if(!$stmt){
			echo json_encode(array(
				"success" => false,
				"message" => "Cannot connect to database."
			));
			exit;
		}

		$stmt->bind_param('s', $username);
		$stmt->execute();
		$stmt->bind_result($answer1,$answer2);
		$stmt->fetch();

		if (($answer1 == $ans1) and ($answer2 == $ans2) ) {
			$stmt->close();
			$pwd_hash = password_hash($password,PASSWORD_DEFAULT);
			$stmt = $mysqli->prepare("update user set password=? where username=?");
			if (!$stmt) {
				echo json_encode(array(
        			"success" => false,
        			"message" => "Connecting to database failed."
      			));
      			exit;
			}
			$stmt->bind_param('ss',$pwd_hash,$username);
			$stmt->execute();
    		$stmt->close();
    		echo json_encode(array(
      			"success" => true,
      			"message" => "Changed Password Successfully."
    		));
    		exit;
		}else{
			echo json_encode(array(
				"success" => false,
				"message" => "Cannot change password."
			));
			exit;
		}	
  	
?>