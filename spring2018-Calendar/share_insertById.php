<?php
  header("Content-Type: application/json");
  require 'database.php';
  ini_set("session.cookie_httponly", 1);
  session_start();

  $shareUserName = trim($_POST['shareUserName']);
  $shareUserName = htmlspecialchars($shareUserName); 
  $eventName = trim($_POST['eventName']);
  $eventName = htmlspecialchars($eventName);
  $eventContent = trim($_POST['eventContent']);
  $eventContent = htmlspecialchars($eventContent);
  $eventDate = trim($_POST['eventDate']);
  $eventDate = htmlspecialchars($eventDate);
  $eventTime = trim($_POST['eventTime']);
  $eventTime = htmlspecialchars($eventTime);
  $is_group = trim($_POST['is_group']);
  $is_group = htmlspecialchars($is_group);
  $sharedEventId = trim($_POST['sharedEventId']);
  $sharedEventId = htmlspecialchars($sharedEventId);
  $eventId = trim($_POST['eventId']);
  $eventId = htmlspecialchars($eventId);
  $jsonDataLength = trim($_POST['jsonDataLength']);
  $jsonDataLength = htmlspecialchars($jsonDataLength);
  $countNumber = trim($_POST['countNumber']);
  $countNumber = htmlspecialchars($countNumber);
  $new_token = trim($_POST['token']);
  $new_token = htmlspecialchars($new_token);
  $old_token = $_SESSION['token'];

  if (hash_equals($old_token,$new_token)) {
      $stmt = $mysqli->prepare("select userId from user where username=?");
      $stmt->bind_param('s',$shareUserName);
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

      $stmt = $mysqli->prepare("select sharedEventId from event where userId=?");
      $stmt->bind_param('d',$userId);
      $stmt->execute();
      $stmt->bind_result($temp_EventId);
      $result_array = array();
      while ($stmt->fetch()) {
        array_push($result_array, $temp_EventId);
      }

      if (in_array($eventId,$result_array, false)) {
        $stmt->close();
        echo json_encode(array(
            "success" => false,
            "message" => "The event is already existed.",
            "jsonDataLength" => htmlentities($jsonDataLength),
            "countNumber" => htmlentities($countNumber),
            "arrayResult" => $result_array
          ));
          exit;
      }else{
        $stmt->close();
        $stmt = $mysqli->prepare("insert into event(userId,eventTitle,eventContent,eventDate,eventTime,is_group,sharedEventId) values(?,?,?,?,?,?,?)");
        if (!$stmt) {
          echo json_encode(array(
            "success" => false,
            "message" => "Connecting to database failed."
          ));
          exit;
        }
        $stmt->bind_param('dssssdd',$userId,$eventName,$eventContent,$eventDate,$eventTime,$is_group,$sharedEventId);
        $stmt->execute();
        $stmt->close();

        echo json_encode(array(
          "success" => true,
          "jsonDataLength" => htmlentities($jsonDataLength),
          "countNumber" => htmlentities($countNumber),
          "arrayResult" => $result_array
        ));
        exit;
      }  
  }else{
    echo json_encode(array(
        "success" => false,
        "message" => "Fail to add event with something wrong."
    ));
    exit;
  }
 ?>