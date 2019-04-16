<?php
  //Content of database.php

  $mysqli = new mysqli('localhost','wustl_inst','wustl_pass','newsSites');

  if ($mysqli->connect_errno) {
  	printf("Connection Failed: %s\n", $mysqli->connect_errno);
  	exit;
  }

 ?>