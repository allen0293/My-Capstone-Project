<?php
date_default_timezone_set('Asia/Manila');
include('includes/security.php');
include('includes/header.php'); 
include('includes/navbar.php'); 
require_once 'classes/Cregister.php';
require_once 'classes/user.php';
$objtactivity = new User();

$objtRegister= new Cregistration();
	if (isset($_GET['reset_id'])) {
  		$id = $_GET['reset_id'];
   		$query = "SELECT * FROM register WHERE user_id = '$id'" ;
   		$stmt = $objtRegister-> runQuery($query);
        $stmt->execute();
		$count= $stmt->rowCount();
		$rowUser = $stmt->fetch(PDO::FETCH_ASSOC);
   }else{
    $id = null;
    $rowUser = null; 
}
   if(isset($_POST['update'])){
   	$password = $_POST['password'];
   	$cpassword = $_POST['confirmpassword'];
    $user_type = $_POST['user_type'];
   		if ($password === $cpassword) {
   			$hash = password_hash($password,PASSWORD_DEFAULT);

   			if($objtRegister->update($hash, $user_type, $id)){
          $user_id = $_SESSION['user_id'];
           $url = $_SERVER['REQUEST_URI'];
           $date_time = date("Y-m-d H:i:s");
           $action = "Reset user's password";
           $objtactivity->user_activity($user_id, $action, $url, $date_time);
   				     echo"<script>
                window.location='register.php?updated';
               </script>";
   			}else{
   					echo '<script>
             swal("Database Error, Something went wrong", "", "error");
            </script>';
   			}
   		}else{	
   				echo '<script>
             swal("Password did not match", "", "info");
        </script>';
   		}
   }
?>

<?php 
if($_SESSION['user_type']!="admin"){
      echo '<script>
      var x = document.getElementById("Admin-hide");
      x.style.display = "none";
      window.location="404.php";
    </script>';
    die();
}
 ?>
<div class="container-fluid">
 <div class="card shadow mb-4">
  <div class="card-header py-3">
    <h4 class="m-0 font-weight-bold text-primary">Reset Password 
    </h4>
  </div>
  <div class="card-body">
  		<form method="POST">
        <div class="modal-body">
            <div class="form-group">
                <label> Username </label>
                <input type="text" name="username" value="<?php print($rowUser['username']);?>" class="form-control" placeholder="Enter Username" readonly>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" id="reset_password" name="password" class="form-control" placeholder="Enter Password" required="">
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" id="reset_confirmpassword" name="confirmpassword" class="form-control" placeholder="Confirm Password" required="">
            </div>

            <div class="form-group">
                <label>User Type</label>
                <select name="user_type" class="form-control">
                  <option value="admin">Admin</option>
                  <option value="user">User</option>
                </select>
            </div> 

            <div class="custom-control custom-checkbox float-left mb-2" >
                      <input type="checkbox" class="custom-control-input" id="customCheck" onclick="showPass()"  >
                     <label class="custom-control-label" for="customCheck">Show Password</label>
                    </div>
        </div>
        <div class="modal-footer">
            <a href="register.php" type="button" class="btn btn-danger">Cancel</a>
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
  var x = document.getElementById("reset_password");
  var y = document.getElementById("reset_confirmpassword");
  if (x.type === "password" || y.type === "password") {
    x.type = "text";
    y.type = "text";
  } else {
    x.type = "password";
    y.type = "password";
  }
}
</script>

 