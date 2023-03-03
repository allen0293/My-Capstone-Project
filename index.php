<?php
date_default_timezone_set('Asia/Manila');
include('includes/security.php');
require_once 'classes/Cregister.php';
require_once 'classes/encryption.php';
require_once 'classes/user.php';  
$objtDash = new Cregistration();
  
  //Register Table
  $Borrower_query = "SELECT Count(loan_id) as total, sum((based)+(based*0.05)) as total_receivable FROM loan WHERE based > total";
  $Borrower_stmt = $objtDash-> runQuery($Borrower_query);
  $Borrower_stmt->execute();
  $rowBorrower = $Borrower_stmt->fetch(PDO::FETCH_ASSOC);


  //Member Table
  $Mquery = "SELECT Count(member_id) as total_member, Sum(capital_build_up) as cbu, Sum(paid_up_capital) as puc FROM member";
  $Mstmt = $objtDash-> runQuery($Mquery);
  $Mstmt->execute();
  $rowMember = $Mstmt->fetch(PDO::FETCH_ASSOC);

  //Rent Table
  $Pquery = "SELECT SUM(amount) as amount FROM rent WHERE pay<>amount";
  $Pstmt = $objtDash-> runQuery($Pquery);
  $Pstmt->execute();
  $rowPayment = $Pstmt->fetch(PDO::FETCH_ASSOC);

  //EQuipment Table
  $Equery = "SELECT Count(eqp_id) as total_eqp FROM equipment WHERE status!='occupied'";
  $Estmt = $objtDash-> runQuery($Equery);
  $Estmt->execute();
  $rowEqp = $Estmt->fetch(PDO::FETCH_ASSOC);
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

 <?php  
          //Cropping1
           $crop1_rice=0; $crop1_corn=0; $crop1_mongo_beans=0;
            $objtEncrypt =  new Encryption();
            $objtUser  = new User();
            $crop1_query = "SELECT * FROM member";
            $crop1_stmt = $objtUser-> runQuery($crop1_query);
            $crop1_stmt->execute();
            $crop1_count= $crop1_stmt->rowCount();
           if($crop1_count > 0){    
            while ($rowCrop1 = $crop1_stmt->fetch(PDO::FETCH_ASSOC)) {
                  $crop1 =  strtolower($objtEncrypt->decrypt($rowCrop1["crop1"]));
                  if ($crop1 == "rice") {
                    $crop1_rice+=1;
                  }else if($crop1 == "corn"){
                    $crop1_corn+=1;
                  }else if($crop1 == "mongo beans"){
                     $crop1_mongo_beans+=1;             
                  }
            }
          }

          $dataPoint_crop1 = array(
        array("label" => "Rice", "y" => $crop1_rice),
        array("label" => "Corn", "y" => $crop1_corn),
        array("label" => "Mongo Beans", "y" => $crop1_mongo_beans)
        );
        //Cropping 2
           $crop2_rice=0; $crop2_corn=0; $crop2_mongo_beans=0;
            $objtEncrypt =  new Encryption();
            $objtUser  = new User();
            $crop2_query = "SELECT * FROM member";
            $crop2_stmt = $objtUser-> runQuery($crop2_query);
            $crop2_stmt->execute();
            $crop2_count= $crop2_stmt->rowCount();
           if($crop2_count > 0){    
            while ($rowCrop2 = $crop2_stmt->fetch(PDO::FETCH_ASSOC)) {
                  $crop2 =  strtolower($objtEncrypt->decrypt($rowCrop2["crop2"]));
                  if ($crop2 == "rice") {
                    $crop2_rice+=1;
                  }else if($crop2 == "corn"){
                    $crop2_corn+=1;
                  }else if($crop2 == "mongo beans"){
                     $crop2_mongo_beans+=1;             
                  }
            }
          }

          $dataPoint_crop2 = array(
        array("label" => "Rice", "y" => $crop2_rice),
        array("label" => "Corn", "y" => $crop2_corn),
        array("label" => "Mongo Beans", "y" => $crop2_mongo_beans)
        );
          //Cropping 3
           $crop3_rice=0; $crop3_corn=0; $crop3_mongo_beans=0;
            $objtEncrypt =  new Encryption();
            $objtUser  = new User();
            $crop3_query = "SELECT * FROM member";
            $crop3_stmt = $objtUser-> runQuery($crop3_query);
            $crop3_stmt->execute();
            $crop3_count= $crop3_stmt->rowCount();
           if($crop3_count > 0){    
            while ($rowCrop3 = $crop3_stmt->fetch(PDO::FETCH_ASSOC)) {
                  $crop3 =  strtolower($objtEncrypt->decrypt($rowCrop3["crop3"]));
                  if ($crop3 == "rice") {
                    $crop3_rice+=1;
                  }else if($crop3 == "corn"){
                    $crop3_corn+=1;
                  }else if($crop3 == "mongo beans"){
                     $crop3_mongo_beans+=1;             
                  }
            }
          }

         $dataPoint_crop3 = array(
        array("label" => "Rice", "y" => $crop3_rice),
        array("label" => "Corn", "y" => $crop3_corn),
        array("label" => "Mongo Beans", "y" => $crop3_mongo_beans)
        );
                  
