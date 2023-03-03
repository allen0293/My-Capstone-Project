
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rest Password</title>
</head>
<?php
include('includes/header.php'); 


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
                <h1 class="h4 text-gray-900 mb-4 mt-3" style="font-family: Rockwell">Reset Code</h1>
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
                    <input type="text" name="code" class="form-control " placeholder="Reset Code" required>
                    </div>
                    <button type="submit" name="verify" class="btn btn-secondary" style="background-color:green;border-color:green">Verify</button>        
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
<script>
  function goBack() {
  window.history.back();
}
</script>
</body>
<?php include('includes/scripts.php');  ?>
</html>


<?php 
    require_once 'classes/Cregister.php';
    $objtReset = new Cregistration();

      session_start();

      if(!empty($_SESSION['code'])){
        echo'
        <script>
        swal("Check your email for code to reset your password", "", "success");
        </script>
      ';
      unset($_SESSION['code']);
      }

    if(isset($_POST['verify'])){
        $code = $_POST['code'];
        $stmt = $objtReset->runQuery("SELECT * FROM register WHERE code =:code");
        $stmt->execute(array(":code"=> $code));
        $count = $stmt->rowCount($stmt);
        if($count>0){
            $stmt = $objtReset->runQuery("SELECT * FROM register WHERE time_expired > NOW()");
            $stmt->execute();
            $count = $stmt->rowCount($stmt);
            if($count>0){
                header("Location: reset_pass.php?code=$code");
            }else{
                echo'
                <script>
                swal("Reset code expired, resend code again", "", "error");
                </script>
            ';
            }
        }else{
        echo'
            <script>
            swal("Reset code incorrect", "", "error");
            </script>
        ';
        }
        /*
        if(!$email){
            echo'
              <script>
               swal("reset link is expired", "", "error");
              </script>
            ';
        }else{
          if($objtReset-> reset_password($hash, $email)){
            $objtReset-> reset_code($email);
            header("Location:login.php?reset");
          }else{
              die();
          }
        }
        */
    }

?>