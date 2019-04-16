<?php
	header("Content-Type: application/json");
	require 'database.php';
	ini_set("session.cookie_httponly", 1);
	session_start();

	if (empty($_POST['username'])) {
		echo json_encode(array(
	  		"success" => false,
	  		"message" => "Username cannot be empty."
	  	));
	  	exit;
	}else{
	  	$username = trim($_POST['username']);
	  	$username = htmlspecialchars($username);
	  	$eventId = trim($_POST['eventId']);
	  	$eventId = htmlspecialchars($eventId);
	  	$userId = $_SESSION['userId'];
	  	$new_token = trim($_POST['token']);
  		$new_token = htmlspecialchars($new_token);
  		$old_token = $_SESSION['token'];

  		if (hash_equals($old_token,$new_token)) {
  			$stmt = $mysqli->prepare("select userId from user where username=?");
	  		$stmt->bind_param('s',$username);
	  		$stmt->execute();
	  		$stmt->bind_result($temp_userId);
	  		$stmt->fetch();

	  		if (empty($temp_userId)) {
	  			echo json_encode(array(
	  				"success" => false,
	  				"message" => "Username does not exist."
	  			));
	  			exit;
	  		}
	  		$userId = htmlspecialchars($temp_userId);
	  		$stmt->close();
	  		//event: userId eventTitile eventContent eventDate eventTime
	  		$stmt = $mysqli->prepare("select eventId,eventTitle,eventContent,eventDate,eventTime,is_group from event where eventId=?");
	  		if (!$stmt) {
   	    		echo json_encode(array(
            		"success" => false,
            		"message" => "Connecting to database failed."
	   			));
	   			exit;
    		}
    		$stmt->bind_param('d',$eventId);
    		$stmt->execute();
    		$stmt->bind_result($eventShareId,$eventTitle, $eventContent, $eventDate, $eventTime,$is_group);
    		$stmt->fetch();

    		$eventTitle = htmlspecialchars($eventTitle);
    		$eventContent = htmlspecialchars($eventContent);
    		$eventDate = htmlspecialchars($eventDate);
    		$eventTime = htmlspecialchars($eventTime);
    		$is_group = htmlspecialchars($is_group);
    		$eventShareId = htmlspecialchars($eventShareId);
    		$stmt->close();

    		$stmt = $mysqli->prepare("insert into event(userId,eventTitle,eventContent,eventDate,eventTime,is_group,sharedEventId) values(?,?,?,?,?,?,?)");
    		if (!$stmt) {
      			echo json_encode(array(
    	   			"success" => false,
    	   			"message" => "Connecting to database failed."
    	   		));
    	   		exit;
      		}
      		$stmt->bind_param('dssssdd',$userId,$eventTitle,$eventContent,$eventDate,$eventTime,$is_group,$eventShareId);
      		$stmt->execute();
      		$stmt->close();

      		echo json_encode(array(
      			"success" => true
      		));
      		exit;
  		}else{
  			echo json_encode(array(
        		"success" => false,
        		"message" => "Fail to add event with something wrong."
      		));
      		exit;
  		} 	
	} 
 ?>