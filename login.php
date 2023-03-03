<?php
error_reporting(0);
include('includes/header.php'); 
include('includes/scripts.php'); 
include('classes/Cregister.php');
$objtUser = new Cregistration();
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
                <h1 class="h4 text-gray-900 mb-4" style="font-family: Rockwell">Sinulatan 1st Agriculture Cooperative</h1>
                <?php
                  session_start();
                  if(!empty($_SESSION['error'])){
                          echo'
                          <script>
                            swal("Incorrect username or password, please try again", "", "error");  
                          </script>
                        ';   
                        unset($_SESSION['error']);
                      }else if(!empty($_SESSION['reset'])){
                        echo'
                          <script>
                            swal("Password reset", "", "success");  
                          </script>
                        ';   
                        unset($_SESSION['reset']);
                      }
                      else if(!empty($_SESSION['weak_password'])){
                        echo'<script>swal ("Please provide aleast one letter with uppercase, one number and one special character", "password not set", "error");  </script>';  
                        unset($_SESSION['weak_password']);
                      } 
                      else if(!empty($_SESSION['short_password'])){
                        echo'<script>swal ("Your password is too short, Please provide 8 character lenght or more, to secure your password", "password not set", "error");  </script>';
                        unset($_SESSION['short_password']);
                      }else if(!empty($_SESSION['confirmation_pass'])){
                        echo'<script>swal ("Confirm Password Did not match, Please try again", "", "error");  </script>';
                        unset($_SESSION['confirmation_pass']);
                      } 
                      

                ?>
              </div>
                <form class="user" action="code.php" method="POST">

                    <div class="form-group">
                    <input type="text" name="username" class="form-control " placeholder="Username" required>
                    </div>
                    <div class="form-group">
                    <input type="password" id="password " name="password" class="form-control " placeholder="Password" required>
                    </div>
                    <div class="custom-control custom-checkbox float-left mb-2" >
                      <input type="checkbox" class="custom-control-input" id="customCheck1" onclick="showPass()"  >
                     <label class="custom-control-label" for="customCheck1">Show Password</label>
                    </div>
                    <button type="submit" name="login_btn" class="btn btn-primary btn-user btn-block" style="background-color:green;border-color:green">Login</button>
                    <a href="#" name="forgot_pass" data-toggle="modal" data-target="#modalForgot" class=" mt-2"> Forgot password? </a>    
                    <div class="mt-2"><a href="#" data-toggle="modal" data-target="#exampleModalCenter"><span>Need Help</span><i class="far fa-question-circle"></i></a></div>
                      <p id="demo"></p>
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
<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Need Help?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <strong>Contact us</strong><br>
        Email: student.ccs@gmail.com
        <br>
        Phone: 09273777519
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- Forgot Password Modal -->
<div class="modal fade" id="modalForgot" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Forgot Password Option</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <a href="#" onclick="isOnline()">Option1:</a>  Reset password by sending a code to your email.
        <br>
        <a href="#" data-toggle="modal" data-target="#modalQuestionaire" data-dismiss="modal" >Option2:</a> Reset password using Security Questionaire.
        <a href="forgot_pass.php"></a>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Security Questionaire -->
