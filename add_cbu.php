<?php
// Show PHP errors
date_default_timezone_set('Asia/Manila');
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
include('includes/security.php');
require_once 'classes/user.php';
require_once 'classes/encryption.php';
$objtEncrypt = new Encryption();
$objtUser =  new User();

//GET
if(isset($_GET['add_id'])){
    $member_id = $_GET['add_id'];
    $stmt = $objtUser->runQuery("SELECT * FROM loan WHERE member_id = :member_id AND (based+penalty+interest)<> total");
    $stmt->execute(array(":member_id"=> $member_id));
    $count= $stmt->rowCount();
    if($count>0){
        $_SESSION['pending_loan_cbu'] = $member_id;
        header("Location: member.php");
    }else{
        $stmt = $objtUser->runQuery("SELECT member_id, Lname, Fname, Mname, capital_build_up, paid_up_capital  FROM member WHERE member_id = :member_id");
        $stmt->execute(array(":member_id"=> $member_id));
        $rowUser = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
}else{
    $member_id = null;
    $rowUser = null; 
}
// POST
if(isset($_POST['btn_save'])){
    $member_id = $_POST['member_id'];
    $cbu = $_POST['add_cbu'];
    $user_id = $_SESSION['user_id'];
    $url = $_SERVER['REQUEST_URI'];
    $date_time = date("Y-m-d H:i:s");
    try{
        if($member_id != null){
          if($cbu<0){
                $objtUser->update_cbu($cbu,$member_id);    
                $action = "Reduced Capital build of member ID $member_id";
                $objtUser->user_activity($user_id, $action, $url, $date_time); 
                $_SESSION['cbu_subtract']=$member_id;
                header('Location:member.php');   
          }else{
                $objtUser->update_cbu($cbu,$member_id);
                $action = "Add Capital build up amount on member ID $member_id";
                $objtUser->user_activity($user_id, $action, $url, $date_time); 
                $_SESSION['cbu_added']="added";
                header('Location:member.php');
          }
      } 
    }catch(PDOException $e){
        echo $e->getMessage();
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
?>

<html lang="en">
        <?php 
          if(isset($_GET['error'])){
            echo '<script>
             swal("Database Error, Something went wrong", "", "Error");
            </script>';
          }else
        ?> 
        <div class="container-fluid">
            <div class="container shadow" style="background-color: white; padding: 20px;">
                <h1 style="margin-top: 10px padding-top:20px" class="text-primary">Add Capital Build Up</h1>
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
                                <div class="row">
                                <div class="col">
                                    <div class="form-group ">
                                        <label for="eqp_id">Current Capital Build UP</label>
                                        <input  class="form-control" type="text" name="cbu" id="cbu" placeholder="" value="<?php print(number_format($rowUser['capital_build_up'],2));?>" readonly>
                                    </div> 
                                </div> 
                                <div class="col">
                                    <div class="form-group ">
                                        <label for="eqp_id">Paid Up Capital </label>
                                        <input  class="form-control" type="text" name="puc" id="puc" placeholder="" value="<?php print(number_format($rowUser['paid_up_capital'],2));?>" readonly>
                                    </div> 
                                </div> 

                                <div class="col">
                                    <div class="form-group ">
                                        <label for="eqp_id">Amount you want to add </label>
                                        <input  class="form-control" type="text" name="add_cbu" id="add_cbu" placeholder="" value="" required>
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
   // include('includes/scripts.php');
    include('includes/footer.php');
    ?>
<script>
var input = document.getElementById("add_cbu");

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
  // Allow the minus sign (-) if the user enters it first
  if (typedChar == "." ) {
    return;
  }
  // In all other cases, suppress the event
  return false;
};
</script>
