<?php
// Show PHP errors
date_default_timezone_set('Asia/Manila');
ini_set('display_errors',0);
ini_set('display_startup_erros',0);
include('includes/security.php');
require_once 'classes/equipment.php';
require_once 'classes/rent.php';
require_once 'classes/encryption.php';
require_once 'classes/user.php';
$objtUser = new User();
$objtEncrypt = new Encryption();
$objtRent = new Rent();
$objtEqp = new Equipment();

// DELETE RENT 
if(isset($_GET['delete_id'])){
    $rent_id = $_GET['delete_id'];
    $stmt = $objtRent->runQuery("SELECT * FROM rent WHERE rent_id = :rent_id");
    $stmt->execute(array(":rent_id"=> $rent_id));
    $rowRent = $stmt->fetch(PDO::FETCH_ASSOC);
    try{
      if($rent_id != null){
        if($objtRent->delete($rent_id)){
          $objtEqp->status_unoccupied($rowRent['eqp_id']);
          $user_id = $_SESSION['user_id'];
          $url = $_SERVER['REQUEST_URI'];
          $date_time = date("Y-m-d H:i:s");
          $memberid = $rowRent['member_id'];
          $action = "Deleted a Pending Rent ID $rent_id of member ID $memberid";
          $objtUser->user_activity($user_id, $action, $url, $date_time);
          $objtRent->redirect('frm_rent.php?deleted');
        }
      }else{
        var_dump($rent_id);
      }
    }catch(PDOException $e){
      echo $e->getMessage();
    }
  }

// GET
if(isset($_GET['edit_id'])){
    $rent_id = $_GET['edit_id'];
    $stmt = $objtRent->runQuery("SELECT * FROM rent WHERE rent_id = :rent_id");
    $stmt->execute(array(":rent_id"=> $rent_id));
    $rowRent = $stmt->fetch(PDO::FETCH_ASSOC);
}else{
    $rent_id = null;
    $rowRent = null; 
}

//GEt member ID
if(isset($_GET['get_id'])){
    $member_id = $_GET['get_id'];
    $stmt = $objtRent->runQuery("SELECT * FROM member WHERE member_id = :member_id");
    $stmt->execute(array(":member_id"=> $member_id));
    $rowM = $stmt->fetch(PDO::FETCH_ASSOC);
}else{
    $rent_id = null;
    $rowM = null; 
}

