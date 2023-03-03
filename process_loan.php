<?php
// Show PHP errors
date_default_timezone_set('Asia/Manila');
error_reporting(E_ALL);
include('includes/security.php');

require_once 'classes/user.php';
require_once 'classes/encryption.php';
$objtEncrypt = new Encryption();
$objtUser =  new User();



//GET
if(isset($_GET['loan'])){
    $member_id = $_GET['loan'];
    $member_stmt = $objtUser->runQuery("SELECT member_id, Lname, Fname, Mname, capital_build_up, paid_up_capital  FROM member WHERE member_id = :member_id AND capital_build_up = paid_up_capital");
    $member_stmt->execute(array(":member_id"=> $member_id));
    $count= $member_stmt->rowCount();
    
    if($count > 0){
        $stmt = $objtUser->runQuery("SELECT * FROM loan WHERE member_id = :member_id AND (based+penalty+interest)<> total");
        $stmt->execute(array(":member_id"=> $member_id));
        $counts= $stmt->rowCount();
       if($counts>0){
        $_SESSION['pending_loan'] = $member_id;
        header("Location: member.php");
       }else{
        $rowUser = $member_stmt->fetch(PDO::FETCH_ASSOC);
        $maximum = $rowUser['capital_build_up'] * 5;
       }
    }else{
        $_SESSION['not_full_paid'] = $member_id;
        header("Location: member.php");
    }
}else{
    $member_id = null;
    $rowUser = null; 
}
// POST
if(isset($_POST['btn_save'])){
    $user_id = $_SESSION['user_id'];
    $loan_amount = $_POST['loan'];
    $date_pay = date("Y-m-d H:i:s");
    $loan_amount = preg_replace('/[,]/', '', $loan_amount);
    $loan_amount  = floatval($loan_amount);
if($loan_amount > $maximum ){
        $_SESSION['maximum']="error";
    }else {
        if($objtUser->insert_loan($member_id,$loan_amount,$user_id)){
            $url = $_SERVER['REQUEST_URI'];
            $date_time = date("Y-m-d H:i:s");
            $action = "Processed Loan of member ID $member_id";
            $objtUser->user_activity($user_id, $action, $url, $date_time); 
            $objtUser->update_due_date();
            $_SESSION['loan_success']="success";
            header("Location:loan.php");
        }else{
            $_SESSION['db_error']="error";
        }
    }

}



include('includes/header.php'); 
include('includes/navbar.php');
if($_SESSION['user_type']!="admin"){
    echo '<script>
    var x = document.getElementById("Admin-hide");
    x.style.display = "none";
  </script>';
}

if(!empty($_SESSION['maximum'])){
    echo '<script>
        swal("Your inputed amount is greater than your maximum loanable amount", "", "warning");
    </script>';
    unset($_SESSION['maximum']);
}

if(!empty($_SESSION['db_error'])){
    echo '<script>
    swal("Database Error, Something went wrong", "", "error");
    </script>';
    unset($_SESSION['db_error']);
}


?> 
        <div class="container-fluid">
            <div class="container shadow" style="background-color: white; padding: 20px;">
                <h1 style="margin-top: 10px padding-top:20px" class="text-primary">Loan Form</h1>
                    <form method="post">
                        <div class="container">
                                    <div class="form-group">
                                        <label for="member_id">Member ID </label>
                                        <input  class="form-control" type="text" name="member_id" id="member_id" placeholder="" value="<?php print($rowUser['member_id']);?>" readonly>
                                    </div>
                                    <div class="form-group ">
                                        <label for="name">Name </label>
                                        <input  class="form-control" type="text" name="name" id="name" placeholder="" value="<?php print($objtEncrypt->decrypt($rowUser['Lname']).", "); 
                                           print($objtEncrypt->decrypt($rowUser['Fname'])." ");
                                           print($objtEncrypt->decrypt($rowUser['Mname']));?>" readonly>
                                    </div>        
                                    <div class="form-group ">
                                        <label for="cbu">Capital Build Up </label>
                                        <input  class="form-control" type="text" name="cbu" id="cbu" placeholder="" value="<?php print(number_format($rowUser['capital_build_up'],2));?>" readonly>
                                    </div> 
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group ">
                                                <label>Maximum amount to loan: <strong><?php echo number_format($maximum,2);?></strong> </label>
                                                <input  class="form-control number" type="text" name="loan" id="loan" placeholder="e.g. 20000" required="">
                                             </div>
                                        </div>
                                    </div>                                 
                            <div class="modal-footer">
                                <a href="member.php" type="button" class="btn btn-danger">Cancel</a>
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
var input = document.getElementById("loan");

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
  if (this.value == "") {
    return;
  }

  // In all other cases, suppress the event
  return false;
};
</script>