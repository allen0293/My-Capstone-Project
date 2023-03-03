<?php
date_default_timezone_set('Asia/Manila');
include('includes/security.php');
require_once 'classes/Cregister.php';
require_once 'classes/user.php';
$objtUser = new User();
$objtRegister = new Cregistration();

$_SESSION['username'];

 if(isset($_POST['update'])){
   $username = $_SESSION['username'];
   $oldpassword = $_POST['oldpassword'];
   $newpassword = $_POST['newpassword'];
   $cpassword = $_POST['confirmpassword'];

        $query = "SELECT user_id, password FROM register WHERE username = '$username'";
        $stmt = $objtRegister-> runQuery($query);
        $stmt->execute();
        $count= $stmt->rowCount();

          if($count > 0){
             $rowUser = $stmt->fetch(PDO::FETCH_ASSOC);
             $id = $rowUser['user_id'];
              
                if(password_verify($oldpassword,$rowUser['password'])){
                    
                    if ($newpassword === $cpassword) {
                      $hash = password_hash($newpassword,PASSWORD_DEFAULT);
                      
                      if($objtRegister->update_password($hash, $id)){

                          $user_id = $_SESSION['user_id'];
                          $url = $_SERVER['REQUEST_URI'];
                          $date_time = date("Y-m-d H:i:s");
                          $action = "Changed password";
                          $objtUser->user_activity($user_id, $action, $url, $date_time);

                        header("Location: change_password.php?updated_password");

                    }else{  header("Location: change_password.php?db_error"); }

                  }else{ header("Location: change_password.php?c_password"); }
                
                }else{ header("Location: change_password.php?old_password"); }
          }
}
?>
<?php
include('includes/header.php'); 
include('includes/navbar.php'); 
if($_SESSION['username']!="Admin"){
      echo '<script>
      var x = document.getElementById("Admin-hide");
      x.style.display = "none";
    </script>';
}
 ?>
<div class="container-fluid">
  <?php 
    if (isset($_GET['updated_password'])) {
      echo '<div class="alert alert-info alert-dismissable fade show" role="alert">
      <strong>New Password</strong> Updated with success.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true"> &times; </span>
        </button>
      </div>';
    }else if (isset($_GET['db_error'])) {
      echo '<div class="alert alert-warning alert-dismissable fade show" role="alert">
      <strong>Db Error</strong>Something went wrong.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true"> &times; </span>
        </button>
      </div>';
    }else if (isset($_GET['c_password'])) {
      echo '<div class="alert alert-warning alert-dismissable fade show" role="alert">
      <strong>Confirm Password Did not match</strong>, Please try again.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true"> &times; </span>
        </button>
      </div>';
    }else if (isset($_GET['old_password'])) {
      echo '<div class="alert alert-warning alert-dismissable fade show" role="alert">
      <strong>Old Password incorrect</strong>, Please try again.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true"> &times; </span>
        </button>
      </div>';
    }
  ?>
<div class="card shadow mb-4">
  <div class="card-header py-3">
    <h4 class="m-0 font-weight-bold text-primary">Change Password 
    </h4>
  </div>
  <div class="card-body">
  		<form method="POST">
        <div class="modal-body">
            <div class="form-group">
                <label> Username </label>
                <input type="text" name="username" value="<?php echo $_SESSION['username'];?>" class="form-control" placeholder="Enter Username" readonly>
            </div>
              <div class="form-group">
                  <label>Password</label>
                  <input type="password" id="password" name="oldpassword" class="form-control" placeholder="Enter Old Password" required="">
              </div>
              <div class="form-group">
                  <label>New Password</label>
                  <input type="password" id="newpassword" name="newpassword" class="form-control" placeholder="Enter New Password" required="">
              </div>
                <div class="form-group">
                  <label>Confirm Password</label>
                  <input type="password" id="confirmpassword" name="confirmpassword" class="form-control" placeholder="Confirm Password" required="">
                </div>
          </div>
            <div class="custom-control custom-checkbox ml-3" >
                  <input type="checkbox" class="custom-control-input" id="customCheck1" onclick="showPass()"  >
                  <label class="custom-control-label" for="customCheck1">Show Password</label>
              </div>
        <div class="modal-footer">
            <a href="dashboard.php" type="button" class="btn btn-danger">Cancel</a>
            <button type="submit" name="update" class="btn btn-primary">Save</button>
        </div>
      </form>
  </div>
</div>
</div>
<?php
//include('includes/scripts.php');
include('includes/footer.php');
?>
<script>
function showPass() {
  var x = document.getElementById("password");
  var y = document.getElementById("newpassword");
  var z = document.getElementById("confirmpassword");
  if (x.type === "password" || y.type === "password" || z.type === "password") {
    x.type = "text";
    y.type = "text";
    z.type = "text";
  } else {
    x.type = "password";
    y.type = "password";
    z.type = "password";
  }
}
</script>