<div class="modal fade" id="modalQuestionaire" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Reset Password Using Security Questionaire</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="login.php" method="post">
        <div class="form-group">
        <label>Username</label>
            <input type="text" name="usernamex" class="form-control " placeholder="" required>
        </div>

        <div class="form-group">
        <label>What is your childhood nickname?</label>
            <input type="text" name="ans1" class="form-control " placeholder="" required>
        </div>

        <div class="form-group">
        <label>What is your mother's maiden name?</label>
            <input type="text" name="ans2" class="form-control " placeholder="" required>
        </div>

        <div class="form-group">
        <label>Where is your birth place?</label>
            <input type="text" name="ans3" class="form-control " placeholder="" required>
        </div>   

        <div class="form-group">
        <label>New Password</label>
            <input type="password" name="new_pass" class="form-control " placeholder="new password" required minlength="8">
        </div>            
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
        <button type="submit" name="reset" class="btn btn-primary">Reset</button>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal_setup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Create admin account to access the system</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="code.php" method="POST">
        <div class="modal-body">
            <div class="form-group">
                <label> Username </label>
                <input type="text" name="username" class="form-control" placeholder="Enter Username" required="">
            </div>
            <div class="form-group">
                <label> Email </label>
                <input type="email" name="email" class="form-control" placeholder="Enter email " required="">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" id="admin_password" name="password" class="form-control" placeholder="Enter Password" required="" minlength="8">
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" id="admin_confirmpassword" name="confirmpassword" class="form-control" placeholder="Confirm Password" required="" minlength="8">
            </div> 
            <div class="form-group">
            <input type="hidden" id="user_type" name="user_type" value="admin" class="form-control">
            </div> 

            <div class="custom-control custom-checkbox float-left mb-2" >
             <input type="checkbox" class="custom-control-input" id="customCheck2" onclick="showPasss2()"  >
             <label class="custom-control-label" for="customCheck2">Show Password</label>
            </div>    
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="submit" name="set_up" class="btn btn-primary">Register</button>
        </div>
      </form>
    </div>

<?php 
 $stmt = $objtUser->runQuery("SELECT * FROM register WHERE username<>'Admin'");
 $stmt->execute();
 $rowUser = $stmt->fetch(PDO::FETCH_ASSOC);
 $count = $stmt->rowCount();
  if($count == 0 ){
    echo'<script>
      $(document).ready(function(){
          $("#modal_setup").modal("show");
        });
    </script>';
  }else{
    if(!empty($_SESSION['set_up'])){
      echo'<script>swal ("Admin created. You may now login", "", "success");  </script>';
      unset($_SESSION['set_up']);
    }
  }
?>
<?php

    if(isset($_POST['reset'])){
      $username=$_POST['usernamex'];
      $ans1 = $_POST['ans1'];
      $ans2 = $_POST['ans2'];
      $ans3 = $_POST['ans3'];
      $new_pass = $_POST['new_pass'];
      $password_length = strlen($new_pass);
      $query = "SELECT * FROM register WHERE username = :username";
      $stmt = $objtUser-> runQuery($query);
      $stmt->execute(['username' => $username]);
      $count = $stmt->rowCount();
      if($count >0){
        $rowUser = $stmt->fetch(PDO::FETCH_ASSOC);
         if(password_verify($ans1,$rowUser['ans1']) && password_verify($ans2,$rowUser['ans2']) && password_verify($ans3,$rowUser['ans3'])){
          if($password_length >= 8){
            if (preg_match('/[a-zA-Z]/', $new_pass) && preg_match('/\d/',$new_pass)&& preg_match('/[^a-zA-Z\d]/',$new_pass)&&preg_match("/[A-Z]/",$new_pass)){
          $hash = password_hash($new_pass,PASSWORD_DEFAULT);
          $objtUser-> reset_passwords($hash, $username);
          echo'
            <script>
              swal("Password reset", "", "success");  
            </script>
          ';
          }else{
            echo'<script>swal ("Please provide aleast one letter with uppercase, one number and one special character", "New password not set", "error");  </script>';
              }
          }else{ 
            echo'<script>swal ("Your password is too short, Please provide 8 character lenght or more, to secure your password", "New password not set", "error");  </script>';
          }
         }else{
            echo'
            <script>
              swal("Some of the key that you entered are incorrect, try again.", "New password not set", "error");  
            </script>
          ';
         }
      }else{
        echo'
          <script>
            swal("Username incorrect, try again.", "New password not set", "error");  
          </script>
        ';
      }

    }
?>
<script>
function showPass() { 
  var x = document.getElementById("password ");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
}


function showPasss2() {
  var x = document.getElementById("admin_password");
  var y = document.getElementById("admin_confirmpassword");
  if (x.type === "password" || y.type === "password") {
    x.type = "text";
    y.type = "text";
  } else {
    x.type = "password";
    y.type = "password";
  }
}
</script>



<script> 
        function isOnline() { 
            if (navigator.onLine) { 
              window.location="forgot_pass.php";
            } else { 
              swal("You dont have internet access, connect your device to internet first.", "", "info");
            } 
        } 
    </script> 