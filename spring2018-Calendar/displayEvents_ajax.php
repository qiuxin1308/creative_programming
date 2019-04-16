<?php
    header("Content-Type: application/json");
    ini_set("session.cookie_httponly", 1);
    session_start();

    require 'database.php';
    $userId = $_SESSION['userId'];
    $eventDate = trim($_POST['eventDate']);
    $eventDate = htmlspecialchars($eventDate);

    $stmt = $mysqli->prepare("select eventId, eventTitle,eventContent,eventDate,eventTime,is_group,sharedEventId,category from event where userId=? and eventDate=?");
    if (!$stmt) {
   	    echo json_encode(array(
            "success" => false,
            "message" => "Connecting to database failed."
        ));
        exit;
    }
    $stmt->bind_param('ds',$userId,$eventDate);
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