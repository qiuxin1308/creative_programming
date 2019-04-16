<?php 
    header("Content-Type: application/json");
    ini_set("session.cookie_httponly", 1);
    session_start();
    require 'database.php';

    if (empty($_POST['eventName'])) {
      	echo json_encode(array(
            "success" => false,
      		"message" => "Event name cannot be empty."
        ));
      	exit;
    }elseif (empty($_POST['eventContent'])) {
      	echo json_encode(array(
      		"success" => false,
      		"message" => "Event content cannot be empty."
      	));
      	exit;
    }elseif (empty($_POST['eventDate'])) {
      	echo json_encode(array(
      		"success" => false,
      		"message" => "Date cannot be empty."
      	));
      	exit;
    }elseif (empty($_POST['eventTime'])) {
      	echo json_encode(array(
            "success" => false,
            "message" => "Time cannot be empty."
      	));
      	exit;
    }
    
    $eventName = trim($_POST['eventName']);
    $eventName = htmlspecialchars($eventName);
    $eventContent = trim($_POST['eventContent']);
    $eventContent = htmlspecialchars($eventContent);
    $eventDate = trim($_POST['eventDate']);
    $eventDate = htmlspecialchars($eventDate);
    $eventTime = trim($_POST['eventTime']);
    $eventTime = htmlspecialchars($eventTime);
    $userId = $_SESSION['userId'];
    $username = $_SESSION['username'];
    $is_group = trim($_POST['is_group']);
    $is_group = htmlspecialchars($is_group);
    $category = trim($_POST['category']);
    $category = htmlspecialchars($category);
    $new_token = trim($_POST['token']);
    $new_token = htmlspecialchars($new_token);
    $old_token = $_SESSION['token'];

    if (hash_equals($old_token,$new_token)) {
       $stmt = $mysqli->prepare("insert into event(userId,eventTitle,eventContent,eventDate,eventTime,is_group,category) values(?,?,?,?,?,?,?)");
        if (!$stmt) {
          echo json_encode(array(
            "success" => false,
            "message" => "Connecting to database failed."
          ));
          exit;
          }
        $stmt->bind_param('dssssdd',$userId,$eventName,$eventContent,$eventDate,$eventTime,$is_group,$category);
        $stmt->execute();
        $stmt->close();

        $stmt = $mysqli->prepare("select eventId from event where userId=? and eventTitle=? and eventContent=? and eventDate=? and eventTime=? and is_group=? and category=?");
        $stmt->bind_param('dssssdd',$userId,$eventName,$eventContent,$eventDate,$eventTime,$is_group,$category);
        $stmt->execute();
        $stmt->bind_result($temp_eventId);
        $stmt->fetch();
        $tempEventId = htmlspecialchars($temp_eventId);
        $stmt->close();

        $stmt = $mysqli->prepare("update event set sharedEventId=? where eventId=? and userId=?");
        if (!$stmt) {
          echo json_encode(array(
            "success" => false,
            "message" => "Connecting to database failed."
          ));
          exit;
        }
        $stmt->bind_param('ddd',$tempEventId,$tempEventId,$userId);
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
?>