<?php
// Show PHP errors
date_default_timezone_set('Asia/Manila');
ini_set('display_errors',0);
ini_set('display_startup_erros',0);
include('includes/security.php');
require_once 'classes/equipment.php';
require_once 'classes/encryption.php';
require_once 'classes/user.php';
$objtUser = new User();
$objtEncrypt = new Encryption();
$objtEqp = new Equipment();
// GET
if(isset($_GET['edit_id'])){
    $eqp_id = $_GET['edit_id'];
    $stmt = $objtEqp->runQuery("SELECT * FROM equipment WHERE eqp_id = :eqp_id");
    $stmt->execute(array(":eqp_id"=> $eqp_id));
    $rowEqp = $stmt->fetch(PDO::FETCH_ASSOC);
    $status = $rowEqp['status'];

    if($status == 'occupied'){
      $objtEqp->status_unoccupied($rowEqp['eqp_id']);
      header("Location: display_equipment.php");
    }else{
        $objtEqp->status_occupied($rowEqp['eqp_id']);
        header("Location: display_equipment.php");
    }
}else{
    $eqp_id = null;
    $rowEqp = null; 
}

// DELETE Equipment
if(isset($_GET['delete_id'])){
    $eqp_id = $_GET['delete_id'];
    try{
      if($eqp_id != null){
        if($objtEqp->delete($eqp_id)){
          $user_id = $_SESSION['user_id'];
          $url = $_SERVER['REQUEST_URI'];
          $date_time = date("Y-m-d H:i:s");
          $action = "Delete a equipment ID $eqp_id";
          $objtUser->user_activity($user_id, $action, $url, $date_time);  

          $objtEqp->redirect('display_equipment.php?deleted');
        }
      }else{
        var_dump($member_id);
      }
    }catch(PDOException $e){
      echo $e->getMessage();
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
    </script>';
}

?>
<style>
.far{cursor: pointer;}
</style> 
        <!-- Header banner -->
        <div class="container-fluid" style="background-color: white">
                <!-- Sidebar menu -->
                    <?php
                      if(isset($_GET['updated'])){
                       echo '<script>
                               swal("Equipment Updated", "", "success");
                          </script>'; 
                      }else if(isset($_GET['deleted'])){
                        echo '<script>
                               swal("Equipment Deleted", "", "success");
                          </script>'; 
                      }else if(isset($_GET['inserted'])){
                        echo '<script>
                               swal("Equipment Added", "", "success");
                          </script>'; 
                      }else if(isset($_GET['error'])){
                        echo '<script>
                               swal("Database Error, Somthing went wrong", "", "success");
                          </script>'; 
                      }
                    ?>
                    <div class="card shadow mb-4">
                      <div class="card-header bg-white py-3">
                        <h2 class="m-0 font-weight-bold text-primary">Equipment 
                        </h2>
                        <div class="mt-2">
                        <a href="frm_equipment.php" class="btn btn-sm btn-primary shadow-sm mb-1"><i class="fas fa-fw fa-wrench"></i> Add Equipment</a>
                                   <a href="export.php?equipment_pdf" target="_blank" class="  btn btn-sm btn-danger shadow-sm mb-1 "><i class="fas fa-print"></i> Print/Preview</a>
                                   <a href="export_excel.php?equipment" target="_blank" class="  btn btn-sm btn-success shadow-sm mb-1"> <i class="fas fa-download"></i> Excel</a>
                         </div>          
                      </div>
                    <div class="card-body">
                    <div class="table-responsive">
                       
                       <table class="cell-border hover " id="eqp_table" width="100%" cellspacing="0">
                           <thead class="bg-success">
                           <tr>
                               <th>Action</th>                            
                               <th>Equipment ID</th>
                               <th>Serial Number</th>
                               <th>Equipment Name</th>
                               <th>Equipment Model</th>
                               <th>Description</th>
                               <th>Rental Price</th> 
                               <th>Status</th>                                
                           </tr>
                           </thead>
                           <?php
                           $query = "SELECT * FROM equipment";
                           $stmt = $objtEqp-> runQuery($query);
                           $stmt->execute();
                           ?>
                           <tbody>
                                <?php
                                $count= $stmt->rowCount();
                                 if($count > 0){
                                   while($rowEqp = $stmt->fetch(PDO::FETCH_ASSOC)){
                                     if($rowEqp['status']=='occupied'){
                                          $color='text-danger';
                                     }else{
                                        $color = 'text-success';
                                     }
                                ?>
                               <tr>
                                   <td class="text-center" style="font-size: 22px">
                                    
                                    <a href="frm_equipment.php?edit_id=<?php print($rowEqp['eqp_id']);?>"> 
                                    <abbr title="Edit Equipment"><i class="far fa-edit"></i></abbr></a>

                                    <a class="confirmation" href="display_equipment.php?delete_id=<?php print($rowEqp['eqp_id']);?>"> 
                                    <abbr title="Delete Equipment"><i class="far fa-trash-alt"></i> </abbr></a>
                                       
                                   </td>
                                   <td><?php print($rowEqp['eqp_id']);?></td>
                                   <td><?php print($objtEncrypt->decrypt($rowEqp['serial_no']));?></td>
                                   <td><?php print($objtEncrypt->decrypt($rowEqp['eqp_name']));?></td>
                                   <td><?php print($objtEncrypt->decrypt($rowEqp['eqp_model']));?></td>
                                   <td><?php print($objtEncrypt->decrypt($rowEqp['eqp_desc']));?></td>
                                   <td><?php print(number_format($rowEqp['rent_price'],2));?></td>  
                                   <td class="<?php echo $color;?>"><?php print($rowEqp['status']);?></td>
                               </tr>
                               <?php } } ?>
                           </tbody>
                                   
                       </table>
                      
                   </div>    
                 </div>
                 </div>    
      </div>
 <!-- Footer scripts, and functions -->
 <?php

include('includes/footer.php');
//include('includes/scripts.php');
?>
  <!-- Custom scripts -->
  <script>
      // JQuery confirmation
      $('.confirmation').on('click', function () {
          return confirm('Are you sure you want do delete this Equipment?');
      });
  </script>
</html>

<link rel="stylesheet" type="text/css" href="DataTables/datatables.min.css"/>
<script type="text/javascript" src="DataTables/datatables.min.js"></script> 
<script type="text/javascript">
    $(document).ready(function() {
      $('#eqp_table').DataTable({
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
      });
  } );
</script>