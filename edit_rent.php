<?php
// Show PHP errors
date_default_timezone_set('Asia/Manila');
ini_set('display_errors',0);
ini_set('display_startup_erros',0);
include('includes/security.php');
require_once 'classes/rent.php';

$objtRent = new Rent();
// GET
if(isset($_GET['edit_id'])){
    $rent_id = $_GET['edit_id'];
    $stmt = $objtRent->runQuery("SELECT rent.rent_id, member.member_id, equipment.eqp_id, concat(member.Lname,' ', member.Fname,' ', member.Mname) as Name, rent.rent_date, rent.due_date from rent INNER JOIN member on member.member_id = rent.member_id INNER JOIN equipment ON equipment.eqp_id = rent.eqp_id where rent_id = :rent_id");
    $stmt->execute(array(":rent_id"=> $rent_id));
    $rowRent = $stmt->fetch(PDO::FETCH_ASSOC);
}else{
    $rent_id = null;
    $rowRent = null; 
}

// POST
if(isset($_POST['btn_save'])){
    //adding the member
    $eqp_id = strip_tags($_POST['eqp_id']);
    $member_id = strip_tags($_POST['member_id']);
    $rent_date = strip_tags($_POST['rent_date']);
    $due_date = strip_tags($_POST['due_date']);
    try{
        if($rent_id != null){
            if($objtRent->updateRent($eqp_id,$rent_date,$due_date,$rent_id)){
              $objtRent->redirect('frm_rent.php?updated');
            }
          }else {
              if($objtRent->insert($eqp_id,$member_id,$rent_date,$due_date)){
                  $objtRent->redirect('frm_rent.php?inserted');
              }else{
                  $objtRent->redirect('frm_rent.php?error');
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
            <div class="row">
                <!-- Sidebar menu -->
                <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
                <h1 style="margin-top: 10px">Rental Details</h1>
                    <form method="post">
                        <div class="container">
                            <div class="form-group ">
                                <label  for="rent_id">RENT ID</label>
                                <input class="form-control" type="text" name="rent_id" id="rent_id" value="<?php print($rowRent['rent_id']);?>" readonly>
                            </div>
                            
                            <div class="form-group ">
                                <label for="member_id">Member ID </label>
                                <input  class="form-control" type="text" name="member_id" id="member_id" placeholder="" value="<?php print($rowRent['member_id']);?>" readonly>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group ">
                                        <label for="name">Name </label>
                                        <input  class="form-control" type="text" name="name" id="name" placeholder="" value="<?php print($rowRent['Name']);?>" readonly>
                                    </div>
                                </div> 
                           
                                <div class="col">
                                    <div class="form-group ">
                                        <label for="eqp_id">Equipment ID </label>
                                        <select class="custom-select" type="text" name="eqp_id" required="">
                                            <option><?php print($rowRent['eqp_id']);?></option>
                                                <?php
                                                    $query = "SELECT * FROM equipment";
                                                    $stmt = $objtRent-> runQuery($query);
                                                    $stmt->execute();
                                                ?>
                                                <?php 
                                                    $count= $stmt->rowCount();
                                                    if($count > 0){
                                                    while($rowEqp = $stmt->fetch(PDO::FETCH_ASSOC)){
                                                ?>   
                                                    <option><?php print($rowEqp['eqp_id']);?></option>    
                                                    <?php }} ?> 
                                        </select>   
                                    </div> 
                                </div> 
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                            <label for="rent_date">Rent Date *</label>
                                            <input  class="form-control" type="datetime-local" name="rent_date" id="rent_date" placeholder="" value="<?php print($rowRent['rent_date']);?> " >
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                            <label for="birthdate">Return Date *</label>
                                            <input  class="form-control" type="datetime-local" name="due_date" id="due_date" placeholder="" value=" <?php print($rowRent['due_date']);?>">
                                    </div>
                                </div>
                            </div>
                            <input class="btn btn-primary mb-2" type="submit" name="btn_save" value="Save">
                      </div>
                    </form>    
                   </div>       
                </main>
            </div>
        </div>    
        <!-- Footer scripts, and functions -->
       <?php
         // include('includes/scripts.php');
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
