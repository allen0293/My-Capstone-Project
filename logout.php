<?php

date_default_timezone_set('Asia/Manila');
 session_start();

  if(isset($_POST['logout_btn'])){
     session_destroy();
     unset($_SESSION['username']); 
     header('Location: login.php');
  }


  
?>