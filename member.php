<?php
// Show PHP errors
date_default_timezone_set('Asia/Manila');
ini_set('display_errors',0);
ini_set('display_startup_erros',0);
error_reporting(0);
include('includes/security.php');
require_once 'classes/user.php';
require_once 'classes/encryption.php';
$objtEncrypt = new Encryption();
$objtUser = new User();
// GET
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


if(isset($_GET['delete_id'])){
  $member_id = $_GET['delete_id'];
  try{
    if($member_id != null){
      if($objtUser->delete($member_id)){

        $user_id = $_SESSION['user_id'];
        $url = $_SERVER['REQUEST_URI'];
        $date_time = date("Y-m-d H:i:s");
        $action = "Deleted a Member ID $member_id";
        $objtUser->user_activity($user_id, $action, $url, $date_time); 
        echo '<script> swal("Member Deleted", "", "success");</script>';
      }
    }else{
      var_dump($member_id);
    }
  }catch(PDOException $e){
    echo $e->getMessage();
  }
}

?>
<style>
.fas, .far{cursor: pointer;}
</style>
<!-- Begin Page Content -->
      <div class="container-fluid">
                    <?php
                      if(!empty($_SESSION['new_member'])){
                        echo '<script> swal("New Member Added", "", "success");</script>';
                          unset($_SESSION['new_member']);
                      }

                      if(!empty($_SESSION['member_updated'])){
                        echo '<script> swal("Member Details Updated", "", "success");</script>';
                          unset($_SESSION['member_updated']);
                      }
 
                      if(!empty($_SESSION['puc_output'])){
                        echo '<script> swal("Payment Success", "", "success");</script>';
                          unset($_SESSION['puc_output']);
                      }

                      if(!empty($_SESSION['pending_loan'])){
                         echo '<script> swal("This member have pending loan balance", "", "warning"); </script>';
                         unset($_SESSION['pending_loan']);
                      }

                      if(!empty($_SESSION['pending_loan_cbu'])){
                         echo '<script> swal("Cant add Capital build up,This member have pending loan balance", "", "warning"); </script>';
                         unset($_SESSION['pending_loan_cbu']);
                     }

                      if(!empty($_SESSION['not_full_paid'])){
                          echo '<script> swal("This member Capital Build up is not full paid", "", "info"); </script>';
                          unset($_SESSION['not_full_paid']);
                      }

                      if(!empty($_SESSION['cbu_subtract'])){
                         echo '<script> swal("An amount is  deducted from Captital build up of member ID'.$_SESSION['cbu_subtract'].'", "", "info"); </script>';
                          unset($_SESSION['cbu_subtract']);
                      }

                      if(!empty($_SESSION['cbu_added'])){
                         echo '<script> swal("Capital Build up Added", "", "success"); </script>';
                          unset($_SESSION['cbu_added']);
                      }

                      if(!empty($_SESSION['full_paid'])){
                          echo '<script> swal(" This member is Full paid", "", "info");</script>';
                          unset($_SESSION['full_paid']);
                      }

                      if(!empty($_SESSION['puc_subtract'])){
                        echo '<script> swal("An amount is  deducted from Paid up Capital of member ID '.$_SESSION['puc_subtract'].'", "", "info");</script>';
                        unset($_SESSION['puc_subtract']);
                      }

                      if(!empty($_SESSION['puc_added'])){
                        echo '<script> swal("Payment Success", "", "success"); </script>';
                         unset($_SESSION['puc_added']);
                     }
                    ?>
                    <?php 
                      if(isset($_Get['deleted'])){
                        echo '<script> swal("Member Deleted", "", "success");</script>';
                      }
                    ?>
                    <div class="card shadow mb-4">
                      <div class="card-header bg-white py-3">
                        <h2 class="m-0 font-weight-bold text-primary ">Member                                                  
                        </h2>
                        <div>
                        <a href="form.php" class="btn btn-sm btn-primary shadow-sm mr-1 mb-1"><i
                              class="fas fa-user-plus "></i> Add Member</a> 
                              <a href="puc_transaction.php?" class="btn btn-sm btn-primary shadow-sm mr-1 mb-1"> Capital Build Up Records</a>
                              <a href="export.php?member_pdf" target="_blank" class=" btn btn-sm btn-danger shadow-sm  mr-1 mb-1"><i class="fas fa-print"></i> Print/Preview</a>
                              <a href="export_excel.php?member" target="_blank" class="btn btn-sm  btn-success shadow-sm mb-1"> <i class="fas fa-download"></i> Excel</a>    
                          </div>
                      </div>
                    <div class="card-body ">
                    <div class="table-responsive">
                        <table class="cell-border hover display nowrap" id="member_table">
                                <thead class="bg-success">
                                <tr>
                                    <th>Action</th>   
                                    <th>Member ID</th>
                                    <th>TIN</th>
                                    <th>Name</th>
                                    <th>Date of Birth</th>
                                    <th>Spouse Name</th>
                                    <th>Address</th>
                                    <th>Contact Number</th>
                                    <th>Land Location</th>
                                    <th>Land Size</th>
                                    <th>Crop1</th>
                                    <th>Crop2</th>
                                    <th>Crop3</th>
                                    <th>Capital Build Up</th>
                                    <th>Paid Up Capital</th>
                                    <th>Date Registered</th>
                                    <th>Registration Fee</th>         
                                </tr>
                                </thead>
                                <?php
                                $query = "SELECT * FROM member";
                                $stmt = $objtUser-> runQuery($query);
                                $stmt->execute();
                                ?>
                                <tbody>
                                 <?php
                                 $count= $stmt->rowCount();
                                  if($count > 0){
                                    while($rowUser = $stmt->fetch(PDO::FETCH_ASSOC)){
                                       $member_idx = $rowUser['member_id'];
                                       $date_reg = date_create($rowUser['registered_date']);
                                       $date = date_create($objtEncrypt->decrypt($rowUser['birthdate']));
                                       $TIN = $objtEncrypt->decrypt($rowUser['TIN']);
                                       $Lname = $objtEncrypt->decrypt($rowUser['Lname']);
                                       $Fname = $objtEncrypt->decrypt($rowUser['Fname']);
                                       $Mname = $objtEncrypt->decrypt($rowUser['Mname']);
                                       $Fullname = $Lname.", ".$Fname." ".$Mname;
                                       $spouse_name = $objtEncrypt->decrypt($rowUser['spouse_name']);
                                       $address=$objtEncrypt->decrypt($rowUser['address']);
                                       $contact = $objtEncrypt->decrypt($rowUser['contactno']);
                                       $land = $objtEncrypt->decrypt($rowUser['land_location']);
                                       $land_size=$objtEncrypt->decrypt($rowUser['land_size']);
                                 ?>
                                  <tr>
                                    
                                  <td style="font-size: 22px;">
                                      
                                      <a href="#" name="view" class="view_member_details text-muted" data-toggle="modal" data-target="#memberModal<?php echo $rowUser['member_id']; ?>"> 
                                      <abbr title="View Details"><i class="fas fa-eye"></i></abbr></a>
                                    
                                      <a href="export.php?cor=<?php echo $rowUser['member_id'];?>" target="_blank" class="text-warning"> 
                                      <abbr title="Print Certificate of registration"><i class="fas fa-print"></i></abbr></a>
                              
                                      <a href="add_cbu.php?add_id=<?php print($rowUser['member_id']);?>" class="text-success"> 
                                      <abbr title="Add Capital Build Up"><i class="fas fa-plus-circle"></i></abbr></a>
                                                                            
                                      <a href="pay_puc.php?pay_id=<?php print($rowUser['member_id']);?>" class="text-info"> 
                                      <abbr title="Pay Capital Build Up"><i class="far fa-credit-card"></i></abbr></a>
                     
                                      <a href="process_loan.php?loan=<?php print($rowUser['member_id']);?>" class="text-info"> 
                                      <abbr title="Loan"><i class="fas fa-hand-holding-usd"></i></abbr></a>
                   
                                      <a href="frm_rent.php?get_id=<?php echo $rowUser['member_id']; ?>">
                                      <abbr title="Rent Equipment"><i class="fas fa-tractor"></i></abbr></a>
                  
                                      <a href="form.php?edit_id=<?php print($rowUser['member_id']);?>"> 
                                      <abbr title="Edit Member profile"><i class="fas fa-user-edit"></i></abbr></a>
                                      
                                      <a class="confirmation text-danger" href="member.php?delete_id=<?php print($rowUser['member_id']);?>">
                                      <abbr title="Delete Member"><i class="fas fa-user-times"></i></abbr></a>
                                      
                                    </td>
                                    <td><?php echo $rowUser['member_id'];?></td>
                                    <td><?php echo $TIN; ?></td>
                                    <td><?php echo $Fullname; ?></td>
                                    <td ><?php echo date_format($date,'F d, Y');?></td>
                                    <td><?php echo $spouse_name; ?></td>
                                    <td><?php echo $address; ?></td>
                                    <td><?php echo $contact; ?></td>
                                    <td><?php echo $land; ?></td>
                                    <td><?php echo $land_size; ?></td>
                                    <td><?php echo $objtEncrypt->decrypt($rowUser['crop1']); ?></td>
                                    <td><?php echo $objtEncrypt->decrypt($rowUser['crop2']); ?></td>
                                    <td><?php echo $objtEncrypt->decrypt($rowUser['crop3']); ?></td>
                                    <td><?php echo number_format($rowUser['capital_build_up'],2); ?></td>
                                    <td><?php echo number_format($rowUser['paid_up_capital'],2); ?></td>
                                    <td><?php echo date_format($date_reg,'F d, Y h:i A');?></td>  
                                    <td><?php echo number_format($rowUser['reg_fee'],2);?></td>
                                    
                                </tr>

                                <div class="modal fade" id="memberModal<?php echo $rowUser['member_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                  <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                     <div class="modal-header">
                                       <h5 class="modal-title" id="exampleModalLabel">Member Details</h5>
                                         <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                           <span aria-hidden="true">×</span>
                                          </button>
                                        </div>
                                       <div class="modal-body" id="member_details">
                                            <p><strong>Member ID: </strong><?php echo $rowUser['member_id']; ?></p>
                                            <p><strong>Tax Identification Number: </strong><?php echo $TIN ?></p>
                                            <p><strong>Name: </strong><?php echo $Fullname ?></p>
                                            <p><strong>Date of Birth: </strong><?php echo date_format($date,'F d, Y');?></p>
                                            <p><strong>Spouse Name: </strong><?php echo $spouse_name; ?></p>
                                            <p><strong>Address: </strong><?php echo $address; ?></p>
                                            <p><strong>Contact number: </strong><?php echo $contact; ?></p>
                                            <p><strong>Land Location: </strong><?php echo $land; ?></p>
                                            <p><strong>Land Size: </strong><?php echo $land_size; ?></p>
                                            <p class="text-center"><strong>Kinds of Crop Planted</strong></p>
                                            <p><strong>First Crop:</strong> <?php echo $objtEncrypt->decrypt($rowUser['crop1']); ?> </p>
                                            <p><strong>Second Crop:</strong> <?php echo $objtEncrypt->decrypt($rowUser['crop2']); ?> </p>
                                            <p> <strong>Third Crop:</strong> <?php echo $objtEncrypt->decrypt($rowUser['crop3']); ?> </p>
                                            <hr>
                                            <p> <strong>Capital Build Up:</strong> <?php echo $rowUser['capital_build_up']; ?> </p>
                                            <p> <strong>Paid up Capital:</strong> <?php echo $rowUser['paid_up_capital']; ?> </p>
                                            <p> <strong>Registration Fee:</strong> <?php echo $rowUser['reg_fee']; ?> </p>
                                      </div>
                                      <div class="modal-footer">
                                        <button class="btn btn-outline-dark" type="button" data-dismiss="modal">Close</button>
                                    </div>
                                  </div>
                                </div>
                               <?php }  }?>
                            </tbody>
                             <tfoot>
                                  <?php 
                                      $querys = "SELECT sum(reg_fee) as total_reg_fee FROM member";
                                      $stmts = $objtUser->runQuery($querys);
                                      $stmts->execute();
                                      $rowMember = $stmts->fetch(PDO::FETCH_ASSOC);
                                  ?>
                                  <tr>
                                      <th colspan="12" style="text-align:right">Total Registration Fee: <?php echo number_format($rowMember['total_reg_fee'],2); ?></th>
                                  </tr>
                              </tfoot> 
                        </table>

                        </div>
                      </div>
                  </div>       
            </div>
        </div>

    
  </div>
  
  <?php
  //include('includes/footer.php');
  //include('includes/scripts.php')
  ?>
        <!-- Custom scripts -->
<script>
    // JQuery confirmation
    $('.confirmation').on('click', function () {
        return confirm('Are you sure you want to delete this Member?');
    });
</script>

<link rel="stylesheet" type="text/css" href="DataTables/datatables.min.css"/>
<script type="text/javascript" src="DataTables/datatables.min.js"></script> 
<script type="text/javascript">
 $(document).ready(function() {
    $('#member_table').DataTable( {
        "scrollX": true,
        "pagingType": "full_numbers",
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        
    } );
} );
</script>

  <?php 
    if(isset($_POST['member_id'])){
      $output='';
      $member_id = $_POST['member_id'];
      $stmt = $objtUser->runQuery("SELECT * FROM member WHERE member_id = :member_id");
      $stmt->execute(array(":member_id"=> $member_id));
      $rowUser = $stmt->fetch(PDO::FETCH_ASSOC);
      $count = $stmt->rowCount();
      if($count>0){
         $output .='
              <tr>
                <td>Member ID</td>
                <td>'.$rowUser['member_id'].'</td>
              </tr>
         ';
      }
      echo $output;
    }
  
  ?>