<?php
// Show PHP errors
date_default_timezone_set('Asia/Manila');
ini_set('display_errors',0);
ini_set('display_startup_erros',0);
error_reporting(0);
include('includes/security.php');
require_once 'classes/encryption.php';
require_once 'classes/user.php';
$objtEncrypt = new Encryption();
$objtUser = new User();
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
<!-- Begin Page Content -->
      <div class="container-fluid">  
        <?php 
          if(!empty($_SESSION['puc_output'])){
            echo '<script>
                   swal("Payment Success", "", "success");
              </script>';
              unset($_SESSION['puc_output']);
          }else if(isset($_GET['full_paid'])){
            $member_id = $_GET['full_paid'];
            $stmt = $objtUser->runQuery("SELECT * FROM member WHERE member_id = :member_id");
            $stmt->execute(array(":member_id"=> $member_id));
            $rowUser = $stmt->fetch(PDO::FETCH_ASSOC);
            echo '<script>
             swal("'.$objtEncrypt->decrypt($rowUser['Fname']).' '.$objtEncrypt->decrypt($rowUser['Lname']).' is Full paid", "", "info");
            </script>';
          }else if(isset($_GET['added'])){
              echo '<script>
                    swal("Capital Build up Added", "", "success");
                  </script>';
          }
        ?>    
              <div class="card shadow mb-4">
                <div class="card-header bg-white py-3">
                  <h2 class="m-0 font-weight-bold text-primary">Paid up Capital                                                         
                  </h2>
                     <a href="puc_transaction.php?" class=" d-sm-inline-block btn btn-sm btn-primary shadow-sm mt-2"> Transaction Record</a>
                    </div>
                    <div class="card-body">
                    <div class="table-responsive">
                      <div class="card-body">
                       <div class="table-responsive" >     
                       <table  class="cell-border hover" id="puc_table">
                           <thead style="background-color: #2E8B57 !important" class="text-center">
                           <tr>
                               <th>Action</th>
                               <th>Member ID</th>
                               <th>Name</th>
                               <th>Capital Build Up</th>
                               <th>Paid up Capital</th>
                               <th>Balance</th>                  
                           </tr>
                           </thead>
                           <?php
                            $query = "SELECT *, (capital_build_up - paid_up_capital) as balance FROM member ORDER BY paid_up_capital  ASC ";
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
                                    <td align="center" style="font-size: 24px">
                                      <abbr title="Add Capital Build Up">
                                      <a href="add_cbu.php?add_id=<?php print($rowUser['member_id']);?>" class="text-success"> 
                                       <i class="fas fa-plus-circle"></i></a>
                                       </abbr>
                                      <abbr title="Pay Capital Build Up">
                                      <a href="pay_puc.php?pay_id=<?php print($rowUser['member_id']);?>" class="text-info"> 
                                       <i class="far fa-credit-card"></i></a>
                                       </abbr>
                                    </td>
                                    <td> <?php print($rowUser['member_id']);?> </td>
                                    <td><?php print($objtEncrypt->decrypt($rowUser['Lname']).", "); 
                                       print($objtEncrypt->decrypt($rowUser['Fname'])." ");
                                       print($objtEncrypt->decrypt($rowUser['Mname']));
                                    ?></td>
                                    <td><?php print($rowUser['capital_build_up']);?> </td>
                                    <td><?php print($rowUser['paid_up_capital']);?> </td>
                                    <td><?php print($rowUser['balance']);?> </td>
                                    <?php } }?>
                                </tr>
                            </tbody> 
                            <tfoot>
                                <tr>
                                <?php 
                                        $stmt = "SELECT sum(paid_up_capital) as total_puc FROM member";
                                        $stmt = $objtUser->runQuery($stmt);
                                        $stmt->execute();
                                        $rowPayments = $stmt->fetch(PDO::FETCH_ASSOC);
                                    ?> 
                                    <th colspan="6" style="text-align:right">Total Paid Up Capital: <?php echo $rowPayments['total_puc']; ?></th>
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
  //include('includes/scripts.php');
  include('includes/footer.php');
  ?>
        <!-- Custom scripts -->
        <script>
            // JQuery confirmation
            $('.confirmation').on('click', function () {
                return confirm('Are you sure you want do delete this Record?');
            });
        </script>

<link rel="stylesheet" type="text/css" href="DataTables/datatables.min.css"/>
<script type="text/javascript" src="DataTables/datatables.min.js"></script> 
<script type="text/javascript">
    $(document).ready(function() {
      $('#users').DataTable();
  } );
  </script>

  <script type="text/javascript">
    $(document).ready(function() {
    $('#puc_table').DataTable( {
        "pagingType": "full_numbers",
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
    } );
} );
  </script>