?>
<?php
$dataPoints = array();  
try{
    $handle = "SELECT monthname(date_pay) as MONTHNAME, sum(paid) as paid from puc_transaction WHERE Year(date_pay) = YEAR(NOW()) group by monthname(date_pay) DESC"; 
    $handle =$objtDash-> RunQuery($handle);
    $handle->execute(); 
    $result = $handle->fetchAll(\PDO::FETCH_OBJ);
    foreach($result as $row){
        array_push($dataPoints, array("label"=> $row->MONTHNAME, "y"=> $row->paid));
    }
  $link = null;
}
catch(\PDOException $ex){
    print($ex->getMessage());
}

$dataPoints_rent = array();
try{
    $handle_rent = "SELECT monthname(date_returned) as MONTHNAME, sum(pay) as paid from rent WHERE Year(date_returned) = YEAR(NOW()) AND date_returned IS NOT NULL group by monthname(date_returned) DESC"; 
    $handle_rent =$objtDash-> RunQuery($handle_rent);
    $handle_rent->execute(); 
    $result_rent = $handle_rent->fetchAll(\PDO::FETCH_OBJ);
    foreach($result_rent as $row_rent){
        array_push($dataPoints_rent, array("label"=> $row_rent->MONTHNAME, "y"=> $row_rent->paid));
    }
  $link = null;
}
catch(\PDOException $ex){
    print($ex->getMessage());
}


$dataPoints_puc_year = array();
try{
    $handle = "SELECT YEAR(date_pay) as Year, sum(paid) as paid from puc_transaction"; 
    $handle =$objtDash-> RunQuery($handle);
    $handle->execute(); 
    $result = $handle->fetchAll(\PDO::FETCH_OBJ);
    foreach($result as $row){
        array_push($dataPoints_puc_year, array("label"=> $row->Year, "y"=> $row->paid));
    }
  $link = null;
}
catch(\PDOException $ex){
    print($ex->getMessage());
}

$dataPoints_rent_year = array();
try{
    $handle_rent = "SELECT Year(date_returned) as Year, sum(pay) as paid from rent WHERE date_returned IS NOT NULL "; 
    $handle_rent =$objtDash-> RunQuery($handle_rent);
    $handle_rent->execute(); 
    $result_rent = $handle_rent->fetchAll(\PDO::FETCH_OBJ);
    foreach($result_rent as $row_rent){
        array_push($dataPoints_rent_year, array("label"=> $row_rent->Year, "y"=> $row_rent->paid));
    }
  $link = null;
}
catch(\PDOException $ex){
    print($ex->getMessage());
}

