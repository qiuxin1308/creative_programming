<?php
  header("Content-Type: application/json");
  ini_set("session.cookie_httponly", 1);
  session_start();
  require 'database.php';

  $userId = $_SESSION['userId'];
  $eventId = trim($_POST['eventId']);
  $eventId = htmlspecialchars($eventId);
  $serverName = "localhost";
  $usernameDB = "wustl_inst";
  $passwordDB = "wustl_pass";
  $dbname = "calendar";
  $new_token = trim($_POST['token']);
  $new_token = htmlspecialchars($new_token);
  $old_token = $_SESSION['token'];

  if (hash_equals($old_token,$new_token)) {
      $stmt = $mysqli->prepare("select is_group from event where eventId=?");
      if (!$stmt) {
        echo json_encode(array(
          "success" => false,
          "message" => "Connecting to database failed."
      ));
      exit;
      }
      $stmt->bind_param('d',$eventId);
      $stmt->execute();
      $stmt->bind_result($is_group);
      $stmt->fetch();

      if ($is_group == 1) {
        $stmt->close();

        $stmt = $mysqli->prepare("select sharedEventId from event where eventId=?");
        if (!$stmt) {
          echo json_encode(array(
            "success" => false,
            "message" => "Connecting to database failed."
        ));
        exit;
        }
        $stmt->bind_param('d',$eventId);
        $stmt->execute();
        $stmt->bind_result($existedSharedId);
        $stmt->fetch();
        $temp_sharedId = htmlspecialchars($existedSharedId);
        $stmt->close();

        $conn = new mysqli($serverName,$usernameDB,$passwordDB,$dbname);
        $sqlEvent = "delete from event where sharedEventId='".$temp_sharedId."'";
        if ($conn->query($sqlEvent) == True) {
          echo json_encode(array(
            "success" => true,
            "message" => "Deleted Group Event Successfully."
          ));
          exit;
        }else{
          echo json_encode(array(
            "success" => false,
            "message" => "Connecting to database failed."
          ));
          exit;
        }
    }elseif ($is_group == 0) {
      $stmt->close();
      $conn = new mysqli($serverName,$usernameDB,$passwordDB,$dbname);
      $sqlEvent = "delete from event where userId='".$userId."' and eventId='".$eventId."'";

      if ($conn->query($sqlEvent) == True) {
        echo json_encode(array(
          "success" => true,
          "message" => "Deleted Individual Event Successfully."
        ));
        exit;
      }else{
        echo json_encode(array(
          "success" => false,
          "message" => "Connecting to database failed."
        ));
        exit;
      } 
    } 
  }else{
    echo json_encode(array(
        "success" => false,
        "message" => "Fail to add event with something wrong."
      ));
      exit;
    }

 ?>