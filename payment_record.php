<?php
// Show PHP errors
date_default_timezone_set('Asia/Manila');
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);
include('includes/security.php');
require_once 'classes/payment.php';
require_once 'classes/encryption.php';
$objtEncrypt = new Encryption();
$objtPayment = new Payment();
// GET
if(isset($_GET['delete_id'])){
    $pay_id = $_GET['delete_id'];
    try{
      if($pay_id != null){
        if($objtPayment->delete($pay_id)){
          $objtPayment->redirect('payment_record.php?deleted');
        }
      }else{
        var_dump($pay_id);
      }
    }catch(PDOException $e){
      echo $e->getMessage();
    }
  }

//delete all
if(isset($_GET['delete_all'])){
      if($objtPayment->delete_all()){
          header('Location: payment_record.php?deleted_all');
        }else{
          echo '<script>alert("DB error")</script>';
        }
  }

?> 
<?php
include('includes/header.php'); 
include('includes/navbar.php'); 
//hide Admin
?>

<style type="text/css">
      .label-select{
        display: block;
      cursor:pointer;
    }
      .label-select:hover{
        opacity: 0.5;
      }
    </style>
<div class="container-fluid">
           <div class="container-fluid">
            <?php
              if(isset($_GET['deleted_all'])){
                echo '<script>
                       swal("Record Delted", "", "success");
                  </script>';
              }else if(isset($_GET['deleted'])){
                echo '<script>
                     swal("Record Deleted", "", "success");
                </script>';
              }else if(isset($_GET['no_payment_data'])){
                  echo '<script>
                       swal("No data Selected", "", "error");
                  </script>';
                }
            ?>
            </div>
            <div class="container-fluid">
              <form action="export_delete.php"  method="post">  
                 <div class="card shadow mb-4">
              <div class="card-header bg-white py-3">
                <h2 class="m-0 font-weight-bold text-primary">Rental Payment Transactions</h2>
                <div class="mt-1">
                      
                       <button class="btn btn-sm btn-danger shadow-sm confirmation mb-1" id="delete_rent_payment" type="submit" name="delete_payment">Delete Record</button>
                       <button type="submit" name="rental_transaction_pdf" class="btn btn-sm btn-danger shadow-sm mb-1"><i class="fas fa-print"></i> Print/Preview </button>
                       <button type="submit" name="rental_transaction_excel" class="btn btn-sm btn-success shadow-sm mb-1"><i class="fas fa-download"></i> Excel </button> 
                </div>
              </div>
            <div class="card-body">
            <div class="table-responsive">
               
                <table  class="display nowrap cell-border hover" id="payment-records">
                    <thead style="background-color: #2E8B57 !important">
                    <tr>
                        <th class="text-center"><label for="select-all-payment" class="mr-3 text-white label-select">Check All</label><input class="d-none" type="checkbox" id="select-all-payment"></th>
                        <th></th>
                        <th>Payment ID</th>
                        <th>Member ID</th>
                        <th>Name</th>
                        <th>Equipment ID</th>
                        <th>Serial no.</th>
                        <th>Equipment Name</th>
                        <th>Rent Date</th>
                        <th>Due Date</th>
                        <th>Date Returned</th>
                        <th>Status</th>
                        <th>Total Amount</th>
                        <th>Paid</th>
                        <th>Date Paid</th>
                        <th>Process by</th>                   
                    </tr>
                    </thead>
                    <?php
                              
                          $query = "SELECT rent_payment.pay_id,rent.rent_id, member.Lname,member.Fname,member.Mname, member.member_id,equipment.eqp_id,equipment.eqp_name, equipment.serial_no, rent.rent_date, rent.due_date, rent.date_returned, rent_payment.amount, rent_payment.paid, rent_payment.date_pay,(TIMESTAMPDIFF(hour,rent.due_date,rent.date_returned)*equipment.rent_price/24) as penalty, register.username from rent_payment LEFT JOIN rent ON rent.rent_id = rent_payment.rent_id LEFT JOIN member on member.member_id = rent.member_id LEFT JOIN equipment on equipment.eqp_id = rent.eqp_id LEFT JOIN register ON register.user_id = rent_payment.user_id ORDER BY rent_payment.pay_id DESC";
                    $stmt = $objtPayment-> runQuery($query);
                    $stmt->execute();
                    ?>
                    <tbody>
                         <?php
                         $count= $stmt->rowCount();
                          if($count > 0){
                            while($rowPayment = $stmt->fetch(PDO::FETCH_ASSOC)){      
                                $date_pay = date_create($rowPayment['date_pay']);
                                $rent_date = date_create($rowPayment['rent_date']);
                                $due_date = date_create($rowPayment['due_date']);
                                $date_returned = date_create($rowPayment['date_returned']);

                                if($rowPayment['penalty']<=0){
                                  $penalty = 0;      
                                  $total_amount = $rowPayment['amount'] + $penalty;
                                  $status = "Ontime";
                                  $color = "text-success";
                               }else{
                                  $penalty = ceil($rowPayment['penalty']);                                
                                  $total_amount = $rowPayment['amount'] + $penalty;
                                  $status = "Late";
                                  $color = "text-danger";
                               }
                         ?>
                        <tr>
                            <td class="text-center">
                              <abbr title="Delete">
                                <input style="width: 20px; height: 20px;" class="pay_id" type="checkbox" name="no[]" value="<?php echo $rowPayment["pay_id"]; ?>">
                                </abbr>
                            </td>
                            <td>
                              <abbr title="Print Rental Receipt">
                                <a href="export.php?receipt=<?php echo $rowPayment['pay_id'];?>" target="_blank" class="text-warning"> 
                                <i class="fas fa-print" style="font-size: 25px"></i></a>
                              </abbr>
                            </td>
                            <td><?php print($rowPayment['pay_id']);?></td>
                            <td><?php print($rowPayment['member_id']);?></td>
                            <td><?php print($objtEncrypt->decrypt($rowPayment['Lname']).", "); 
                                   print($objtEncrypt->decrypt($rowPayment['Fname'])." ");
                                   print($objtEncrypt->decrypt($rowPayment['Mname']));?></td>
                                   <td><?php print($rowPayment['eqp_id']);?></td>
                            <td><?php print($objtEncrypt->decrypt($rowPayment['serial_no']));?></td>
                            <td><?php print($objtEncrypt->decrypt($rowPayment['eqp_name']));?></td>     
                            <td><?php print(date_format($rent_date,'F d, Y h:i A'));?></td>
                            <td><?php print(date_format($due_date,'F d, Y h:i A'));?></td>
                            <td><?php print(date_format($date_returned,'F d, Y h:i A'));?></td>
                            <td class="<?php echo $color; ?>"><?php print($status);?></td>
                            <td><?php print(number_format($rowPayment['amount'],2));?></td>
                            <td><?php print(number_format($rowPayment['paid'],2));?></td>
                            <td><?php print(date_format($date_pay,'F d, Y h:i A'));?></td>      
                            <td><?php print($rowPayment['username']);?></td>                          
                              <?php }} ?>
                        </tr>
                    </tbody>
                          </tr>
                        </tbody>        
                        <tfoot>
                        <tr>
                          <?php 
                                $stmt = "SELECT sum(paid) as total_paid FROM rent_payment";
                                $stmt = $objtPayment->runQuery($stmt);
                                $stmt->execute();
                                $rowPayments = $stmt->fetch(PDO::FETCH_ASSOC);
                              ?> 
                            <th colspan="4" style="text-align:right">Total Rent Paid: <?php echo number_format($rowPayments['total_paid'],2); ?></th>
                        </tr>
                    </tfoot> 
                </table>
      </form>
              </div>
            </div>
          </div>
            </div>                 
    </div>
