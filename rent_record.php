<?php
// Show PHP errors
date_default_timezone_set('Asia/Manila');
error_reporting(0);
include('includes/security.php');
require_once 'classes/rent.php';
require_once 'classes/encryption.php';
$objtEncrypt = new Encryption();
$objtRent = new Rent();

//GET
if(isset($_GET['edit_id'])){
    $rent_id = $_GET['edit_id'];
    $stmt = $objtRent->runQuery("SELECT * FROM rent WHERE rent_id = :rent_id");
    $stmt->execute(array(":rent_id"=> $rent_id));
    $rowRent = $stmt->fetch(PDO::FETCH_ASSOC);
}else{
    $rent_id = null;
    $rowRent = null; 
}

// DELETE RENT 
if(isset($_GET['delete_id'])){
    $rent_id = $_GET['delete_id'];
      if($rent_id != null){
          $query = "SELECT * FROM rent_payment WHERE rent_id='$rent_id'";
          $stmt = $objtRent-> runQuery($query);
          $stmt->execute();
          $count= $stmt->rowCount();
          if($count > 0){
            header('location: rent_record.php?not_deleted');
          }else{
            if($objtRent->delete($rent_id)){
            $objtRent->redirect('rent_record.php?deleted');
        }
          }
        
      }else{
        var_dump($rent_id);
    }
  }
