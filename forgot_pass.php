<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<?php

include('includes/header.php'); 
?>

<body style="background-image: url(img/bg11.png
); background-repeat: no-repeat; background-size: cover;">
<div class="container" style="margin-top: 100px;">
<!-- Outer Row -->
<div class="row justify-content-center ">
  <div class="col-xl-6 col-lg-6 col-md-6 " align="center">      
    <div class="card o-hidden border-0 my-5 shadow">
      <div class="card-body p-0 ">
        <!-- Nested Row within Card Body -->
        <div class="row ">
          <div class="col-lg-12">
            <div class="p-5">
              <div class="text-center">
                <img src="img/farmerlogo.png" style="width: 30%">
                <h1 class="h4 text-gray-900 mb-4 mt-3" style="font-family: Rockwell">Forgot Password</h1>
                <?php
                  if(isset($_GET['error'])){
                        echo '<div class="alert alert-warning alert-dismissable fade show" role="alert">
                          Incorrect username or password, please try again.
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true"> &times; </span>
                          </button>
                        </div>';
                      }
                ?>
              </div>
                <form class="user"method="POST">
                    <div class="form-group">
                    <input type="email" name="email" class="form-control " placeholder="Email" required>
                    </div>
                    <button type="submit" name="forgot_pass" class="btn btn-secondary" style="background-color:green;border-color:green">Send Code</button>       
                </form>
                <a href="#" class="btn btn-outline-dark mt-2" onclick="goBack()">Go back</i></a>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

</div>

</div>

</body>
<?php include('includes/scripts.php');  ?>
</html>
<script>
function goBack() {
  window.history.back();
}
</script>
<script>
  if (navigator.onLine) {    
    } else { 
      window.location="login.php";
    } 
</script>
<?php 
    session_start();
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    require_once 'classes/Cregister.php';
    $objtForgot = new Cregistration();
    if(isset($_POST['forgot_pass'])){
        $email = strip_tags($_POST['email']);
        
        $stmt = $objtForgot->runQuery("SELECT * FROM register WHERE email = :email");
        $stmt->execute(array(":email"=> $email));
        $rowEmail = $stmt->fetch(PDO::FETCH_ASSOC);
        $count = $stmt->rowCount();

        $code = "QWERTYUasdfgh1234567890";
		    $code = str_shuffle($code);
        $code = substr($code, 0, 10);
        
        if($count>0){
           $subject = 'Password reset';
           $message = "This is your code to reset your password: <strong>$code</strong>";
           $objtForgot->update_code($code, $email); 
           
            require_once "PHPMailer/PHPMailer.php";
            require_once "PHPMailer/SMTP.php"; 
            require_once "PHPMailer/Exception.php";

            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->Host = "smtp.gmail.com";
            $mail->SMTPAuth = true;
            $mail->Username  = "sinulatan1stcooperative@gmail.com";
            $mail->Password = "sinulatan1st";
            $mail->Port = 465;
            $mail->SMTPSecure = "ssl";

            //Email Settings;
            $mail->isHtml(true);
            $mail->setFrom('sinulatan1stcooperative@gmail.com');
            $mail->addAddress($email);
            $mail->Subject =("Reset Password");
            $mail->Body = $message;

            if ($mail->send()) {
                  $_SESSION['code']="success";
                  header("Location: reset_code.php");
               } else {
                echo'<script>
                swal("Email not sent","","error");
                 </script>';
               }
        }else{
          echo'<script>swal ("Email not registered", "", "error");  </script>';
        }
    }

?>