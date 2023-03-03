<?php
// Show PHP errors
date_default_timezone_set('Asia/Manila');
error_reporting(0);
include('includes/security.php');
require_once 'classes/user.php';
require_once 'classes/encryption.php';
$objtEncrypt = new Encryption();
$objtLoan = new User();
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
          if(isset($_GET['deleted'])){
            echo '<script>
             swal("Record Deleted", "", "success");
        </script>';
          }else if(isset($_GET['error'])){
                echo '<script>
                    swal("Database Error, Something went wrong", "", "error");
               </script>';
             }else if(isset($_GET['no_data'])){
              echo '<script>
                     swal("No data Selected", "", "error");
                </script>';
             }
            ?>
          </div>        
         <form action="export_delete.php" method="post"> 
              <div class="container-fluid">
                 <div class="card shadow mb-4">
              <div class="card-header bg-white py-3">
                <h2 class="m-0 font-weight-bold text-primary">Loan Records
                </h2>
                <div>            
                       <button class="btn btn-sm btn-danger shadow-sm mr-1 confirmation mb-1" id="loan_records" type="submit" name="delete_loan">Delete Record</button>
                       <button type="submit" name="loan_record_pdf" class="btn btn-sm btn-danger shadow-sm mb-1"><i class="fas fa-print"></i> Print/Preview </button>
                       <button type="submit" name="loan_record_excel" class=" btn btn-sm btn-success shadow-sm mb-1"><i class="fas fa-download"></i>Excel </button>
                </div>  
              </div>        
            <div class="card-body">
               <div class="table-responsive text-center">   
               <table  class="cell-border hover display nowrap" id="loan-records" width="100%">
                   <thead>
                   <tr style="background-color: #2E8B57 !important">
                      <th class="text-center"><label for="select-all" class="mr-3 text-white label-select">Check All</label><input class="d-none" type="checkbox" id="select-all"></th>
                        <th></th>
                        <th>Loan ID</th>
                        <th>Member ID</th>
                        <th>Name</th>
                        <th>Loan Amount</th>
                        <th>Date Start</th>
                        <th>Due Date</th>
                        <th>Date Pay</th>
                        <th>Status</th>
                        <th>Interest</th>
                        <th>Penalty</th>
                        <th>Total</th>
                        <th>Process By</th>  
                   </tr>
                   </thead>
                   <?php
                              
                   $query = "SELECT loan.loan_id, member.member_id, member.Lname, member.Fname, member.Mname, loan.based, loan.date_start, loan.due_date, loan.date_finish, loan.interest, loan.penalty, loan.total, register.username FROM loan INNER JOIN member ON member.member_id = loan.member_id INNER JOIN register ON register.user_id = loan.user_id WHERE total <> 0  GROUP BY loan_id DESC ";
                   $stmt = $objtLoan-> runQuery($query);
                   $stmt->execute();
                   ?>
                   <tbody>
                        <?php
                          $count= $stmt->rowCount();
                           if($count > 0){
                             while($rowLoan = $stmt->fetch(PDO::FETCH_ASSOC)){
                               $date_start = date_create($rowLoan['date_start']);
                               $date_finish = date_create($rowLoan['date_finish']);
                               $due_date = date_create($rowLoan['due_date']);
                               $date1=date_create($rowLoan['date_start']);
                               $date2=date_create($rowLoan['date_finish']);
                               $diff=date_diff($date1,$date2);
                               $number_of_days=$diff->format("%a");

                               if($number_of_days<120){
                                  $status = "Ontime";
                                  $color = "text-success";
                               }else{
                                  $status = "Late";
                                  $color = "text-danger";
                               }
                        ?>
                       <tr >
                            <td align="center">
                                <input style="width: 20px; height: 20px;" class="loan_id" type="checkbox" name="no[]" value="<?php echo $rowLoan["loan_id"]; ?>">
                            </td> 
                            <td>
                              <abbr title="Print Loan Receipt">
                                <a href="export.php?loan_receipt=<?php echo $rowLoan['loan_id'];?>" target="_blank" class="text-warning"> 
                                <i class="fas fa-print" style="font-size: 25px"></i></a>
                              </abbr>
                            </td>
                           <td> <?php print($rowLoan['loan_id']);?></td>
                           <td><?php print($rowLoan['member_id']);?></td>
                           <td><?php print($objtEncrypt->decrypt($rowLoan['Lname']).", "); 
                                   print($objtEncrypt->decrypt($rowLoan['Fname'])." ");
                                   print($objtEncrypt->decrypt($rowLoan['Mname']));?></td>
                           <td><?php print($rowLoan['based']);?></td>
                           <td><?php print(date_format($date_start,'F d, Y'));?></td>
                           <td><?php print(date_format($due_date,'F d, Y'));?></td>
                           <td><?php print(date_format($date_finish,'F d, Y'));?></td>
                           <td class="<?php echo $color; ?>"><?php print($status);?></td>
                           <td><?php print(number_format($rowLoan['interest'],2));?></td>
                           <td><?php print(number_format($rowLoan['penalty'],2));?></td>
                           <td><?php print(number_format($rowLoan['total'],2));?></td>
                           <td><?php print($rowLoan['username']);?></td>
                            
                           <?php }} ?>   
                       </tr>
                   </tbody> 
                  <tfoot>
                  <tr class="mt-2">
                     <?php 
                        $querys = "SELECT sum(total) as total FROM loan";
                        $stmt = $objtLoan->runQuery($querys);
                        $stmt->execute();
                        $rowLoan = $stmt->fetch(PDO::FETCH_ASSOC);
                      ?> 
                      <th colspan="14" style="text-align:right">Total Loan: <?php echo number_format($rowLoan['total'],2); ?></th>
                    </tr> 
              </tfoot> 
              </table>
        </form>     
               </div>
             </div>
           </div>
        </div>
        <br>   
</div>
  
        <!-- Footer scripts, and functions -->
 <?php
 if($_SESSION['user_type']!="admin"){
  echo '<script>
  var x = document.getElementById("Admin-hide");
  x.style.display = "none";
  var loan_record = document.getElementById("loan_records");
  loan_record.style.display = "none";
</script>';
}
    //include('includes/scripts.php');
    //include('includes/footer.php');
  ?>
  <!-- Custom scripts -->
  <script>
      // JQuery confirmation
      $('.confirmation').on('click', function () {
          return confirm('Are you sure you want do delete this Record?');
      });

      $('.confirmationall').on('click', function () {
       return confirm('Are you sure you want do delete all Records?');
        });

  </script>

<script type="text/javascript">
  document.getElementById('select-all').onclick = function() {
  var checkboxes = document.getElementsByClassName('loan_id');
  for (var checkbox of checkboxes) {
    checkbox.checked = this.checked;
  }
}
</script>
<link rel="stylesheet" type="text/css" href="DataTables/datatables.min.css"/>
<script type="text/javascript" src="DataTables/datatables.min.js"></script> 
  <script type="text/javascript">  
     $(document).ready(function() {
    $('#loan-records').DataTable( {
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "pagingType": "full_numbers"
    } );
} );
  </script>
