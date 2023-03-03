<?php
// Show PHP errors
date_default_timezone_set('Asia/Manila');
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(0);
include('includes/security.php');
require_once 'classes/rent.php';
require_once 'classes/payment.php';
require_once 'classes/encryption.php';
require_once 'classes/user.php';
require_once 'classes/equipment.php';
$objtEqp = new Equipment();
$objtEncrypt = new Encryption();
$objtPayment = new Payment();
$objtRent = new Rent();
$objtUser = new User();

// GET
if(isset($_GET['pay'])){
    $rent_id = $_GET['pay'];
    $date_returned =  date("Y-m-d H:i:s");

    $stmt_r = $objtRent->runQuery("SELECT * FROM rent where rent_id = :rent_id");
    $stmt_r->execute(array(":rent_id"=> $rent_id));
    $rowReturn = $stmt_r->fetch(PDO::FETCH_ASSOC);
    $check_date_return = $rowReturn['date_returned'];

    if($check_date_return == null){
        $objtRent->update_date_returned($date_returned,$rent_id);
    }

           $stmt = $objtRent->runQuery("SELECT rent.rent_id, member.member_id,member.Lname, member.Fname, member.Mname, equipment.eqp_id, equipment.eqp_name, equipment.serial_no, rent.rent_date,rent.due_date,rent.date_returned,TIMESTAMPDIFF(hour,rent.due_date,rent.date_returned)*(equipment.rent_price/24) as penalty,rent.amount, rent.amount-rent.pay as balance, rent.pay from rent INNER JOIN member on member.member_id = rent.member_id INNER JOIN equipment on equipment.eqp_id = rent.eqp_id WHERE rent.amount <> rent.pay AND rent_id = :rent_id");
            $stmt->execute(array(":rent_id"=> $rent_id));
            $rowRent = $stmt->fetch(PDO::FETCH_ASSOC);     

            if($rowRent['penalty'] <= 0){
                $penalty = 0;
                $total_amount = $rowRent['amount'] + $penalty;
                $balance = $total_amount - $rowRent['pay'];

            }else{
                if($rowRent['pay']>0){
                    $penalty = 0;
                    $total_amount = $rowRent['amount'] + $penalty;
                    $balance = $total_amount - $rowRent['pay'];               
                }else{
                    $penalty = $rowRent['penalty'];
                    $total_amount = $rowRent['amount']+$penalty;
                    $balance = $total_amount - $rowRent['pay'];
                    $objtRent->update_amount($total_amount,$rent_id);
                }
            }                         
}
$rent_datex = date_create($rowRent['rent_date']);
    $due_datex = date_create($rowRent['due_date']);
    $date_returned =date_create(($rowRent['date_returned']));

// POST
if(isset($_POST['btn_save'])){
  
    $serial =  strip_tags($_POST['serial_no']);
    $rent_id = strip_tags($_POST['rent_id']);
    $eqp_id = strip_tags($_POST['eqp_id']);
    $amount = strip_tags($_POST['total_amount']);
    $name = strip_tags($_POST['name']);
    $serial_no = strip_tags($_POST['serial_no']);
    $paid = strip_tags($_POST['paid']);
    $eqp_name = strip_tags($_POST['eqp_name']);
    $rent_date = strip_tags($_POST['rent_date']);
    $due_date = strip_tags($_POST['due_date']);
    $balance = strip_tags($_POST['balance']);
    $user_id = $_SESSION['user_id'];
    date_default_timezone_set('Asia/Manila');
    $date_pay = date("Y-m-d H:i:s");
    $member_id = $_POST['member_id'];
    try{
        if($rent_id != null){
            if($paid > $balance){
                 echo '<script> alert("You paid too much") ;
                 window.location="pay_rent.php?pay='.$rent_id.'";
                 </script>
                 ';   
            }else{     
                if($paid<0){
                    $objtPayment->insert($rent_id,$amount,$paid,$date_pay,$user_id);
                    $objtRent->update($paid,$rent_id);
                    $user_id = $_SESSION['user_id'];
                    $url = $_SERVER['REQUEST_URI'];
                    $date_time = date("Y-m-d H:i:s");
                    $action = "Deducted the rental payment of member ID $member_id. Rent ID $rent_id";
                    $objtUser->user_activity($user_id, $action, $url, $date_time);  
                    $_SESSION['rent_reduced']=$member_id;
                    $objtRent->redirect('frm_rent.php'); 
                }else{
                    if($objtPayment->insert($rent_id,$amount,$paid,$date_pay,$user_id)){                                   
                    }if($objtRent->update($paid,$rent_id)){
                           if($balance == $paid){
                               $objtEqp->status_unoccupied($eqp_id);   
                          }
                          $user_id = $_SESSION['user_id'];
                          $url = $_SERVER['REQUEST_URI'];
                          $date_time = date("Y-m-d H:i:s");
                          $action = "Processed rental payment of member ID $member_id. Rent ID $rent_id";
                          $objtUser->user_activity($user_id, $action, $url, $date_time);  
                          $_SESSION['rent_payment_success']="success";
                          $objtRent->redirect('frm_rent.php');               
                   }
                }
                
            }
          }else {   
            $objtRent->redirect('frm_rent.php?error');
          }
    }catch(PDOException $e){
        echo $e->getMessage();
    }
}

?>



<?php
include('includes/header.php'); 
include('includes/navbar.php'); 
//Hide Admin
if($_SESSION['user_type']!="admin"){
      echo '<script>
      var x = document.getElementById("Admin-hide");
      x.style.display = "none";
    </script>';
}

 ?>