$dataPoints_loan_month = array();
try{
    $handle_loan = "SELECT monthname(date_finish) as month, sum(total) as total from loan WHERE Year(date_finish) = YEAR(NOW()) AND date_finish IS NOT NULL group by monthname(date_finish) DESC"; 
    $handle_loan =$objtDash-> RunQuery($handle_loan);
    $handle_loan->execute(); 
    $result_loan = $handle_loan->fetchAll(\PDO::FETCH_OBJ);
    foreach($result_loan as $row_loan){
        array_push($dataPoints_loan_month, array("label"=> $row_loan->month, "y"=> $row_loan->total));
    }
  $link = null;
}
catch(\PDOException $ex){
    print($ex->getMessage());
}

$dataPoints_loan_year = array();
try{
    $handle_loan = "SELECT Year(date_finish) as year, sum(total) as total from loan WHERE date_finish IS NOT NULL GROUP BY YEAR(date_finish)"; 
    $handle_loan =$objtDash-> RunQuery($handle_loan);
    $handle_loan->execute(); 
    $result_loan = $handle_loan->fetchAll(\PDO::FETCH_OBJ);
    foreach($result_loan as $row_loan){
        array_push($dataPoints_loan_year, array("label"=> $row_loan->year, "y"=> $row_loan->total));
    }
  $link = null;
}
catch(\PDOException $ex){
    print($ex->getMessage());
}

?>

<?php
  if(!empty($_SESSION['success'])){
    echo'<script>swal ("Welcome '.$_SESSION['username'].'", "", "success");  </script>';
    unset($_SESSION['success']);
  }

?>


<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    <!--div>
    <a href="export.php?monthly_report" target="_blank" class="d-sm-inline-block btn btn-sm btn-danger shadow-sm tel"><i
        class="fas fa-download fa-sm text-white-50"></i> Generate Monthly Report</a>
    </div-->
    <div>
    <a href="#" class="d-sm-inline-block btn btn-sm btn-danger shadow-sm tel" data-toggle="modal" data-target="#reportModal"><i
        class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
    </div>    
  </div>

  <!-- Content Row -->
  <div class="row">
    <!-- Number of Member -->
    <div class="col-xl-3 col-md-4 mb-4">
       <a href="member.php" style="text-decoration: none;">
      <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-md font-weight-bold text-warning text-uppercase mb-1">Member</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?php print($rowMember['total_member']); ?></div>
            </div>
            <div class="col-auto">
              <i class="fas fa-users fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
      </a>
    </div>

    <div class="col-xl-3 col-md-4 mb-4">
      <a href="loan.php" style="text-decoration: none;">
      <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-md font-weight-bold text-success text-uppercase mb-1">Loan Receivable</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">₱ <?php print(number_format($rowBorrower['total_receivable'],2)); ?></div>
            </div>
            <div class="col-auto">
              <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
      </a>
    </div>

    <!-- Total Number of Equipment -->
    <div class="col-xl-3 col-md-4 mb-4">
      <a  href="display_equipment.php" style="text-decoration: none;">
      <div class="card border-left-info shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-md font-weight-bold text-info text-uppercase mb-1">Available Equipment for Rent</div>
              <div class="row no-gutters align-items-center">
                <div class="col-auto">
                  <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php print($rowEqp['total_eqp']); ?></div>
                </div>
              </div>
            </div>
            <div class="col-auto">
              <i class="fas fa-tools fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </a>
    </div>

    <!-- Rent Earnings -->
    <div class="col-xl-3 col-md-4 mb-4">
      <a href="frm_rent.php" style="text-decoration: none;">
      <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-md font-weight-bold text-success text-uppercase mb-1"> Rent Receivable</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">₱ <?php print(number_format($rowPayment['amount'],2));?></div>
            </div>
            <div class="col-auto">
              <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
         <span align="right" class="mr-3 font-weight-bold text-danger"> <?php //print(date("M d, Y h:i:sa")); ?></span>
      </div>
    </a>  
    </div>   
  </div>
<?php 
  if(isset($_GET['key_success'])){
    echo'<script>swal ("Password Recovery key set", "", "success");  </script>';
  }
