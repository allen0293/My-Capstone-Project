<?php
// Show PHP errors
date_default_timezone_set('Asia/Manila');
ini_set('display_errors',0);
ini_set('display_startup_erros',0);
include('includes/security.php');
require_once 'classes/user.php';
require_once 'classes/encryption.php';
$objtEncrypt = new Encryption();
$objtUser = new User();


// DELETE Loan
if(isset($_GET['delete_id'])){
    $loan_id= $_GET['delete_id'];
    $query = "SELECT * from loan WHERE loan_id = :loan_id";
    $stmt = $objtUser-> runQuery($query);
    $stmt->execute(array(":loan_id"=> $loan_id));
    $rowLoan = $stmt->fetch(PDO::FETCH_ASSOC);
    try{
      if($loan_id != null){
        if($objtUser->delete_loan($loan_id)){
            $user_id = $_SESSION['user_id'];
            $url = $_SERVER['REQUEST_URI'];
            $date_time = date("Y-m-d H:i:s");
            $memberid=$rowLoan['member_id'];
            $action = "Deleted a Pending loan ID $loan_id of member ID $memberid";
            $objtUser->user_activity($user_id, $action, $url, $date_time);
            $_SESSION['loan_deleted']="deleted";
        }
      }else{
        var_dump($rent_id);
      }
    }catch(PDOException $e){
      echo $e->getMessage();
    }
  }

?>
<?php
include('includes/header.php'); 
include('includes/navbar.php');
//hide admin
if($_SESSION['user_type']!="admin"){
      echo '<script>
      var x = document.getElementById("Admin-hide");
      x.style.display = "none";
    </script>';
} 
?>
<style>
.fas, .far{cursor: pointer;}
</style>
        <?php 
            if(!empty($_SESSION['loan_success'])){
                echo '<script>
                 swal("Loan Success", "", "success");
                </script>';
                unset($_SESSION['loan_success']);
            }
            if(!empty($_SESSION['loan_deleted'])){
                echo '<script>
                 swal("Deleted Success", "", "success");
                </script>';
                unset($_SESSION['loan_deleted']);
            }
            if(!empty($_SESSION['loan_payment_success'])){
                echo '<script>
                 swal("Payment Success", "", "success");
                </script>';
                unset($_SESSION['loan_payment_success']);
            }
        ?>
    <div class="container-fluid">
            <div class="card shadow mb-4">
                <div class="card-header bg-white py-3">
                <h2 class="m-0 font-weight-bold text-primary">Loan</h2>
                    <a href="loan_records.php" class=" btn btn-sm btn-primary shadow-sm mb-1"><i class="fas fa-file-invoice"></i> Loan Records </a>                     
                        <!--a href="export.php?rent_pdf" target="_blank" class=" btn btn-sm btn-danger shadow-sm mb-1"><i class="fas fa-print"></i> Print/Preview</a>
                        <a href="export_excel.php?rent" target="_blank" class="btn btn-sm btn-success shadow-sm mb-1"> <i class="fas fa-download"></i> Excel</a-->    
                </div>
            <div class="card-body">
                <div class="table-responsive">
                
                <table class="cell-border hover display nowrap" id="loan-table" width="100%" cellspacing="0">
                    <thead class="bg-success">
                    <tr>
                        <th>Action</th>
                        <th>Loan ID</th>
                        <th>Member ID</th>
                        <th>Name</th>
                        <th>Loan Amount</th>
                        <th>Date Start</th>
                        <th>Due Date</th>
                        <th>Interest</th>
                        <th>Penalty</th>
                        <th>Amount Receivable</th>
                        <th>Process By</th>
                    </tr>
                    </thead>
                    <?php
                    $query = "SELECT loan.loan_id, member.member_id, member.Lname, member.Fname, member.Mname, loan.based, loan.date_start, loan.due_date, loan.date_finish, loan.interest, loan.penalty, loan.total, register.username FROM loan INNER JOIN member ON member.member_id = loan.member_id INNER JOIN register ON register.user_id = loan.user_id WHERE loan.based > loan.total";
                    $stmt = $objtUser-> runQuery($query);
                    $stmt->execute();
                    ?>
                    <tbody>
                        <?php
                        $count= $stmt->rowCount();
                            if($count > 0){
                            while($rowLoan = $stmt->fetch(PDO::FETCH_ASSOC)){
                            $date_start = date_create($rowLoan['date_start']);
                            $due_date = date_create($rowLoan['due_date']);
                            $interest = $rowLoan['based'] * 0.05;
                            $receivable = $interest + $rowLoan['based'];
                        ?>
                        <tr>
                            <td>     
                                <a href="pay_loan.php?pay=<?php print($rowLoan['loan_id']);?>"> <abbr title="Pay Loan"><i class="far fa-credit-card" style="font-size: 24px"></i></abbr></a> 
                                <a class="confirmation" href="loan.php?delete_id=<?php print($rowLoan['loan_id']);?>"><abbr title="Delete"><i class="far fa-trash-alt" style="font-size: 24px"></i></abbr></a>
                                <a href="export.php?loan_form=<?php echo $rowLoan['loan_id'];?>" target="_blank" class="text-warning"><abbr title="Print Loan Assessment"><i class="fas fa-print" style="font-size: 24px"></i></abbr></a>    
                            </td>
                            <td><?php print($rowLoan['loan_id']);?></td>
                            <td><?php print($rowLoan['member_id']);?></td>
                            <td><?php print($objtEncrypt->decrypt($rowLoan['Lname']).", "); 
                                    print($objtEncrypt->decrypt($rowLoan['Fname']));
                                    print($objtEncrypt->decrypt($rowLoan['Mname']));?></td>
                            <td><?php print(number_format($rowLoan['based'],2));?></td>
                            <td><?php print(date_format($date_start,'F d, Y '));?></td>
                            <td><?php print(date_format($due_date,'F d, Y '));?></td>
                            <td><?php print(number_format($interest,2));?></td>
                            <td><?php print(number_format($rowLoan['penalty'],2));?></td>
                            <td><?php print(number_format($receivable,2));?></td>
                            <td><?php print($rowLoan['username']);?></td>
                        </tr>
                        
                        <?php } } ?>
                    </tbody>
                        <tfoot>
                            <tr class="mt-2">
                             <?php 
                                $querys = "SELECT  sum((based)+(based*0.05)) as total_receivable FROM loan WHERE based > total";
                                $stmts = $objtUser->runQuery($querys);
                                $stmts->execute();
                                $rowLoans = $stmts->fetch(PDO::FETCH_ASSOC);
                            ?> 
                            <th colspan="11" style="text-align:right">Total Loan Receivable: <?php echo number_format($rowLoans['total_receivable'],2); ?></th>
                            </tr> 
                    </tfoot> 
                                    
                </table>
                </div>
            </div>
                </div>
            </div>  
            </div>      
<!-- Footer scripts, and functions -->
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
    </body>
</html>


<link rel="stylesheet" type="text/css" href="DataTables/datatables.min.css"/>
<script type="text/javascript" src="DataTables/datatables.min.js"></script> 
  <script type="text/javascript">
    $(document).ready(function() {
    $('#loan-table').DataTable( {
        "scrollX": true,
        "pagingType": "full_numbers",
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
    } );
} );
  </script>
