
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rest Password</title>
</head>
<?php

include('includes/header.php'); 

require_once 'classes/Cregister.php';
$objtReset = new Cregistration();
$code = null;
if(isset($_GET['code'])){
    $code = $_GET['code'];
    $stmt = $objtReset->runQuery("SELECT * FROM register WHERE code =:code AND time_expired > NOW()");
    $stmt->execute(array(":code"=> $code));
    $count = $stmt->rowCount($stmt);
    if($count>0){
      $rowReset = $stmt->fetch(PDO::FETCH_ASSOC);
    }else{
      $rowReset = null;
    }
}else{
  $code = null;
  $rowReset = null;
}
?>
<script>
  if (navigator.onLine) {    
    } else { 
      window.location="login.php";
    } 
</script>
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
                <h1 class="h4 text-gray-900 mb-4 mt-3" style="font-family: Rockwell">Reset Password</h1>
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
                    <input type="password" name="password" class="form-control " placeholder="New Password" required minlength="8">
                    <input type="hidden" name="email" value="<?php echo $rowReset['email'] ?>">
                    </div>
                    <button type="submit" name="reset_pass" class="btn btn-secondary" style="background-color:green;border-color:green">Reset Password</button>        
                </form>
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

<?php 
    session_start();
    if(isset($_POST['reset_pass'])){
        $email = $_POST['email'];
        $new_pass = strip_tags($_POST['password']);
        $password_length = strlen($new_pass);
       
        if(!$email){
            echo'
              <script>
               swal("reset code expired", "", "error");
              </script>
            ';
        }else{
        if($password_length >= 8){
          if (preg_match('/[a-zA-Z]/', $new_pass) && preg_match('/\d/',$new_pass)&& preg_match('/[^a-zA-Z\d]/',$new_pass)&&preg_match("/[A-Z]/",$new_pass)){
            $hash = password_hash($new_pass,PASSWORD_BCRYPT);
              if($objtReset-> reset_password($hash, $email)){
                $objtReset-> reset_code($email);
                $_SESSION['reset']="success";
                header("Location:login.php");       
              }else{
                  die();
              }
            }else{
              echo'<script>swal ("Please provide aleast one letter with uppercase, one number and one special character", "New password not set", "error");  </script>';
          }
      }else{ 
        echo'<script>swal ("Your password is too short, Please provide 8 character lenght or more, to secure your password", "New password not set", "error");  </script>';
      }
        }
        
    }

?>