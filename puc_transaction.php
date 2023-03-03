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
    $puc_id = $_GET['delete_id'];
    try{
      if($puc_id != null){
        if($objtPayment->delete_puc($puc_id)){
          $objtPayment->redirect('puc_transaction.php?deleted');
        }
      }else{
        var_dump($puc_id);
      }
    }catch(PDOException $e){
      echo $e->getMessage();
    }
  }

//delete all
if(isset($_GET['delete_all'])){
      if($objtPayment->delete_all_puc()){
          header('Location: puc_transaction.php?deleted_all');
        }else{
          echo '<script>alert("DB error")</script>';
        }
  }
?>
<?php
include('includes/header.php'); 
include('includes/navbar.php'); 
//hide Admin
if($_SESSION['user_type']!="admin"){
      echo '<script>
      var x = document.getElementById("Admin-hide");
      x.style.display = "none";
    </script>';
}
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
           if(isset($_GET['deleted'])){
            echo '<script>
                 swal("Record Deleted", "", "success");
            </script>';
          }else if(isset($_GET['no_puc_data'])){
                  echo '<script>
                     swal("No data Selected", "", "error");
                </script>';
            }
        ?>
        </div>
        <div class="alert alert-info alert-dismissable fade show" role="alert" style="display: none" id="deleteds">
            <strong>All Transactions</strong> are deleted with success.
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true"> &times; </span>
              </button>
        </div>
        <div class="container-fluid">
        <form action="export_delete.php" method="post">
        <div class="card shadow mb-4">
          <div class="card-header bg-white py-3">
            <h2 class="m-0 font-weight-bold text-primary">Capital Build Up Records</h2>
            <div class="mt-2">
              <input class=" btn btn-sm btn-danger shadow-sm mr-1 confirmation mb-1" id="puc_transactions" type="submit" name="delete_puc" value="Delete Record">
                   <button type="submit" name="paid_up_capital_pdf" class=" btn btn-sm btn-danger shadow-sm mb-1"><i class="fas fa-print"></i> Print/Preview </button>
                   <button type="submit" name="paid_up_capital_excel" class=" btn btn-sm btn-success shadow-sm mb-1"><i class="fas fa-download"></i> Excel </button>
            </div>
          </div>
        <div class="card-body">
        <div class="table-responsive">  
            <table class="cell-border hover display nowrap" id="puc-records">
                <thead style="background-color: #2E8B57 !important">
                <tr>
                    <th class="text-center"><label for="select-all-puc" class="mr-3 text-white label-select">Check All</label><input class="d-none" type="checkbox" id="select-all-puc"></th>
                    <th></th>
                    <th>Transaction ID</th>
                    <th>Member ID</th>
                    <th>Name</th>
                    <th>Paid</th>
                    <th>Date Paid</th>
                    <th>Process By</th>                    
                </tr>
                </thead>
                <?php                         
                $query = "SELECT puc_transaction.puc_id, member.member_id, member.Lname, member.Fname, member.Mname, puc_transaction.paid, puc_transaction.date_pay, register.username FROM puc_transaction INNER JOIN member ON member.member_id = puc_transaction.member_id INNER JOIN register ON register.user_id =  puc_transaction.user_id ORDER BY puc_transaction.puc_id DESC ";
                $stmt = $objtPayment-> runQuery($query);
                $stmt->execute();
                ?>
                <tbody>
                     <?php
                     $count= $stmt->rowCount();
                      if($count > 0){
                        while($rowPayment = $stmt->fetch(PDO::FETCH_ASSOC)){
                          $date = date_create($rowPayment['date_pay']);
                     ?>
                    <tr>
                        <td class="text-center">
                              <input style="width: 20px; height: 20px;" class="puc_id" type="checkbox" name="no[]" value="<?php echo $rowPayment["puc_id"]; ?>">
                        </td>
                        <td>
                          <abbr title="Print Capital Build up Receipt">
                            <a href="export.php?puc_receipt=<?php echo $rowPayment['puc_id'];?>" target="_blank" class="text-warning"> 
                            <i class="fas fa-print" style="font-size: 25px"></i></a>
                          </abbr>
                        </td>
                        <td><?php print($rowPayment['puc_id']);?></td>
                        <td><?php print($rowPayment['member_id']);?></td>
                        <td><?php print($objtEncrypt->decrypt($rowPayment['Lname']).", "); 
                               print($objtEncrypt->decrypt($rowPayment['Fname'])." ");
                               print($objtEncrypt->decrypt($rowPayment['Mname']));?></td>
                        <td><?php print(number_format($rowPayment['paid'],2));?></td>
                        <td><?php print(date_format($date,'M d, Y h:i A'));?></td>    
                        <td><?php print($rowPayment['username']);?></td>                                                   
                         <?php }} ?>
                    </tr>
                </tbody>   
            </table>
      </form>
          </div>
        </div>
      </div>
        </div>                 
</div>
</div>
<!-- Footer scripts, and functions -->
 <?php
  if($_SESSION['user_type']!="admin"){
    echo '<script>
    var x = document.getElementById("Admin-hide");
    x.style.display = "none";
    var puc_transaction = document.getElementById("puc_transactions");
    puc_transaction.style.display = "none";
  </script>';
  }
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

   function filter_puc() {
    var x = document.getElementById("filter");
    if (x.style.display === "none") {
      x.style.display = "block";
    } else {
      x.style.display = "none";
    }
  }
</script>
<!-- Cehchbox JS-->
<script type="text/javascript">
  document.getElementById('select-all-puc').onclick = function() {
  var checkboxes = document.getElementsByClassName('puc_id');
  for (var checkbox of checkboxes) {
    checkbox.checked = this.checked;
  }
}
</script>

<link rel="stylesheet" type="text/css" href="DataTables/datatables.min.css"/>
<script type="text/javascript" src="DataTables/datatables.min.js"></script> 

  <script type="text/javascript">
    $(document).ready(function() {
    $('#puc-records').DataTable( {
        "pagingType": "full_numbers",
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
    } );
} );


  </script>