?>
  <div class="container-fluid">

  <div class="card shadow mt-4">    
      <div class="card-body">
            <div id="loan_" style="height: 370px; width: 100%;"></div>
      </div>
  </div>

  <div class="card shadow mt-2">  
      <div class="card-body">
          <div id="paid_up_capital" style="height: 370px; width: 100%;"></div>
      </div>
  </div>


  <div class="card shadow mt-2">
      <div class="card-body">
      <div id="rent_" style="height: 370px; width: 100%;"></div>
    </div>
    </div>
    <div class="card shadow mt-2">    
      <div class="card-body">
            <div id="crops_" style="height: 370px; width: 100%;"></div>
      </div>
  </div> 
    </div>
  </div>
  
  <div class="modal fade" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Generate Report</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <a href="export.php?monthly_report" target="_blank" class="d-sm-inline-block btn btn-sm btn-info shadow-sm tel mb-2"> Current month</a>
        <form action="generate_report.php" method="post">
        <hr>
        <div align="center">
              <label text="center">Generate Month Report</label>
        </div>
          <div class="row">
           <div class="col">
                <label>Month</label>
                <select class="custom-select mb-2" type="text" name="month">
                <option></option>
                <option value="January">January</option>
                <option value="Febuary">Febuary</option>
                <option value="March">March</option>
                <option value="April">April</option>
                <option value="May">May</option>
                <option value="June">June</option>
                <option value="July">July</option>
                <option value="August">August</option>
                <option value="September">September</option>
                <option value="October">October</option>
                <option value="November">November</option>
                <option value="December">December</option>
                </select>
            </div>
               <div class="col">
                 <label>Year</label>
                  <input type="year" class="form-control" name="year" placeholder="e.g. 2020" required="">
                </div>
            </div>
            <div align="right">
                  <button type="submit" name="generate_month" class="btn btn-primary ">Generate</button>
            </div>
          </form>
          <hr>
          <form action="generate_report.php" method="post">
            <div align="center">
                  <label text="center">Generate Year Report</label>
                  <input type="year" class="form-control" name="years" placeholder="e.g. 2020" required="">
            </div>
            <div class="mt-2" align="right">
                    <button type="submit" name="generate_year" class="btn btn-primary ">Generate</button>
              </div>
           </div>
          </form>
      </div>
    </div>
  </div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Set your password security key</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="code.php" method="post">

        <div class="form-group">
        <label>What is your childhood nickname?</label>
            <input type="text" name="ans1" class="form-control " placeholder="" required>
        </div>

        <div class="form-group">
        <label>What is your mother's maiden name?</label>
            <input type="text" name="ans2" class="form-control " placeholder="" required>
        </div>

        <div class="form-group">
        <label>Where is your birth place?</label>
            <input type="text" name="ans3" class="form-control " placeholder="" required>
        </div>              
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
        <button type="submit" name="security" class="btn btn-primary">Save</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php 
  $username = $_SESSION['username'];
  $query = "SELECT * FROM register WHERE username = :username AND ans1 IS NULL";
  $stmt = $objtDash->runQuery($query);
  $stmt->execute(['username' => $username]);
  $count = $stmt->rowCount();
  if($count>0){
      echo'<script>
        $(document).ready(function(){
            $("#myModal").modal("show");
          });
      </script>';
  }else{
    if(!empty($_SESSION['security_key'])){
      echo'<script>swal ("Pasword recovery key set", "", "success");  </script>';
       unset($_SESSION['security_key']);
    }
  }
?>


  <!-- Content Row -->
  <?php
include('includes/footer.php');
//include('includes/scripts.php');
?>
<!-- Rent Earnings Chart-->

