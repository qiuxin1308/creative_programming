<?php 
  session_start();

  if (isset($_POST['submit'])) {
  	require 'database.php';
    $userName = trim($_POST['userName']);
    $userName = htmlspecialchars($userName);

    #Use a prepared statement
    $stmt = $mysqli->prepare("select user_pwd from user where user_name=?");

    #bind the parameter
    $stmt->bind_param('s',$userName);
    $stmt->execute();

    #bind the results
    $stmt->bind_result($pwd_hash);
    $stmt->fetch();

    if (empty($pwd_hash)) {
  	  printf("The password is empty!");
  	  exit;
    }

    $pwd_guess = trim($_POST['password']);
    $pwd_guess = htmlspecialchars($pwd_guess);
    $pwd_guess = substr($pwd_guess,0,60);
    #compare the submitted password to the actual password hash
    if (password_verify($pwd_guess,$pwd_hash)) {
  	   $_SESSION['userName'] = $userName;
  	   #generate a 32-byte random string
  	   $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
  	   header("Location: userPage.php");
  	   exit;
    }else{
  	   header("Location: failToLogin.html");
  	   exit;
    }
    $stmt->close();
  }

 ?>