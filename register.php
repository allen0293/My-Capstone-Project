<?php
date_default_timezone_set('Asia/Manila');
include('includes/security.php');
require_once 'classes/Cregister.php';
require_once 'classes/user_logs.php';
require_once 'classes/user.php';
$objtactivity =  new User();
$objtUser = new Cregistration();
$objtLogs = new User_logs();
//DELETE USERS
if(isset($_GET['delete_id'])){
    $user_id = $_GET['delete_id'];
    try{
      if($user_id != null){
        if($objtUser->delete($user_id)){
            header("Location: register.php?deleted");
        }
      }else{
        var_dump($user_id);
      }
    }catch(PDOException $e){
      echo $e->getMessage();
    }
  }

//Multiple Delete For User
  if(isset($_POST['delete_user']))
{
  if(!empty($_POST["no"])){
    foreach($_POST["no"] as $id)
     {
        $query = "DELETE FROM register WHERE user_id = '".$id."'";
        $stmt = $objtUser-> runQuery($query);
        $stmt->execute();
    }
    $user_id = $_SESSION['user_id'];
     $url = $_SERVER['REQUEST_URI'];
     $date_time = date("Y-m-d H:i:s");
     $action = "Deleted a User";
     $objtactivity->user_activity($user_id, $action, $url, $date_time);  
    header('Location: register.php?deleted');
  }else{
    header('Location: register.php?no_user_data');
  }

}
?> 
<?php
include('includes/header.php'); 
include('includes/navbar.php'); 

if($_SESSION['user_type']!="admin"){
      echo '<script>
      var x = document.getElementById("Admin-hide");
      x.style.display = "none";
      window.location="404.php";
    </script>';
    die();
}

if (!empty($_SESSION['admin_success'])) {
  echo'<script>swal ("Welcome '.$_SESSION['username'].'", "", "success");  </script>';
  unset($_SESSION['admin_success']);
}
 ?>

 <style type="text/css">
    .user-label{
      cursor:pointer;
    }

    .user-label:hover{
      opacity: 0.5;
    }
    .dt-buttons{
      position: absolute !important;
    }
</style>
<div class="modal fade" id="addadminprofile" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add User</h5>
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
                <input type="password" id="user_password" name="password" class="form-control" placeholder="Enter Password" required="" minlength="8">
            </div>
            <!--div class="form-group">
                <label>Confirm Password</label>
                <input type="password" id="user_confirmpassword" name="confirmpassword" class="form-control" placeholder="Confirm Password" required="">
            </div--> 
            <div class="form-group">
                <label>User Type</label>
                <select name="user_type" class="form-control">
                  <option value="admin">Admin</option>
                  <option value="user">User</option>
                </select>
            </div> 

            <div class="custom-control custom-checkbox float-left mb-2" >
             <input type="checkbox" class="custom-control-input" id="customCheck2" onclick="showPasss()"  >
             <label class="custom-control-label" for="customCheck2">Show Password</label>
            </div>    
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="submit" name="registerbtn" class="btn btn-primary">Register</button>
        </div>
      </form>

    </div>
  </div>
</div>

<div class="container-fluid">
<?php
     if(isset($_GET['deleted'])){
      echo '<script>
             swal("User Deleted", "", "success");
        </script>';
    }else if(isset($_GET['inserted'])){
      echo '<script>
             swal("New User Added", "", "success");
        </script>';
    }else if(isset($_GET['updated'])){
          echo '<script>
             swal("User password updated", "", "success");
        </script>';
      }else if(isset($_GET['error'])){
      echo '<script>
             swal("Database Error, Something went wrong", "", "error");
        </script>';
    }
    else if(isset($_GET['wrong'])){
      echo '<script>
             swal("Password did not match", "", "info");
        </script>';
    }else if(isset($_GET['username'])){
      echo '<script>
             swal("Username is already existed", "", "info");
        </script>';
    }else if(isset($_GET['email'])){
      echo '<script>
             swal("email is already existed,try another email", "", "info");
        </script>';
    }else if(isset($_GET['no_user_data'])){
          echo '<script>
             swal("No data Selected", "", "error");
        </script>';
        }
    ?>