</script>
<script src="js/canvasjs.min.js"></script>
<script>
$(function () {
  var chart_loan = new CanvasJS.Chart("loan_", {
  animationEnabled: true,
  exportEnabled: true,
  theme: "light1", // "light1", "light2", "dark1", "dark2"
  title:{
    text: "Loan Statement"
  },
  axisY:{
    minimum:0 ,
    },
  data: [{
    type: "column", //change type to bar, line, area, pie, etc  
    name: "Monthly Loan Statement As Of <?php echo date('Y'); ?>",
    showInLegend: true,
    dataPoints: <?php echo json_encode($dataPoints_loan_month, JSON_NUMERIC_CHECK); ?>
  },
  {
    type: "column", //change type to bar, line, area, pie, etc  
    name: "Loan  Yearly Statement",
    showInLegend: true,
    dataPoints: <?php echo json_encode($dataPoints_loan_year, JSON_NUMERIC_CHECK); ?>
  }
  ],
  legend: {
        cursor: "pointer",
        itemclick: function (e) {
            if (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                e.dataSeries.visible = false;
            }
            else {
                e.dataSeries.visible = true;
            }
            chart_loan.render();
        }
    }
});
chart_loan.render();



var chartx = new CanvasJS.Chart("paid_up_capital", {
  animationEnabled: true,
  exportEnabled: true,
  theme: "light1", // "light1", "light2", "dark1", "dark2"
  title:{
    text: "Paid up Capital Statement"
  },
  axisY:{
    minimum:0 ,
    },
  data: [{
    type: "column", //change type to bar, line, area, pie, etc  
    name: "Monthly Paid up Capital Statement As of <?php echo date('Y'); ?>",
    showInLegend: true,
    dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
  },
  {
    type: "column", //change type to bar, line, area, pie, etc  
    name: "Paid up Capital Yearly Statement",
    showInLegend: true,
    dataPoints: <?php echo json_encode($dataPoints_puc_year, JSON_NUMERIC_CHECK); ?>
  }
  ],
  legend: {
        cursor: "pointer",
        itemclick: function (e) {
            if (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                e.dataSeries.visible = false;
            }
            else {
                e.dataSeries.visible = true;
            }
            chartx.render();
        }
    }
});
chartx.render();


var chart1 = new CanvasJS.Chart("rent_", {
  animationEnabled: true,
  exportEnabled: true,
  theme: "light1", // "light1", "light2", "dark1", "dark2"
  title:{
    text: "Rent Statement"
  },
  axisY:{
    minimum:0 ,
    },
  data: [{
    type: "column", //change type to bar, line, area, pie, etc  
    name: "Monthly rent Statement as of <?php echo date('Y'); ?>",
    showInLegend: true,
    dataPoints: <?php echo json_encode($dataPoints_rent, JSON_NUMERIC_CHECK); ?>
  },
  {
    type: "column", //change type to bar, line, area, pie, etc 
    name: "Yearly Rent Statement",
    showInLegend: true, 
    dataPoints: <?php echo json_encode($dataPoints_rent_year, JSON_NUMERIC_CHECK); ?>
  }
  ],
  legend: {
        cursor: "pointer",
        itemclick: function (e) {
            if (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                e.dataSeries.visible = false;
            }
            else {
                e.dataSeries.visible = true;
            }
            chart1.render();
        }
    }
});
chart1.render();



var chart2 = new CanvasJS.Chart("crops_", {
            title: {
                text: "Number of Members Who Plant Rice, Corn and Mongo beans"
            },
            subtitles: [
                {
                    text: ""
                }
            ],
            animationEnabled: true,
            exportEnabled: true,
            theme: "light1",
            axisY: {
                titleFontFamily: "arial",
                titleFontSize: 12,
                includeZero: false
            },
            toolTip: {
                shared: true
            },
            data: [
            {
                type: "column",
                name: "First Crop",
                showInLegend: true,
                dataPoints: <?php echo json_encode($dataPoint_crop1, JSON_NUMERIC_CHECK); ?>
            },
            {
                type: "column",
                name: "Second Crop",
                showInLegend: true,
                dataPoints: <?php echo json_encode($dataPoint_crop2, JSON_NUMERIC_CHECK); ?>
            },
            {
                type: "column",
                name: "Third Crop",
                showInLegend: true,
                dataPoints: <?php echo json_encode($dataPoint_crop3, JSON_NUMERIC_CHECK); ?>
            }
            ],
            legend: {
                cursor: "pointer",
                itemclick: function (e) {
                    if (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                        e.dataSeries.visible = false;
                    }
                    else {
                        e.dataSeries.visible = true;
                    }
                    chart2.render();
                }
            }
        });

        chart2.render();
});
</script>