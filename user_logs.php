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


  //Multiple Delete For User's Log
  if(isset($_POST['delete_logs']))
{
  if(!empty($_POST["no"])){
    foreach($_POST["no"] as $id)
     {
        $query = "DELETE FROM user_logs WHERE log_id = '".$id."'";
        $stmt = $objtLogs-> runQuery($query);
        $stmt->execute();
    }
    $user_id = $_SESSION['user_id'];
     $url = $_SERVER['REQUEST_URI'];
     $date_time = date("Y-m-d H:i:s");
     $action = "Deleted User's Logs";
     $objtactivity->user_activity($user_id, $action, $url, $date_time);  
    header('Location: user_logs.php?deleted_log');
  }else{
    header('Location: user_logs.php?no_log_data');
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
 ?>
 <style type="text/css">
      .log-label{
      cursor:pointer;
    }
      .log-label:hover{
        opacity: 0.5;
      }
</style>
<div class="container-fluid">
  <?php 
        if(isset($_GET['no_log_data'])){
          echo '<script>
             swal("No data Selected", "", "error");
        </script>';
        }else if(isset($_GET['deleted_log'])){
          echo '<script>
             swal("User log Deleted", "", "success");
        </script>';
        }
      ?>
<form action="user_logs.php" method="post">
<!-- USER LOGS -->
 <form method="post">
  <div class="card shadow mb-4">
  <div class="card-header bg-white py-3">
    <h4 class="m-0 font-weight-bold text-primary">User Logs
            <!--a href="register.php?delete_all" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm mr-1 confirmationall "><i class="fas fa-user-slash"></i> Delete all</a--> 
            <input class="d-sm-inline-block btn btn-sm btn-danger shadow-sm mr-1 confirmation" type="submit" name="delete_logs" value="Delete">
    </h4>
  </div>
  <div class="card-body">
    <div class="table-responsive" width="100%">    
      <table class=" cell-border hover " id="logs">
        <thead class="bg-success">
          <tr>
            <th class="text-center"><label for="select-log" class="mr-3 log-label text-white">Check All</label><input class="d-none" type="checkbox" id="select-log"></th>
            <th>log ID</th>
            <th> Username</th>
            <th> User Type</th>
            <th> Time Login</th>        
          </tr>
        </thead>
          <?php

              $query = "SELECT user_logs.log_id, register.username,register.user_type, user_logs.login FROM user_logs INNER JOIN register ON register.user_id = user_logs.user_id GROUP BY user_logs.log_id DESC ";
              $stmt = $objtLogs-> runQuery($query);
              $stmt->execute();
              ?>
             <tbody>

              <?php
               $count= $stmt->rowCount();
                if($count > 0){
                  while($rowLogs = $stmt->fetch(PDO::FETCH_ASSOC)){
                    $login = date_create($rowLogs['login']);
               ?>
            <tr>
              <td align="center">
             <!--a class="confirmation btn-sm btn-danger"  href="register.php?delete_log=<?php print($rowLogs['log_id']);?>" >DELETE </a-->
             <input style="width: 20px; height: 20px;" class="log_id" type="checkbox" name="no[]" value="<?php echo $rowLogs["log_id"]; ?>">
            </td> 
            <td><?php print($rowLogs['log_id']);?></td>
            <td><?php print($rowLogs['username']);?></td>
            <td><?php print($rowLogs['user_type']);?></td>
            <td><?php print(date_format($login,'F d, Y h:i A'));?></td>    
          </tr>
          <?php } }  ?>
        </tbody>

    </table>
  </form>
    </div>
  </div>
</div>
</div>

<?php
//include('includes/scripts.php');
include('includes/footer.php');
?>
 
<script>
  // JQuery confirmation
  $('.confirmation').on('click', function () {
      return confirm('Are you sure you want do delete this record?');
  });
 </script>


<script type="text/javascript">
  document.getElementById('select-log').onclick = function() {
  var checkboxes = document.getElementsByClassName('log_id');
  for (var checkbox of checkboxes) {
    checkbox.checked = this.checked;
  }
}
</script>


<link rel="stylesheet" type="text/css" href="DataTables/datatables.min.css"/>
<script type="text/javascript" src="DataTables/datatables.min.js"></script> 

  <script type="text/javascript">
    $(document).ready(function() {
    $('#logs').DataTable( {
        "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
        "pagingType": "full_numbers"
    } );
} );
  </script>

