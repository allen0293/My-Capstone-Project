<?php
// Show PHP errors
date_default_timezone_set('Asia/Manila');
error_reporting(0);
include('includes/security.php');
require_once 'classes/encryption.php';
require_once 'classes/user.php';
$objtEncrypt = new Encryption();
$objtLoan = new User();

// GET
if(isset($_GET['pay'])){
    $loan_id = $_GET['pay'];
    $date_pay = date('Y-m-d');

    $stmt = $objtLoan->runQuery("SELECT loan.loan_id, member.member_id, member.Lname, member.Fname, member.Mname, loan.based, loan.date_start FROM loan INNER JOIN member ON member.member_id = loan.member_id WHERE loan.based > loan.total AND loan_id = :loan_id");
    $stmt->execute(array(":loan_id"=> $loan_id));
    $rowLoan = $stmt->fetch(PDO::FETCH_ASSOC);

    $date1=date_create($rowLoan['date_start']);
    $date2=date_create($date_pay);
    $diff=date_diff($date1,$date2);
    $number_of_days=$diff->format("%a");
    $interest = $rowLoan['based'] * 0.05;
    $total_amount = $interest + $rowLoan['based'];
   if($number_of_days%120 == 0 && !($number_of_days/120 >1)){
         // same day payment not more 120days
        $interest = $rowLoan['based'] * 0.05;
        $total_amount = $interest + $rowLoan['based'];
        $penalty = 0;
    }else if($number_of_days%120 == 0 || $number_of_days%120 > 0){//payment is more than 120 days
      if($number_of_days%120 > 0){//remainder days
      $counter = $number_of_days/120;
      $counters = (int)($counter);
      }else{
      $counter = ($number_of_days/120)-1;//same day payment morethan 120days
      $counters = (int)($counter);
      }
  }

  for($i =0 ; $i<$counters ; $i++){      
      $penalty = $penalty+($total_amount*0.01);
      $total_amount = $total_amount+$penalty;

  }

        
}


// POST
if(isset($_POST['pay'])){
  
    $loan_id = $_POST['loan_id'];
    $member_id = $_POST['member_id'];
    $name = $_POST['name'];
    $loan_amount = $_POST['loan_amount'];
    $penalty = $_POST['penalty'];
    $interest = $_POST['interest'];
    $amount_to_pay = $_POST['total_amount'];
    $user_id = $_SESSION['user_id'];
    $date_pay = date("Y-m-d H:i:s");
    $pay = $_POST['paid'];
    
        if($pay != $amount_to_pay){
            $_SESSION['invalid']="invalid input";
        }
        else{
           if($objtLoan->update_loan($interest, $penalty,$amount_to_pay, $loan_id)){
                $user_id = $_SESSION['user_id'];
                $url = $_SERVER['REQUEST_URI'];
                $date_time = date("Y-m-d H:i:s");
                $action = "Processed Loan payment of member ID $member_id Loan ID $loan_id ";
                $objtLoan->user_activity($user_id, $action, $url, $date_time); 
                $_SESSION['loan_payment_success']="success";
                header("Location:loan.php");
           }else{
               $_SESSION['error']="error";

           }
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
 <?php 
    if(!empty($_SESSION['invalid'])){
        echo '<script>
            swal("The amount you entered is not equal to the amount to pay", "", "warning");
        </script>';
        unset($_SESSION['invalid']);
    }
    if(!empty($_SESSION['error'])){
        echo '<script>
            swal("Database Error", "", "error");
        </script>';
        unset($_SESSION['error']);
    }
 ?>
        <div class="container-fluid">
            <div class="container shadow" style="background-color: white">
                <h1 style="margin-top: 10px" class="text-primary">Loan Payment</h1>
                    <form method="post">
                        <div class="container">
                            <div class="form-group d-none">
                                <label  for="rent_id">Loan ID</label>
                                <input class="form-control" type="text" name="loan_id" value="<?php print($rowLoan['loan_id']);?>" readonly>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group ">
                                        <label for="member_id">Member ID </label>
                                        <input  class="form-control" type="text" name="member_id" id="member_id" placeholder="" value="<?php print($rowLoan['member_id']);?>" readonly>
                                    </div>
                                     </div>  
                                <div class="col">
                                    <div class="form-group ">
                                        <label for="name">Name </label>
                                        <input  class="form-control" type="text" name="name" id="name" placeholder="" value="<?php print($objtEncrypt->decrypt($rowLoan['Lname']).", "); 
                                           print($objtEncrypt->decrypt($rowLoan['Fname'])." ");
                                           print($objtEncrypt->decrypt($rowLoan['Mname']));?>" readonly>
                                    </div>
                                </div> 
                              </div>  
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                            <label for="rent_date">Loan Amount </label>
                                            <input  class="form-control" type="text" name="loan_amount" id="loan_amount" placeholder="" value="<?php echo $rowLoan['based'];?>" readonly >
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                        
                                 <div class="col">
                                    <div class="form-group">
                                            <label for="balance">Interest </label>
                                            <input  class="form-control" type="text" name="interest" id="interest" placeholder="" value=" <?php print ($interest);?>" readonly>
                                    </div>
                                </div>   


                                <div class="col">
                                    <div class="form-group">
                                            <label for="balance">Penalty</label>
                                            <input  class="form-control" type="text" name="penalty" id="penalty" placeholder="" value=" <?php print($penalty);?>" readonly>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="form-group">
                                            <label for="pay">Total Amount</label>
                                            <input  class="form-control" type="text" name="total_amount" id="total_amount" placeholder="" value=" <?php print($total_amount);?>" readonly>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                            <label for="paid">Payment </label>
                                            <input  class="form-control" type="text" name="paid" id="loan_s" placeholder="" value="" required >
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <a href="loan.php" type="button" class="btn btn-danger">Cancel</a>
                                <button type="submit" name="pay" class="btn btn-primary">Save</button>
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
var input = document.getElementById("loan_s");

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
  if (typedChar == "-" && this.value == "") {
    return;
  }
  
  // In all other cases, suppress the event
  return false;
};
</script>