<html lang="en">

    <body>    
        <div class="container-fluid">
            <div class="container shadow" style="background-color: white">
                <h1 style="margin-top: 10px" class="text-primary">Rental Payment</h1>
                    <form action="pay_rent.php" method="post">
                        <div class="container">
                            <div class="form-group ">
                                <label  for="rent_id">RENT ID</label>
                                <input class="form-control" type="text" name="rent_id" id="rent_id" value="<?php print($rowRent['rent_id']);?>" readonly>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group ">
                                        <label for="member_id">Member ID </label>
                                        <input  class="form-control" type="text" name="member_id" id="member_id" placeholder="" value="<?php print($rowRent['member_id']);?>" readonly>
                                    </div>
                                     </div>  
                                <div class="col">
                                    <div class="form-group ">
                                        <label for="name">Name </label>
                                        <input  class="form-control" type="text" name="name" id="name" placeholder="" value="<?php print($objtEncrypt->decrypt($rowRent['Lname']).", "); 
                                           print($objtEncrypt->decrypt($rowRent['Fname'])." ");
                                           print($objtEncrypt->decrypt($rowRent['Mname']));?>" readonly>
                                    </div>
                                </div> 
                              </div>  
                                <div class="row">
                                <div class="col">
                                    <div class="form-group ">
                                        <label for="eqp_id">Equipment ID </label>
                                        <input  class="form-control" type="text" name="eqp_id" id="eqp_id" placeholder="" value="<?php print($rowRent['eqp_id']);?>" readonly>
                                    </div> 
                                </div> 

                                <div class="col">
                                    <div class="form-group ">
                                        <label for="serial_no">Serial number </label>
                                        <input  class="form-control" type="text" name="serial_no" id="serial_no" placeholder="" value="<?php print($objtEncrypt->decrypt($rowRent['serial_no']));?>" readonly>
                                    </div> 
                                </div> 

                                <div class="col">
                                    <div class="form-group ">
                                        <label for="eqp_name">Equipment Name </label>
                                        <input  class="form-control" type="text" name="eqp_name" id="eqp_name" placeholder="" value="<?php print($objtEncrypt->decrypt($rowRent['eqp_name']));?>" readonly>
                                    </div> 
                                </div> 
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                            <label for="rent_date">Rent Date </label>
                                            <input  class="form-control" type="text" name="rent_date" id="rent_date" placeholder="" value="<?php print(date_format($rent_datex,'F d Y, h:i A'));?>" readonly >
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                            <label for="birthdate">Due Date </label>
                                            <input  class="form-control" type="text" name="due_date" id="due_date" placeholder="" value=" <?php print(date_format($due_datex,'F d Y, h:i A'));?>" readonly>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                            <label for="birthdate">Date Returned </label>
                                            <input  class="form-control" type="text" name="due_date" id="due_date" placeholder="" value=" <?php print(date_format($date_returned,'F d Y, h:i A'));?>" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                            
                                 <div class="col">
                                    <div class="form-group">
                                            <label for="amount">Amount </label>
                                            <input  class="form-control" type="text" name="amount" id="amount" placeholder="" value=" <?php print($rowRent['amount']);?>" readonly>
                                    </div>
                                </div>

                                 <div class="col">
                                    <div class="form-group">
                                            <label for="balance">Penalty </label>
                                            <input  class="form-control" type="text" name="penalty" id="penalty" placeholder="" value=" <?php print($penalty);?>" readonly>
                                    </div>
                                </div>   


                                <div class="col">
                                    <div class="form-group">
                                            <label for="balance">Total amount to pay </label>
                                            <input  class="form-control" type="text" name="total_amount" id="total_amount" placeholder="" value=" <?php print($total_amount);?>" readonly>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="form-group">
                                            <label for="pay">Paid</label>
                                            <input  class="form-control" type="text" name="pay" id="pay" placeholder="" value=" <?php print($rowRent['pay']);?>" readonly>
                                    </div>
                                </div>


                                <div class="col">
                                    <div class="form-group">
                                            <label for="balance">Balance </label>
                                            <input  class="form-control" type="text" name="balance" id="balance" placeholder="" 
                                            value=" <?php print($balance);?>" readonly>
                                    </div>
                                </div>
                                
                                

                                <div class="col">
                                    <div class="form-group">
                                            <label for="paid">Payment </label>
                                            <input  class="form-control" type="text" name="paid" id="rent_paid" placeholder="" value="" required >
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <a href="frm_rent.php" type="button" class="btn btn-danger">Cancel</a>
                                <button type="submit" name="btn_save" class="btn btn-primary">Save</button>
                            </div>  
                      </div>
                    </form>    
                   </div>   
                </div>   
                <br>
        </div>
        <!-- Footer scripts, and functions -->
   <?php
    //include('includes/scripts.php');
    include('includes/footer.php');
    ?>
 <script>
var input = document.getElementById("rent_paid");

input.onkeypress = function(e) {
  e = e || window.event;
  var charCode = (typeof e.which == "number") ? e.which : e.keyCode;

  // Allow non-printable keys
  if (!charCode || charCode == 8 /* Backspace */ ) {
    return;
  }

  var typedChar = String.fromCharCode(charCode);

  // Allow numeric characters
  if (/\d/.test(typedChar)) {
    return;
  }

  // Allow the minus sign (-) if the user enters it first
  if (typedChar == "" && this.value == "") {
    return;
  }
  if (typedChar == "." ) {
    return;
  }
  
  // In all other cases, suppress the event
  return false;
};

 </script>