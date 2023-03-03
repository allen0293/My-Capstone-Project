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
$disabled='';
$member_id = null;
// GET
if(isset($_GET['edit_id'])){
    $member_id = $_GET['edit_id'];
    $stmt = $objtUser->runQuery("SELECT * FROM member WHERE member_id = :member_id");
    $stmt->execute(array(":member_id"=> $member_id));
    $rowUser = $stmt->fetch(PDO::FETCH_ASSOC);
    $count = $stmt->rowCount();

    $disabled = $count > 0 ? "disabled='disabled'" : "";
}else{
    $member_id = null;
    $rowUser = null; 
}

// POST
if(isset($_POST['btn_save'])){
    //adding the member
    $TIN = strip_tags($_POST['TIN']);
    $TIN = $objtEncrypt->encrypt($TIN);

    $Lname = strip_tags($_POST['Lname']);
    $Lname = $objtEncrypt->encrypt($Lname);

    $Fname = strip_tags($_POST['Fname']);
    $Fname = $objtEncrypt->encrypt($Fname);

    $Mname = strip_tags($_POST['Mname']);
    $Mname = $objtEncrypt->encrypt($Mname);

    $birthdate = strip_tags($_POST['birthdate']);
    $birthdate = $objtEncrypt->encrypt($birthdate);

    $spouse_name = strip_tags($_POST['spouse_name']);
    $spouse_name = $objtEncrypt->encrypt($spouse_name);

    $address = strip_tags($_POST['address']);
    $address = $objtEncrypt->encrypt($address);

    $contactno = strip_tags($_POST['contactno']);
    $contactno = $objtEncrypt->encrypt($contactno);

    $land_location = strip_tags($_POST['land_location']);
    $land_location =  $objtEncrypt->encrypt($land_location);

    $land_size = strip_tags($_POST['land_size']);
    $land_size = $objtEncrypt->encrypt($land_size);

    $crop1 = strip_tags($_POST['crop1']);
    $crop1 = $objtEncrypt->encrypt($crop1);

    $crop2 = strip_tags($_POST['crop2']);
    $crop2 = $objtEncrypt->encrypt($crop2);
    
    $crop3 = strip_tags($_POST['crop3']);  
    $crop3 = $objtEncrypt->encrypt($crop3); 

    $capital_build_up = strip_tags($_POST['capital_build_up']);
    $paid_up_capital = strip_tags($_POST['paid_up_capital']);
    $reg_fee = strip_tags($_POST['reg_fee']);
    date_default_timezone_set('Asia/Manila');
    $reg_date = date("Y-m-d H:i:s");
    try{
        if($member_id != null){
          if($objtUser->update($TIN, $Lname, $Fname, $Mname, $birthdate, $spouse_name, $address, $contactno,$land_location, $land_size, $crop1, $crop2, $crop3, $member_id)){

                $user_id = $_SESSION['user_id'];
                $url = $_SERVER['REQUEST_URI'];
                $date_time = date("Y-m-d H:i:s");
                $action = "Updated a member record";
                $objtUser->user_activity($user_id, $action, $url, $date_time);    

                $_SESSION['member_updated']="updated";
                $objtUser->redirect('member.php');
          }
        }else {
            if($objtUser->insert($TIN, $Lname, $Fname, $Mname, $birthdate, $spouse_name, $address, $contactno,$land_location, $land_size, $crop1, $crop2, $crop3, $capital_build_up, $paid_up_capital, $reg_fee, $reg_date)){
                $user_id = $_SESSION['user_id'];
                $url = $_SERVER['REQUEST_URI'];
                $date_time = date("Y-m-d H:i:s");
                $action = "Added new member";
                $objtUser->user_activity($user_id, $action, $url, $date_time);  

                $_SESSION['new_member']="added";
                $objtUser->redirect('member.php');
            }else{
                //$objtUser->redirect('member.php?error');
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
//Hide button Admin
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
                <!-- Sidebar menu -->
                <div class="container shadow" style="background-color: white">      
                    <h1 class="mt-3 pt-3 font-weight-bold text-primary">Member Form</h1>               
                      <hr>
                            <h4>Personal Information</h4>
                         <form  method="post">
                            <div class="form-group">
                                <label for="TIN">TIN</label>
                                <input class="form-control" type="text" name="TIN" id="TIN" placeholder="111-111-111-111" value="<?php print($objtEncrypt->decrypt($rowUser['TIN']));?>" required maxlength="15" data-inputmask="'mask': '999-999-999-999'">
                            </div>

                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="Lname">Last Name </label>
                                        <input  class="form-control" type="text" name="Lname" id="Lname" placeholder="e.g. De La Cruz" value="<?php print($objtEncrypt->decrypt($rowUser['Lname'])); ?>" required maxlength="100">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="Fname">First Name </label>
                                        <input  class="form-control" type="text" name="Fname" id="Fname" placeholder="e.g. Juan" value="<?php print($objtEncrypt->decrypt($rowUser['Fname'])); ?>" required maxlength="100">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="Mname">Middle Name </label>
                                        <input  class="form-control" type="text" name="Mname" id="Mname" placeholder="" value="<?php print($objtEncrypt->decrypt($rowUser['Mname'])); ?>">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="birthdate">Date Of Birth </label>
                                        <input  class="form-control" type="date" name="birthdate" id="birthdate" placeholder="" value="<?php print($objtEncrypt->decrypt($rowUser['birthdate'])); ?>" required>
                                    </div>
                                </div>
                            </div>
                            <div>
                                    <div class="form-group">
                                        <label for="birthdate">Spouse Name</label>
                                        <input  class="form-control" type="text" name="spouse_name" id="spouse_name" placeholder="" value="<?php print($objtEncrypt->decrypt($rowUser['spouse_name'])); ?>">
                                    </div>
                                </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="address">Address </label>
                                        <input  class="form-control" type="text" name="address" id="address" placeholder="e.g sinulatan1st, Camilling Tarlac" value="<?php print($objtEncrypt->decrypt($rowUser['address'])); ?>" required maxlength="100">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="contactno">Contact Number </label>
                                        <input  class="form-control" type="text" name="contactno" id="contactno" placeholder="e.g. 09########" value="<?php print($objtEncrypt->decrypt($rowUser['contactno'])); ?>" required maxlength="11">
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <h4>Farm Information</h4>
                            <div class="form-group">
                                <label for="birthdate">Land Location</label>
                                <input  class="form-control" type="text" name="land_location" id="land_location" placeholder="e.g sinulatan1st, Camilling Tarlac" value="<?php print($objtEncrypt->decrypt($rowUser['land_location'])); ?>" required>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="land_size">Land Size (Hectares) </label>
                                        <input  class="form-control" type="text" name="land_size" id="land_size" placeholder="e.g. 5" value="<?php print($objtEncrypt->decrypt($rowUser['land_size'])); ?>" required>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="crop1">Crop1 </label>
                                                <select class="custom-select" type="text" name="crop1" required="">
                                                    <option><?php print($objtEncrypt->decrypt($rowUser['crop1'])); ?></option>
                                                    <option>Rice</option>
                                                    <option>Corn</option>
                                                    <option>mongo beans</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                            <label for="crop1">Crop2 </label>
                                            <select class="custom-select" type="text" name="crop2" required="">
                                                    <option ><?php print($objtEncrypt->decrypt($rowUser['crop2'])); ?></option>
                                                    <option>Rice</option>
                                                    <option>Corn</option>
                                                    <option>Mongo Beans</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="crop3">Crop3 </label>
                                                <select class="custom-select" type="text" name="crop3" required="">
                                                    <option><?php print($objtEncrypt->decrypt($rowUser['crop3'])); ?></option>
                                                    <option >Rice</option>
                                                    <option>Corn</option>
                                                    <option>Mongo Beans</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>         
                            </div>
                             <div class="row" >
                                <div class="col">
                                    <div class="form-group">
                                        <label for="address">Capital Build Up </label>
                                        <input  class="form-control" type="text" name="capital_build_up" id="capital_build_up" placeholder="e.g. 4000" value="<?php print($rowUser['capital_build_up']); ?>" required <?php echo $disabled; ?>>
                                    </div>
                                </div>
                                <div class="col" id="puc-col">
                                    <div class="form-group">
                                        <label for="contactno">Paid Up Capital </label>
                                        <input  class="form-control" type="text" name="paid_up_capital" id="paid_up_capital" placeholder="" value="<?php print($rowUser['paid_up_capital']); ?>"<?php echo $disabled; ?>>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                        <label for="birthdate" class="cbu" >Registration Fee</label>
                                        <input  class="form-control" type="text" name="reg_fee" id="reg_fee" placeholder="e.g. 500" value="<?php print($rowUser['reg_fee']);?>" required <?php echo $disabled; ?> >
                            </div>
                             <div class="modal-footer">
                                <a href="member.php?view" type="button" class="btn btn-danger">Cancel</a>
                                <button type="submit" name="btn_save" class="btn btn-primary">Save</button>
                            </div>
                    </div>
                  </form>      
                  <br>      
        </div>
        <style type="text/css">
            #puc-col{
                display: none;
            }
        </style>

        <!-- Footer scripts, and functions -->  
          <?php
   // include('includes/scripts.php');
    include('includes/footer.php');

?>
<script src="js/mask/jquery.min.js"></script>
<script src="js/mask/jquery.inputmask.bundle.js"></script>
<script type="text/javascript">
$(document).ready(function() {
$('#TIN').inputmask();
});
</script>
<script>
con = '<?php echo $member_id ;?>';
 var x = document.getElementById('puc-col');
if (!con) {
    x.style.display = "none";
}else{
    x.style.display = "block";
}

</script>