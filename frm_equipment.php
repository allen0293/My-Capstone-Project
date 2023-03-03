<?php
// Show PHP errors
date_default_timezone_set('Asia/Manila');
error_reporting(0);
include('includes/security.php');
require_once 'classes/user.php';
require_once 'classes/equipment.php';
require_once 'classes/encryption.php';
$objtEncrypt = new Encryption();
$objtEqp = new Equipment();
$objtUser = new User();

// GET
if(isset($_GET['edit_id'])){
    $eqp_id = $_GET['edit_id'];
    $stmt = $objtEqp->runQuery("SELECT * FROM equipment WHERE eqp_id = :eqp_id");
    $stmt->execute(array(":eqp_id"=> $eqp_id));
    $rowEqp = $stmt->fetch(PDO::FETCH_ASSOC);
}else{
    $eqp_id = null;
    $rowEqp = null; 
}

// POST
if(isset($_POST['btn_save'])){
    //adding the member
    $serial_no = strip_tags($_POST['serial_no']);
    $serial_no = $objtEncrypt->encrypt($serial_no);

    $eqp_name = strip_tags($_POST['eqp_name']);
    $eqp_name = $objtEncrypt->encrypt($eqp_name);

    $eqp_model = strip_tags($_POST['eqp_model']);
    $eqp_model = $objtEncrypt->encrypt($eqp_model);

    $eqp_desc = strip_tags($_POST['eqp_desc']);
    $eqp_desc = $objtEncrypt->encrypt($eqp_desc);
    $rent_price = strip_tags($_POST['rent_price']);
    $status  = strip_tags($_POST['status']);

    try{
        if($eqp_id != null){
            if($objtEqp->update($serial_no, $eqp_name, $eqp_model, $eqp_desc, $rent_price,$eqp_id)){
                 $user_id = $_SESSION['user_id'];
                 $url = $_SERVER['REQUEST_URI'];
                 $date_time = date("Y-m-d H:i:s");
                 $action = "Updated Equipment details";
                 $objtUser->user_activity($user_id, $action, $url, $date_time);  

                $objtEqp->redirect('display_equipment.php?updated');
              }
        }else {
            if($objtEqp->insert($serial_no, $eqp_name, $eqp_model, $eqp_desc, $rent_price, $status)){
                 $user_id = $_SESSION['user_id'];
                 $url = $_SERVER['REQUEST_URI'];
                 $date_time = date("Y-m-d H:i:s");
                 $action = "Added new Equipment";
                 $objtUser->user_activity($user_id, $action, $url, $date_time);  

                $objtEqp->redirect('display_equipment.php?inserted');
            }else{
                $objtEqp->redirect('display_equipment.php?error');
            } 
        }
    }catch(PDOException $e){
        echo $e->getMessage();
    }
}

?>
<?php
include('includes/header.php'); 
include('includes/navbar.php'); 
//hide button Admin
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
                <div class="container shadow " style="background-color: white">
                <h1 style="margin-top: 10px" class="m-0 font-weight-bold text-primary">Equipment Form</h1>
                    <form method="post">
                        <!--div class="form-group">
                            <label for="eqp_id">ID</label>
                            <input class="form-control" type="text" name="eqp_id" id="eqp_id" value="<?php print($rowEqp['eqp_id']); ?>" readonly>
                        </div-->

                        <div class="form-group mt-2">
                                <label for="serial_no">Serial Number </label>
                                <input  class="form-control" type="text" name=serial_no id="serial_no" placeholder="e.g. PHZ19090210" value="<?php print($objtEncrypt->decrypt($rowEqp['serial_no'])); ?>" required maxlength="16">
                         </div>

                         <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="eqp_name">Equipment Name </label>
                                        <input  class="form-control" type="text" name="eqp_name" id="eqp_name" placeholder="e.g. Hand Tractor" value="<?php print($objtEncrypt->decrypt($rowEqp['eqp_name'])); ?>" required maxlength="100">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="eqp_model">Equipment Model </label>
                                        <input  class="form-control" type="text" name="eqp_model" id="eqp_model" placeholder="e.g. RD80N" value="<?php print($objtEncrypt->decrypt($rowEqp['eqp_model'])); ?>" required maxlength="100">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="eqp_desc">Description </label>
                                        <input  class="form-control" type="text" name="eqp_desc" id="eqp_desc" placeholder="e.g. Tractor" value="<?php print($objtEncrypt->decrypt($rowEqp['eqp_desc'])); ?>" required maxlength="100">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="rent_price">Rental Price </label>
                                        <input  class="form-control" type="text" name="rent_price" id="rent_price" placeholder="e.g. 100" value="<?php print($rowEqp['rent_price']); ?>" required maxlength="100">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <input  class="form-control" type="hidden" name="status" id="status" value="unoccupied"    >
                            </div>
                            <div class="modal-footer">
                                <a href="display_equipment.php" type="button" class="btn btn-danger">Cancel</a>
                                <button type="submit" name="btn_save" class="btn btn-primary">Save</button>
                            </div>
                            
                     </div>
                    </form>
            </div>
        </div>
    </body>
</html>
