<?php
  header("Content-Type: application/json");
  ini_set("session.cookie_httponly", 1);
  session_start();

  require 'database.php';
  $userId = $_SESSION['userId'];
  $eventId = trim($_POST['eventId']);
  $eventId = htmlspecialchars($eventId);
  $eventName = trim($_POST['eventName']);
  $eventName = htmlspecialchars($eventName);
  $eventContent = trim($_POST['eventContent']);
  $eventContent = htmlspecialchars($eventContent);
  $eventDate = trim($_POST['eventDate']);
  $eventDate = htmlspecialchars($eventDate);
  $eventTime = trim($_POST['eventTime']);
  $eventTime = htmlspecialchars($eventTime);
  $category = trim($_POST['category']);
  $category = htmlspecialchars($category);
  $new_token = trim($_POST['token']);
  $new_token = htmlspecialchars($new_token);
  $old_token = $_SESSION['token'];

  if (hash_equals($old_token,$new_token)) {
      $stmt = $mysqli->prepare("select is_group,sharedEventId from event where eventId=?");
      if (!$stmt) {
        echo json_encode(array(
          "success" => false,
          "message" => "Connecting to database failed."
      ));
      exit;
      }
      $stmt->bind_param('d',$eventId);
      $stmt->execute();
      $stmt->bind_result($is_group,$tempSharedEventId);
      $stmt->fetch();
      $temp_SharedEventId = htmlspecialchars($tempSharedEventId);

      if ($is_group == 1) {
        $stmt->close();
        $stmt = $mysqli->prepare("update event set eventTitle=?,eventContent=?,eventDate=?,eventTime=?,category=? where sharedEventId=?");
        if (!$stmt) {
          echo json_encode(array(
            "success" => false,
            "message" => "Connecting to database failed."
          ));
          exit;
        }
        $stmt->bind_param('ssssdd',$eventName,$eventContent,$eventDate,$eventTime,$category,$temp_SharedEventId);
        $stmt->execute();
        $stmt->close();
        echo json_encode(array(
          "success" => true,
          "message" => "Modified Group Event Successfully."
        ));
        exit;

      }elseif ($is_group == 0) {
        $stmt->close();
        $stmt = $mysqli->prepare("update event set eventTitle=?,eventContent=?,eventDate=?,eventTime=?,category=? where userId=? and eventId=?");
        if (!$stmt) {
          echo json_encode(array(
            "success" => false,
            "message" => "Connecting to database failed."
          ));
          exit;
        }
        $stmt->bind_param('ssssddd',$eventName,$eventContent,$eventDate,$eventTime,$category,$userId,$eventId);
        $stmt->execute();
        $stmt->close(); 

        echo json_encode(array(
          "success" => true,
          "message" => "Modified Individual Event Successfully."
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