// POST
if(isset($_POST['btn_save'])){  
    //adding the member
    $eqp_id = strip_tags($_POST['eqp_id']);
    $eqp_id = preg_replace('/[^0-9]/', '', $eqp_id);
    $member_id = strip_tags($_POST['member_id']);
    $rent_date = strip_tags($_POST['rent_date']);
    $due_date = strip_tags($_POST['due_date']);

    $stmt = $objtRent->runQuery("SELECT * FROM equipment WHERE eqp_id = :eqp_id");
    $stmt->execute(array(":eqp_id"=> $eqp_id));
    $rowEqp = $stmt->fetch(PDO::FETCH_ASSOC);
    $rent_datex = date_create($_POST['rent_date']);
    $due_datex = date_create($_POST['due_date']);
    $diff = date_diff($rent_datex,$due_datex);
    $num = (int)$diff->format('%a');
    $amount = $rowEqp['rent_price'] * $num;
     try{
        if($rent_id != null){
            if($objtRent->update($pay,$rent_id)){
              //$objtRent->redirect('frm_rent.php?updated');
            }
          }else {
              if($objtRent->insert($eqp_id,$member_id,$rent_date,$due_date,$amount)){
                $user_id = $_SESSION['user_id'];
                $url = $_SERVER['REQUEST_URI'];
                $date_time = date("Y-m-d H:i:s");
                $memberid = $rowRent['member_id'];
                $action = "Processed equipment rental  of member ID $member_id";
                $objtUser->user_activity($user_id, $action, $url, $date_time);
                  $objtEqp->status_occupied($eqp_id);
                  $objtRent->redirect('frm_rent.php');
              }else{
                  $objtRent->redirect('frm_rent.php?error');
              }
              
          }
    }catch(PDOException $e){
        echo $e->getMessage();
    }
}
//SEARCH MEMBER
if(isset($_POST['search'])){
    $member_id = $_POST['m_id'];
    $stmt = $objtRent->runQuery("SELECT  member_id, TIN, Lname, Fname, Mname FROM member WHERE member_id = :member_id");
    $stmt->execute(array(":member_id"=> $member_id));
    $rowMember = $stmt->fetch(PDO::FETCH_ASSOC);
}else{
    $member_id = null;
    $rowMember = null; 
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
      <div class="container-fluid">
        <div class="container shadow" style="background-color: white">
          <div class="container">
                <h2 style="margin-top: 10px" class="m-0 font-weight-bold text-primary">Rental Details</h2>                
                        <form action="frm_rent.php" method="post">
                             <!--SEARCH BAR -->   
                                <div class="form-group">
                                  <div class="input-group">
                                    <!--label for="eqp_name">Search Member</label-->
                                    <input  class="form-control mt-3" type="text" name="m_id" id="search" placeholder="Search Member ID" value="" required="">
                                   <button class="btn btn-primary mt-3" type="submit" name="search">
                                        <i class="fas fa-search fa-sm"></i>
                                      </button>
                                  </div>
                               </div>                               
                        </form>                  
                     <form action="frm_rent.php" method="post">   
                            <div class="form-group d-none ">
                                <label  for="rent_id">RENT ID</label>
                                <input class="form-control" type="text" name="rent_id" id="rent_id" value="" readonly>
                            </div>                    
                            <div class="form-group d-none">
                                <label for="member_id">Member ID </label>
                                <input  class="form-control" type="text" name="member_id" id="member_id" placeholder="" value="<?php print($rowMember['member_id']);
                                   print($rowM['member_id']);?>" readonly>
                            </div>                               
                            <div class="row">                           
                                <div class="col">
                                    <div class="form-group ">
                                        <label for="name">Name </label>
                                        <input  class="form-control" type="text" name="name" id="name" placeholder="" value="<?php print($objtEncrypt->decrypt($rowMember['Lname'])." "); 
                                           print($objtEncrypt->decrypt($rowMember['Fname'])." ");
                                           print($objtEncrypt->decrypt($rowMember['Mname']));

                                           print($objtEncrypt->decrypt($rowM['Lname'])." "); 
                                           print($objtEncrypt->decrypt($rowM['Fname'])." ");
                                           print($objtEncrypt->decrypt($rowM['Mname']));  ?>" readonly required="">
                                    </div>
                                </div>                   
                            <div class="col">
                                <div class="form-group ">
                                    <label for="eqp_id">Equipment </label>
                                    <select class="custom-select" type="text" name="eqp_id" required="">
                                            <?php
                                                $query = "SELECT * FROM equipment where status ='unoccupied' ";
                                                $stmt = $objtRent-> runQuery($query);
                                                $stmt->execute();
                                            ?>
                                            <?php 
                                                $count= $stmt->rowCount();
                                                if($count > 0){
                                                while($rowEqp = $stmt->fetch(PDO::FETCH_ASSOC)){
                                            ?>   
                                                 <option><?php print("ID"); print($rowEqp['eqp_id']); print(": ");
                                                 print($objtEncrypt->decrypt($rowEqp['eqp_name']));?></option>    
                                                <?php }} ?> 
                                    </select>   
                                </div> 
                            </div> 
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                            <label for="rent_date">Rent Date </label>
                                            <input  class="form-control" type="datetime-local" name="rent_date" id="rent_date" placeholder="<?php print($rowRent['rent_date']);?>" value="" required>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                            <label for="birthdate">Return Date </label>
                                            <input  class="form-control" type="datetime-local" name="due_date" id="due_date" placeholder="" value="<?php print($rowRent['due_date']);?>" required>
                                    </div>
                                </div>
                            </div>
                            <input class="btn btn-primary mb-2" type="submit" name="btn_save" value="Save">
                      </div>
                    </form>      
                    </div>      
               </div>
               <!--Table part -->
               <div class="container-fluid">
                <hr>
                    <?php
                      if(isset($_GET['error'])){
                        echo '<script> swal("Invalid input, no name added", "", "error"); </script>';
                      }else if(isset($_GET['deleted'])){
                        echo '<script> swal("Rent Deleted", "", "success"); </script>';
                      }

                      if(!empty($_SESSION['rent_reduced'])){
                         echo '<script> swal("An amount is  deducted from the rental payment of member ID '.$_SESSION['rent_reduced'].'", "", "info"); </script>';
                         unset($_SESSION['rent_reduced']);
                      }

                      if(!empty($_SESSION['rent_payment_success'])){
                         echo '<script> swal("Payment Success", "", "success"); </script>';
                         unset($_SESSION['rent_payment_success']);
                      }
                    ?>
                    <div class="card shadow mb-4">
                      <div class="card-header bg-white py-3">
                        <h2 class="m-0 font-weight-bold text-primary">Rent</h2>
                          <a href="payment_record.php" class=" btn btn-sm btn-primary shadow-sm mb-1"><i class="far fa-credit-card"></i> Payment Transaction </a>                     
                               <!--a href="export.php?rent_pdf" target="_blank" class=" btn btn-sm btn-danger shadow-sm mb-1"><i class="fas fa-print"></i> Print/Preview</a>
                               <a href="export_excel.php?rent" target="_blank" class="btn btn-sm btn-success shadow-sm mb-1"> <i class="fas fa-download"></i> Excel</a-->    
                      </div>
                    <div class="card-body">
                     <div class="table-responsive">
                       
                       <table class="cell-border hover display nowrap" id="rent-table" width="100%" cellspacing="0">
                           <thead class="bg-success">
                           <tr>
                              <th>Action</th>
                               <th>Rent ID</th>
                               <th>Member ID</th>
                               <th>Name</th>
                               <th>Equipment id</th>
                               <th>Serial no.</th>
                               <th>Equipment Name</th>
                               <th>Rent Date</th>
                               <th>Return Date</th>
                               <th>Amount</th>
                               <th>Paid</th>
                               <th>Balance</th>
                              <!--th class="text-center">Status</th-->  
                           </tr>
                           </thead>
                           <?php
                           $query = "SELECT rent.rent_id, member.member_id, member.Lname, member.Fname, member.Mname, equipment.eqp_id, equipment.serial_no, equipment.eqp_name, rent.rent_date, rent.due_date, rent.amount, rent.pay, rent.amount-rent.pay as Balance FROM rent INNER JOIN member on member.member_id = rent.member_id INNER JOIN equipment on equipment.eqp_id = rent.eqp_id Where rent.amount <> rent.pay";
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
                                ?>
                               <tr>
                                 <td>  
                                    <a href="pay_rent.php?pay=<?php print($rowRent['rent_id']);?>" style="font-size: 24px"> <abbr title="Pay rent"><i class="far fa-credit-card"></i></abbr></a>
                                    <a class="confirmation" href="frm_rent.php?delete_id=<?php print($rowRent['rent_id']);?>" style="font-size: 24px"><abbr title="Delete"><i class="far fa-trash-alt"></i></abbr></a> 
                                    <a href="export.php?rent_form=<?php echo $rowRent['rent_id'];?>" target="_blank" class="text-warning"><abbr title="Print Rental Assessment"><i class="fas fa-print" style="font-size: 25px"></i></abbr></a>
                                  </td>
                                   <td><?php print($rowRent['rent_id']);?></td>
                                   <td><?php print($rowRent['member_id']);?></td>
                                   <td><?php print($objtEncrypt->decrypt($rowRent['Lname']).", "); 
                                            print($objtEncrypt->decrypt($rowRent['Fname']));
                                            print($objtEncrypt->decrypt($rowRent['Mname']));?></td>
                                   <td><?php print($rowRent['eqp_id']);?></td>
                                   <td><?php print($objtEncrypt->decrypt($rowRent['serial_no']));?></td>
                                   <td><?php print($objtEncrypt->decrypt($rowRent['eqp_name']));?></td>
                                   <td><?php print(date_format($rent_date,'M d, Y h:i A'));?></td>
                                   <td><?php print(date_format($due_date,'M d, Y h:i A'));?></td>
                                   <td><?php print(number_format($rowRent['amount'],2));?></td>
                                   <td><?php print(number_format($rowRent['pay'],2));?></td>
                                   <td><?php print(number_format($rowRent['Balance'],2))?></td>
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
    //include('includes/scripts.php');
    include('includes/footer.php');
?>
        <!-- Custom scripts -->
        <script>
            // JQuery confirmation
            $('.confirmation').on('click', function () {
                return confirm('Are you sure you want do delete this rent record?');
            });   
        </script>
    </body>
</html>


<link rel="stylesheet" type="text/css" href="DataTables/datatables.min.css"/>
<script type="text/javascript" src="DataTables/datatables.min.js"></script> 
  <script type="text/javascript">
    $(document).ready(function() {
    $('#rent-table').DataTable( {
        "scrollX": true,
        "pagingType": "full_numbers",
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
    } );
} );
  </script>