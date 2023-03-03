<?php
date_default_timezone_set('Asia/Manila');
include('includes/security.php');
require_once 'classes/Cregister.php';
require_once 'classes/user_logs.php';
require_once 'classes/user.php';
$objtactivity =  new User();
$objtUser = new Cregistration();
$objtLogs = new User_logs();
//Multiple Delete For User
  if(isset($_POST['delete_user']))
{
  if(!empty($_POST["no"])){
    foreach($_POST["no"] as $id)
     {
        $query = "DELETE FROM user_activities WHERE act_id = '".$id."'";
        $stmt = $objtUser-> runQuery($query);
        $stmt->execute();
    }
    $user_id = $_SESSION['user_id'];
     $url = $_SERVER['REQUEST_URI'];
     $date_time = date("Y-m-d H:i:s");
     $action = "Deleted a Activity";
     $objtactivity->user_activity($user_id, $action, $url, $date_time);  
    header('Location: user_activity.php?deleted');
  }else{
    header('Location: user_activity.php?no_data');
  }

}

if(isset($_POST['delete_activity']))
{
  if(!empty($_POST["no"])){
    foreach($_POST["no"] as $id)
     {
        $query = "DELETE FROM user_activities WHERE act_id = '".$id."'";
        $stmt = $objtUser-> runQuery($query);
        $stmt->execute();
    }
    $user_id = $_SESSION['user_id'];
     $url = $_SERVER['REQUEST_URI'];
     $date_time = date("Y-m-d H:i:s");
     $action = "Deleted a Activity";
     $objtactivity->user_activity($user_id, $action, $url, $date_time);  
    header('Location: user_activity.php?deleted');
  }else{
    header('Location: user_activity.php?no_data');  
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
  




<!-- USER ACTIVITY -->

<div class="container-fluid">
<?php
     if(isset($_GET['deleted'])){
      echo '<script>
             swal("Deleted", "", "success");
        </script>';
    }else if(isset($_GET['no_data'])){
          echo '<script>
             swal("No Data Selected", "", "error");
        </script>';
        }
    ?>

<form  method="post">
<!-- USER LOGS -->
 <form method="post">
  <div class="card shadow mb-4">
  <div class="card-header bg-white py-3">
    <h4 class="m-0 font-weight-bold text-primary">User Activity
            <input class="d-sm-inline-block btn btn-sm btn-danger shadow-sm mr-1 confirmation" type="submit" name="delete_activity" value="Delete">
    </h4>
  </div>
  <div class="card-body">
    <div class="table-responsive" width="100%">
    
      <table class=" cell-border hover " id="activity">
        <thead class="bg-success">
          <tr>
            <th class="text-center"><label for="select-activity" class="mr-3 log-label text-white">Check All</label><input class="d-none" type="checkbox" id="select-activity"></th>
            <th>Activity ID</th>
            <th>User ID</th>
            <th> Username</th>
            <th> Action </th>
            <!--th>Location</th-->
            <th> Date Time</th>            
          </tr>
        </thead>
          <?php
              $query = "SELECT user_activities.act_id, user_activities.user_id, register.username, user_activities.action, user_activities.act_url, user_activities.date_time FROM user_activities INNER JOIN register ON register.user_id = user_activities.user_id GROUP BY user_activities.act_id DESC ";
              $stmt = $objtLogs-> runQuery($query);
              $stmt->execute();
              ?>
             <tbody>

              <?php
               $count= $stmt->rowCount();
                if($count > 0){
                  while($rowActivity = $stmt->fetch(PDO::FETCH_ASSOC)){
                    $date_time = date_create($rowActivity['date_time']);
               ?>
            <tr>
            <td align="center">
             <input style="width: 20px; height: 20px;" class="activity" type="checkbox" name="no[]" value="<?php echo $rowActivity["act_id"]; ?>">
            </td> 
            <td><?php print($rowActivity['act_id']);?></td>
            <td><?php print($rowActivity['user_id']);?></td>
            <td><?php print($rowActivity['username']);?></td>
            <td><?php print($rowActivity['action']);?></td>
            <!--td><?php print($rowActivity['act_url']);?></td-->
            <td><?php print(date_format($date_time,'F d, Y h:i A'));?></td>       
          </tr>
          <?php } }   ?>
        </tbody>

    </table>
  </form>
    </div>
</div>
</div>

    </div>
  </div>
</div>
</div-->
<?php
//include('includes/scripts.php');
//include('includes/footer.php');
?>
 
<script>
  // JQuery confirmation
  $('.confirmation').on('click', function () {
      return confirm('Are you sure you want do delete this record?');
  });
 </script>

<script type="text/javascript">
  document.getElementById('select-activity').onclick = function() {
  var checkboxes = document.getElementsByClassName('activity');
  for (var checkbox of checkboxes) {
    checkbox.checked = this.checked;
  }
}
</script>

<link rel="stylesheet" type="text/css" href="DataTables/datatables.min.css"/>
<script type="text/javascript" src="DataTables/datatables.min.js"></script> 


<script type="text/javascript">
    $(document).ready(function() {
    $('#activity').DataTable( {
        "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
        "pagingType": "full_numbers"
    } );
} );
  </script>