//delete all
if(isset($_GET['delete_all'])){
          $query = "SELECT * FROM rent_payment";
          $stmt = $objtRent-> runQuery($query);
          $stmt->execute();
          $count= $stmt->rowCount();
          if($count > 0){
            header('location: rent_record.php?not_deleted_all');
          }else{
             if($objtRent->delete_all()){
                header('Location: rent_record.php?deleted_all');
            }
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
                <h2 class="m-0 font-weight-bold text-primary">Rental Records
                </h2>
                <div>            
                       <button class="btn btn-sm btn-danger shadow-sm mr-1 confirmation mb-1" id="rental_records" type="submit" name="delete_rent">Delete Record</button>
                       <button type="submit" name="rent_record_pdf" class="btn btn-sm btn-danger shadow-sm mb-1"><i class="fas fa-print"></i> Print/Preview </button>
                       <button type="submit" name="rent_record_excel" class=" btn btn-sm btn-success shadow-sm mb-1"><i class="fas fa-download"></i>Excel </button>
                </div>  
              </div>        
            <div class="card-body">
               <div class="table-responsive text-center">  
               <table  class="cell-border hover display nowrap" id="rent-records" width="100%">
                   <thead class="bg-success">
                   <tr>
                      <th class="text-center"><label for="select-all" class="mr-3 text-white label-select">Check All</label><input class="d-none" type="checkbox" id="select-all"></th>
                       <th>Rent ID</th>
                       <th>Member ID</th>
                       <th>Name</th>
                       <th>Equipment id</th>
                       <th>Serial no.</th>
                       <th>Equipment Name</th>
                       <th>Rent Date</th>
                       <th>Due Date</th>
                       <th>Date Returned</th>
                       <th>Status</th>
                       <th>Amount</th>
                       <th>Penalty</th>
                       <th>Total Amount</th>
                       <th>Paid</th>     
                   </tr>
                   </thead>
                   <?php
                              
                   $query = "SELECT rent.rent_id, member.member_id, member.Lname, member.Fname, member.Mname, equipment.eqp_id ,equipment.serial_no, equipment.eqp_name,equipment.rent_price, rent.rent_date, rent.due_date, rent.date_returned, (TIMESTAMPDIFF(day,rent.rent_date,rent.due_date)*equipment.rent_price) amount,(TIMESTAMPDIFF(hour,rent.due_date,rent.date_returned)*equipment.rent_price/24) as penalty, rent.pay FROM rent INNER JOIN member on member.member_id = rent.member_id INNER JOIN equipment on equipment.eqp_id = rent.eqp_id GROUP BY rent_id DESC ";
                   $stmt = $objtRent-> runQuery($query);
                   $stmt->execute();
                   ?>
                   <tbody>
                        <?php
                          $count= $stmt->rowCount();
                           if($count > 0){
                             while($rowRent = $stmt->fetch(PDO::FETCH_ASSOC)){
                               $rent_date = date_create($rowRent['rent_date']);
                               $due_date = date_create($rowRent['due_date']);
                               $date_returned = date_create($rowRent['date_returned']);
                               
                               if($rowRent['penalty']<=0){
                                  $penalty = 0;      
                                  $total_amount = $rowRent['amount'] + $penalty;
                                  $status = "Ontime";
                                  $color = "text-success";
                               }else{
                                  $penalty = ceil($rowRent['penalty']);                                
                                  $total_amount = $rowRent['amount'] + $penalty;
                                  $status = "Late";
                                  $color = "text-danger";
                               }

                        ?>
                       <tr >
                            <td align="center">
                                <input style="width: 20px; height: 20px;" class="rent_id" type="checkbox" name="no[]" value="<?php echo $rowRent["rent_id"]; ?>">
                            </td> 
                           <td> <?php print($rowRent['rent_id']);?></td>
                           <td><?php print($rowRent['member_id']);?></td>
                           <td><?php print($objtEncrypt->decrypt($rowRent['Lname']).", "); 
                                   print($objtEncrypt->decrypt($rowRent['Fname'])." ");
                                   print($objtEncrypt->decrypt($rowRent['Mname']));?></td>
                           <td><?php print($rowRent['eqp_id']);?></td>
                           <td><?php print($objtEncrypt->decrypt($rowRent['serial_no']));?></td>
                           <td><?php print($objtEncrypt->decrypt($rowRent['eqp_name']));?></td>
                           <td><?php print(date_format($rent_date,'F d, Y h:i A'));?></td>
                           <td><?php print(date_format($due_date,'F d, Y h:i A'));?></td>
                           <td><?php print(date_format($date_returned,'F d, Y h:i A'));?></td>
                           <td class="<?php echo $color; ?>"><?php print($status);?></td>
                           <td><?php print(number_format($rowRent['amount'],2));?></td>
                           <td><?php print(number_format($penalty,2));?></td>
                           <td><?php print(number_format($total_amount,2));?></td>
                           <td><?php print(number_format($rowRent['pay'],2));?></td>
                            
                           <?php }} ?>   
                       </tr>
                   </tbody> 
                  <tfoot>
                  <tr class="mt-2">
                    <?php 
                        $querys = "SELECT sum(pay) as total_paid FROM rent";
                        $stmt = $objtRent->runQuery($querys);
                        $stmt->execute();
                        $rowPayments = $stmt->fetch(PDO::FETCH_ASSOC);
                      ?> 
                      <th colspan="15" style="text-align:right">Total Rent Paid: <?php echo number_format($rowPayments['total_paid'],2); ?></th>
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
    var rent_records = document.getElementById("rental_records");
    rent_records.style.display = "none";
  </script>';
  }
    //include('includes/scripts.php');
    include('includes/footer.php');
  ?>
  <!-- Custom scripts -->
  <script>
      // JQuery confirmation
      $('.confirmation').on('click', function () {
          return confirm('Are you sure you want do delete this Record? This will also delete the record in Payment Transaction');
      });

      $('.confirmationall').on('click', function () {
       return confirm('Are you sure you want do delete all Records?');
        });

        function filter_record() {
        var x = document.getElementById("filter");
        if (x.style.display === "none") {
          x.style.display = "block";
        } else {
          x.style.display = "none";
        }
      }
  </script>

<script type="text/javascript">
  document.getElementById('select-all').onclick = function() {
  var checkboxes = document.getElementsByClassName('rent_id');
  for (var checkbox of checkboxes) {
    checkbox.checked = this.checked;
  }
}
</script>


<link rel="stylesheet" type="text/css" href="DataTables/datatables.min.css"/>
<script type="text/javascript" src="DataTables/datatables.min.js"></script> 
  <script type="text/javascript">
      function filterGlobal () {
        $('#rent-records').DataTable().search(
            $('#global_filter').val(),
            //$('#global_regex').prop('checked'),
            //$('#global_smart').prop('checked')
        ).draw();
    }
    
    function filterColumn ( i ) {
        $('#rent-records').DataTable().column( i ).search(
            $('#col'+i+'_filter').val(),
            //$('#col'+i+'_regex').prop('checked'),
           // $('#col'+i+'_smart').prop('checked')
        ).draw();
    }
    
    $(document).ready(function() {
        $('#rent-records').DataTable({
        "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
        "pagingType": "full_numbers"
    });
    
        $('input.global_filter').on( 'keyup click', function () {
            filterGlobal();
        } );
    
        $('input.column_filter').on( 'keyup click', function () {
            filterColumn( $(this).parents('tr').attr('data-column') );
        } );
    } );
  </script>
