<?php
  session_start();

  if (isset($_POST['submit'])) {
  	  $userName = trim($_POST['username']);
  	  $userName = htmlspecialchars($userName);
      $password = trim($_POST['password']);
      $password = htmlspecialchars($password);
      require 'database.php';

      $stmt = $mysqli->prepare("select * from user where user_name=?");
      if (!$stmt) {
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
      }

      $stmt->bind_param('s',$userName);
      $stmt->execute();
      $stmt->store_result();

      if ($stmt->num_rows() == 0) {
        $stmt->close();

        $pwd_hash = password_hash($password,PASSWORD_DEFAULT);

        $stmt = $mysqli->prepare("insert into user (user_name,user_pwd) values(?,?)");
        if (!$stmt) {
          printf("Query Prep Failed: %s\n", $mysqli->error);
          #echo "Fail to create an account.";
          exit;
        }

        $stmt->bind_param('ss',$userName,$pwd_hash);
        $stmt->execute();
        $stmt->close();

        header("Location: LoginPage.php");

      }else{
        header("Location: FailToCreateAccount.html");
      }

    }

 ?>