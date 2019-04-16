<?php
  header("Content-Type: application/json");
  ini_set("session.cookie_httponly", 1);
  session_start();

  require 'database.php';
  $userId = $_SESSION['userId'];

  $stmt = $mysqli->prepare("select eventTitle,eventContent,eventDate,eventTime,category from event where userId=?");
  if (!$stmt) {
   	echo json_encode(array(
		"success" => false,
		"message" => "Connecting to database failed."
	));
	exit;
   }
   $stmt->bind_param('d',$userId);
   $stmt->execute();
   $result = $stmt->get_result();
   $result_array = array();
   while ($row = $result->fetch_assoc()) {
    	array_push($result_array, $row);
    }
    echo json_encode(array(
		"success" => true,
		"message" => $result_array
	));
	exit;
    $stmt->close();
 ?>