</div>
<?php 
  if($_SESSION['user_type']!="admin"){
    echo '<script>
    var x = document.getElementById("Admin-hide");
    x.style.display = "none";
    var rent_payment = document.getElementById("delete_rent_payment");
    rent_payment.style.display = "none";
  </script>';
}
?>
<!-- Footer scripts, and functions -->
 <?php
    //include('includes/scripts.php');
    include('includes/footer.php');
  ?>

<!-- Custom scripts -->
<script>
    // JQuery confirmation
    $('.confirmation').on('click', function () {
        return confirm('Are you sure you want do delete this Transaction?');
    });
    $('.confirmationall').on('click', function () {
     return confirm('Are you sure you want do delete all Payment Transactions?');
      });

    function filter_payment() {
      var x = document.getElementById("filter");
      if (x.style.display === "none") {
        x.style.display = "block";
      } else {
        x.style.display = "none";
      }
    }
</script>
 
 <script type="text/javascript">
  document.getElementById('select-all-payment').onclick = function() {
  var checkboxes = document.getElementsByClassName('pay_id');
  for (var checkbox of checkboxes) {
    checkbox.checked = this.checked;
  }
}
</script>

<link rel="stylesheet" type="text/css" href="DataTables/datatables.min.css"/>
<script type="text/javascript" src="DataTables/datatables.min.js"></script> 
  <script type="text/javascript">
    $(document).ready(function() {
    $('#payment-records').DataTable( {
        "scrollX": true,
        "pagingType": "full_numbers",
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
    } );
} );
  </script>