<form action="register.php" method="post">
<div class="card shadow mb-4">
  <div class="card-header bg-white py-3">
    <h4 class="m-0 font-weight-bold text-primary">User Profile 
            <button type="button" class="btn  btn-sm btn-primary" data-toggle="modal" data-target="#addadminprofile">
              <i class="fas fa-user-plus"></i> Add User
            </button>
            <input class="d-sm-inline-block btn btn-sm btn-danger shadow-sm mr-1 confirmation" type="submit" name="delete_user" value="Delete">
    </h4>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="cell-border hover " id="users" >
        <thead align="center" class="bg-success">
          <tr>
            <th class="text-center"><label for="select-user" class="mr-3 user-label text-white">Check All</label><input class="d-none" type="checkbox" id="select-user"></th>
            <!--th>RESET </th-->   
            <th> ID </th>
            <th> Username </th>
            <th>User Type</th>    
          </tr>
          </thead>
          <?php
              $query = "SELECT * FROM register WHERE username<>'Admin'";
              $stmt = $objtUser-> runQuery($query);
              $stmt->execute();
              ?>
             <tbody>
              <?php
               $count= $stmt->rowCount();
                if($count > 0){
                  while($rowUser = $stmt->fetch(PDO::FETCH_ASSOC)){
               ?>
              <tr>
              <td align="center">
              <input style="width: 20px; height: 20px;" class="user_id" type="checkbox" name="no[]" value="<?php echo $rowUser["user_id"]; ?>">
              </td>
              <!--td align="center">     
                <a href="register_edit.php?reset_id=<?php print($rowUser['user_id']);?>" class="btn-sm btn-success">RESET </a>
              </td-->
              <td><?php print($rowUser['user_id']);?></td>
              <td><?php print($rowUser['username']);?></td>
              <td><?php print($rowUser['user_type']);?></td>
             
          <?php } } ?>
          </tr>     
        </tbody>
        
      </table>
      </form>
    </div>
  </div>
</div>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Set your password security key</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="code.php" method="post">

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
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
        <button type="submit" name="admin_security" class="btn btn-primary">Save</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php 
  $username = $_SESSION['username'];
  $query = "SELECT * FROM register WHERE username = :username AND ans1 IS NULL";
  $stmt = $objtUser->runQuery($query);
  $stmt->execute(['username' => $username]);
  $count = $stmt->rowCount();
  if($count>0){
      echo'<script>
        $(document).ready(function(){
            $("#myModal").modal("show");
          });
      </script>';
  }else{
    if(!empty($_SESSION['security_key'])){
      echo'<script>swal ("Pasword recovery key set", "", "success");  </script>';
       unset($_SESSION['security_key']);
    }
  }
?>

<?php
//include('includes/scripts.php');
include('includes/footer.php');
?>
 
<script>
  // JQuery confirmation
  $('.confirmation').on('click', function () {
      return confirm('Are you sure you want do delete this user? This will also delete all record processed by this user');
  });

 </script>

 <script>
function showPasss() {
  var x = document.getElementById("user_password");
  var y = document.getElementById("user_confirmpassword");
  if (x.type === "password" || y.type === "password") {
    x.type = "text";
    y.type = "text";
  } else {
    x.type = "password";
    y.type = "password";
  }
}

</script>

<script type="text/javascript">
  document.getElementById('select-user').onclick = function() {
  var checkboxes = document.getElementsByClassName('user_id');
  for (var checkbox of checkboxes) {
    checkbox.checked = this.checked;
  }
}
</script>

<link rel="stylesheet" type="text/css" href="DataTables/datatables.min.css"/>
<script type="text/javascript" src="DataTables/datatables.min.js"></script> 

<script type="text/javascript">
    $(document).ready(function() {
      $('#users').DataTable({
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "pagingType": "full_numbers"
      });

  } );
  </script>
  
