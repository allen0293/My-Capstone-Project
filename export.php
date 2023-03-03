  <?php      
date_default_timezone_set('Asia/Manila');
include('includes/security.php');
require_once 'classes/user.php';
include('includes/header.php'); 
require_once 'classes/encryption.php';
$objtEncrypt = new Encryption();
$objtUser = new User();
?><br>
<?php 
//pdf function Landscape
function Lpdf($content,$filename){
      require_once('tcpdf_min/tcpdf.php');  
      $obj_pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
      $obj_pdf->SetTitle("Sinulatan 1st Coopertive");  
      $obj_pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);  
      $obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));  
      $obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));  
      $obj_pdf->SetDefaultMonospacedFont('helvetica');  
      $obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);  
      $obj_pdf->SetMargins(PDF_MARGIN_LEFT, '5', PDF_MARGIN_RIGHT);  
      $obj_pdf->setPrintHeader(false);  
      $obj_pdf->setPrintFooter(true);  
      $obj_pdf->SetAutoPageBreak(TRUE, 10);  
      $obj_pdf->SetFont('helvetica', '', 11); 
      $obj_pdf->setPageOrientation('L'); 
      $obj_pdf->AddPage(); 
      //$obj_pdf->Image('img/logo.jpg',60, 10, 30,'JPG');      
      $obj_pdf->writeHTML($content);  
      ob_end_clean();
      $obj_pdf->Output(''.$filename.'.pdf', 'I');  
      // Routine to replace logo img if deleted by tcpdf
      // This happened twice while testing
    $logo_filename = 'img/logo.jpg';
      if (file_exists($logo_filename)) {
    // echo "<BR>The file $logo_filename exists";
      } else {
    // echo "<BR>The file $logo_filename does not exist";
    $backup_logo = 'logo.jpg';
    if (!copy($backup_logo, $logo_filename)) {
    // echo "<BR>failed to copy $file";
    } else {
    // echo "<BR>Success COPYING $backup_logo TO logo_filename";
    }
  }
}
//PDF FUNCTIOn Portrait
function pdf($content){  
        require_once('tcpdf_min/tcpdf.php');  
        $obj_pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $obj_pdf->SetTitle("Sinulatan 1st Coopertive");  
        $obj_pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);  
        $obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));  
        $obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));  
        $obj_pdf->SetDefaultMonospacedFont('helvetica');  
        $obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);  
        $obj_pdf->SetMargins(PDF_MARGIN_LEFT, '5', PDF_MARGIN_RIGHT);  
        $obj_pdf->setPrintHeader(false);  
        $obj_pdf->setPrintFooter(true);  
        $obj_pdf->SetAutoPageBreak(TRUE, 10);  
        $obj_pdf->SetFont('helvetica', '', 11);  
        $obj_pdf->AddPage(); 
        $obj_pdf->Image('img/logo.jpg',10, 10, 30,'JPG');      
        
        $obj_pdf->writeHTML($content);  
        $tDate = date("F j, Y");
        $obj_pdf->Cell(0, 10, 'Date : '.$tDate, 0, false, 'R', 0, '', 0, false, 'T', 'M');
        ob_end_clean();
        $obj_pdf->Output('file.pdf', 'I');  
        // Routine to replace logo img if deleted by tcpdf
        // This happened twice while testing
      $logo_filename = 'img/logo.jpg';
        if (file_exists($logo_filename)) {
      // echo "<BR>The file $logo_filename exists";
        } else {
      // echo "<BR>The file $logo_filename does not exist";
      $backup_logo = 'logo.jpg';
      if (!copy($backup_logo, $logo_filename)) {
      // echo "<BR>failed to copy $file";
      } else {
      // echo "<BR>Success COPYING $backup_logo TO logo_filename";
      }
    }
}

function pdfx($content,$filename){  
  require_once('tcpdf_min/tcpdf.php');  
  $obj_pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  $obj_pdf->SetTitle("Sinulatan 1st Coopertive");  
  $obj_pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);  
  $obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));  
  $obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));  
  $obj_pdf->SetDefaultMonospacedFont('helvetica');  
  $obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);  
  $obj_pdf->SetMargins(PDF_MARGIN_LEFT, '5', PDF_MARGIN_RIGHT);  
  $obj_pdf->setPrintHeader(false);  
  $obj_pdf->setPrintFooter(false);  
  $obj_pdf->SetAutoPageBreak(false, 10);  
  $obj_pdf->SetFont('helvetica', '', 11);  
  $obj_pdf->AddPage(); 
  $obj_pdf->Image('img/logo.jpg',10, 10, 30,'JPG');      
  
  $obj_pdf->writeHTML($content);  
  ob_end_clean();
  $obj_pdf->Output(''.$filename.'.pdf', 'I');  
  // Routine to replace logo img if deleted by tcpdf
  // This happened twice while testing
$logo_filename = 'img/logo.jpg';
  if (file_exists($logo_filename)) {
// echo "<BR>The file $logo_filename exists";
  } else {
// echo "<BR>The file $logo_filename does not exist";
$backup_logo = 'logo.jpg';
if (!copy($backup_logo, $logo_filename)) {
// echo "<BR>failed to copy $file";
} else {
// echo "<BR>Success COPYING $backup_logo TO logo_filename";
}
}
}
//PDF Memebr
function fetch_member(){
    $objtUser = new User();
    $objtEncrypt = new Encryption();
    $output='';
        $query = "SELECT * FROM member";
          $stmt = $objtUser-> runQuery($query);
          $stmt->execute();

             $count= $stmt->rowCount();

              if($count > 0){
                while($rowUser = $stmt->fetch(PDO::FETCH_ASSOC)){
                  $birth_date = date_create($objtEncrypt->decrypt($rowUser["birthdate"]));
                  $date_reg = date_create($rowUser["registered_date"]);
           $output .='
            <tr> 
                <td>'.$objtEncrypt->decrypt($rowUser["Lname"]).'
                '.$objtEncrypt->decrypt($rowUser["Fname"]).'
                '.$objtEncrypt->decrypt($rowUser["Mname"]).'
                </td>
                  <td>'.$objtEncrypt->decrypt($rowUser["spouse_name"]).'</td>
                  <td>'.$objtEncrypt->decrypt($rowUser["address"]).'</td>
                  <td>'.date_format($birth_date,'M d, Y').'</td>
                  <td>'.$objtEncrypt->decrypt($rowUser["contactno"]).'</td> 
                  <td>'.$objtEncrypt->decrypt($rowUser["land_location"]).'</td>                  
                  <td>'.$objtEncrypt->decrypt($rowUser["land_size"]).'</td>
                  <td>'.number_format($rowUser["capital_build_up"],2).'</td>  
                  <td>'.number_format($rowUser["paid_up_capital"],2).'</td>  
                  <td>'.date_format($date_reg,'M d, Y h:i A').'</td>  
                   <td> </td>        
              </tr>
              ';
                }
              }
              return $output;
          }

if(isset($_GET["member_pdf"]))  
 {  
       
      $content = '';  
      $content .= '
        <h2 align="center">Sinulatan 1st Agriculture Cooperative </h2>
        <p align="center">Brg. Sinulatan 1st, Camilling, Tarlac</p> 
        <p align="center">CDA Reg No. 9520-1030000000044540</p> 
        <p align="center">TIN: 748-678-180-000</p> 
        <h4 align="center">Master List of Member</h4> 
        <p align="right">'.date('F, j Y').'</p>
        <table border="1" cellspacing="0" cellpadding="3" align="center" style="font-size: 11px;">
                            <thead>
                              <tr style="font-weight: bold;">                                                             
                                <th> Member Name</th>
                                <th>Spouse Name</th>
                                <th>Address</th>
                                <th>Birthday</th>
                                <th>Contact Number</th>
                                <th>Land Location</th>
                                <th>Area (in Hectares)</th>
                                <th>Subscribed Capital</th>
                                <th>Paid Up Capital</th>
                                <th>Date Registered</th> 
                                <th>Signature</th>                      
                            </tr>
                            </thead>
                            ';  
      $content .= fetch_member();  
      $content .= '</table> 
          <pre><strong>
 

          Prepared by:                      Attested by:

          _____________________          ______________________
             SACoop Secretary                       SACoop President
          </strong></pre>
      ';  
      $filename ="Member List";
      Lpdf($content,$filename);
 }  
 ?>
<!-- Export Equipment-->
<?php
function fetch_equipment(){
    $objtUser = new User();
     $objtEncrypt = new Encryption();
  $output='';
        $query = "SELECT * FROM equipment";
          $stmt = $objtUser-> runQuery($query);
          $stmt->execute();

             $count= $stmt->rowCount();

              if($count > 0){
                while($rowUser = $stmt->fetch(PDO::FETCH_ASSOC)){      
           $output .='
            <tr>
              <td>'.$rowUser["eqp_id"].'</td>
             <td>'.$objtEncrypt->decrypt($rowUser["serial_no"]).'</td>
             <td>'.$objtEncrypt->decrypt($rowUser["eqp_name"]).'</td>
             <td>'.$objtEncrypt->decrypt($rowUser["eqp_model"]).'</td>
             <td>'.$objtEncrypt->decrypt($rowUser["eqp_desc"]).'</td>
             <td>'.number_format($rowUser['rent_price'],2).'</td>     
            </tr>
            ';
              }
            }
            return $output;
          }
if(isset($_GET["equipment_pdf"]))  
 {      
      $content = '';  
      $content .= '
        <h2 align="center">Sinulatan 1st Agriculture Cooperative </h2>
        <p align="center">Brg. Sinulatan 1st, Camilling, Tarlac</p> 
        <p align="center">CDA Reg No. 9520-1030000000044540</p> 
        <p align="center">TIN: 748-678-180-000</p> 
        <h4 align="center">Equipment List</h4> 
        <p align="right">'.date('F, j Y').'</p>
        <table border="1" cellspacing="0" cellpadding="3" style="font-size: 10px">
                            <thead>
                              <tr style="font-weight: bold;">
                                <th>Equiment ID</th>
                                <th>Serial Number</th>
                                <th>Name</th>
                                <th>Model</th>
                                <th>Description</th>
                                <th>Rental Price</th>                     
                            </tr>
                            </thead>';  
      $content .= fetch_equipment();  
      $content .= '</table> 
          <pre><strong>
 

          Prepared by:                      Attested by:

          _____________________          ______________________
             SACoop Secretary                       SACoop President
          </strong></pre>
      ';  
      $filename ="Equipment List";
      pdfx($content,$filename);
 }  
 ?>
<!-- Rent Unpaid -->
<?php
function fetch_rent(){
    $objtUser = new User();
    $objtEncrypt = new Encryption();
    $output='';
        $query = "SELECT rent.rent_id, member.member_id, member.Lname, member.Fname, member.Mname, equipment.eqp_id, equipment.serial_no, equipment.eqp_name, rent.rent_date, rent.due_date, TIMESTAMPDIFF(day,rent.rent_date,rent.due_date)*equipment.rent_price as amount, rent.pay, TIMESTAMPDIFF(day,rent.rent_date,rent.due_date)*equipment.rent_price - rent.pay as Balance FROM rent INNER JOIN member on member.member_id = rent.member_id INNER JOIN equipment on equipment.eqp_id = rent.eqp_id Where TIMESTAMPDIFF(day,rent.rent_date,rent.due_date)*equipment.rent_price > pay";
          $stmt = $objtUser-> runQuery($query);
          $stmt->execute();

             $count= $stmt->rowCount();

              if($count > 0){
                while($rowUser = $stmt->fetch(PDO::FETCH_ASSOC)){
           $output .='
            <tr>
              <td>'.$rowUser["rent_id"].'</td>
              <td>'.$rowUser['member_id'].'</td>
              <td>'.$objtEncrypt->decrypt($rowUser["Lname"]).'
                '.$objtEncrypt->decrypt($rowUser["Fname"]).'
                '.$objtEncrypt->decrypt($rowUser["Mname"]).'</td>
              <td>'.$rowUser['eqp_id'].'</td>
              <td>'.$objtEncrypt->decrypt($rowUser["serial_no"]).'</td>
              <td>'.$objtEncrypt->decrypt($rowUser["eqp_name"]).'</td>
              <td>'.$rowUser['rent_date'].'</td>
              <td>'.$rowUser['due_date'].'</td>  
              <td>'.$rowUser['amount'].'</td> 
              <td>'.$rowUser['pay'].'</td> 
              <td>'.$rowUser['Balance'].'</td> 
            </tr>
            ';
              }
            }
            return $output;
          }
if(isset($_GET["rent_pdf"]))  
 {      
      $content = '';  
      $content .= '  <br>
        <h2 align="center">Sinulatan 1st Agriculture Cooperative </h2>
        <p align="center">Brg. Sinulatan 1st, Camilling, Tarlac</p> 
        <p align="center">CDA Reg No. 9520-1030000000044540</p> 
        <p align="center">TIN: 748-678-180-000</p> 
        <h4 align="center">Rent</h4> 
        <p align="right">'.date('F, j Y').'</p>
        <table border="1" cellspacing="0" cellpadding="3" style="font-size: 10px">
                            <thead>
                              <tr style="font-weight: bold;">
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
                            </tr>
                            </thead>';  
      $content .= fetch_rent();  
      $content .= '</table> 
          <pre><strong>
 

          Prepared by:                      Attested by:

          _____________________          ______________________
             SACoop Secretary                       SACoop President
          </strong></pre>
      ';  
      $filename ="Unpaid Rent";
      pdfx($content,$filename);
 }  
 ?>

<!-- Rental Records -->
<?php
function fetch_rent_records(){
    $objtUser = new User();
    $objtEncrypt = new Encryption();
    $output='';
        $query = "SELECT rent.rent_id, member.member_id, member.Lname, member.Fname, member.Mname, equipment.eqp_id ,equipment.serial_no, equipment.eqp_name,equipment.rent_price, rent.rent_date, rent.due_date, rent.date_returned, (TIMESTAMPDIFF(day,rent.rent_date,rent.due_date)*equipment.rent_price) amount,(TIMESTAMPDIFF(hour,rent.due_date,rent.date_returned)*equipment.rent_price/24) as penalty, rent.pay FROM rent INNER JOIN member on member.member_id = rent.member_id INNER JOIN equipment on equipment.eqp_id = rent.eqp_id WHERE month(date_returned) = month(NOW()) AND YEAR(date_returned) = YEAR(NOW()) GROUP BY rent_id DESC ";
          $stmt = $objtUser-> runQuery($query);
          $stmt->execute();

             $count= $stmt->rowCount();

              if($count > 0){
                while($rowRent = $stmt->fetch(PDO::FETCH_ASSOC)){
                $rent_date = date_create($rowRent['rent_date']);
                $due_date =  date_create($rowRent['due_date']);
                $date_returned=date_create($rowRent['date_returned']);

                if($rowRent['penalty']<=0){
                  $penalty = 0;      
                  $total_amount = $rowRent['amount'] + $penalty;
                  $status = "Ontime";
               }else{
                  $penalty = ceil($rowRent['penalty']);                                
                  $total_amount = $rowRent['amount'] + $penalty;
                  $status = "Late";
               }
           $output .='
            <tr>
              <td>'.$rowRent["rent_id"].'</td>
             <td>'.$rowRent["member_id"].'</td>
             <td>'.$objtEncrypt->decrypt($rowRent["Lname"]).'
                  '.$objtEncrypt->decrypt($rowRent["Fname"]).'
                  '.$objtEncrypt->decrypt($rowRent["Mname"]).'</td>
              <td>'.$rowRent["eqp_id"].'</td>
             <td>'.$objtEncrypt->decrypt($rowRent["serial_no"]).'</td>
             <td>'.$objtEncrypt->decrypt($rowRent["eqp_name"]).'</td>
             <td>'.date_format($rent_date,"M d, Y h:i A").'</td>
             <td>'.date_format($due_date,'M d, Y h:i A').'</td>
             <td>'.date_format($date_returned,"M d, Y h:i A").'</td>
             <td>'.$status.'</td>
             <td>'.number_format($rowRent["amount"],2).'</td>
             <td>'.number_format($penalty,2).'</td>
             <td>'.number_format($total_amount,2).'</td>
             <td>'.number_format($rowRent['pay'],2).'</td>
            </tr>
            ';
              }
            }
            return $output;
          }
if(isset($_GET["rent_record_pdf"]))  
 {      
      $content = '';  
      $content .= '  
        <h2 align="center">Sinulatan 1st Agriculture Cooperative </h2>
        <p align="center">Brg. Sinulatan 1st, Camilling, Tarlac</p> 
        <p align="center">CDA Reg No. 9520-1030000000044540</p> 
        <p align="center">TIN: 748-678-180-000</p> 
        <h4 align="center">Rental Records</h4> 
        <p align="right">'.date('F, j Y').'</p>
        <table border="1" cellspacing="0" cellpadding="3" align="center" style="font-size: 10px">
                            <thead>
                              <tr style="font-weight: bold;">
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
                            </thead>';  
      $content .= fetch_rent_records();  
      $content .= '</table> 
          <pre><strong>
 

          Prepared by:                      Attested by:

          _____________________          ______________________
             SACoop Secretary                       SACoop President
          </strong></pre>
      ';   
      $filename ="Rental Records";
      Lpdf($content,$filename);
 }  
 ?>


 <!-- Payment Transaction -->
<?php
function fetch_Transaction(){
    $objtUser = new User();
    $objtEncrypt = new Encryption();
    $output='';
       $query = "SELECT rent_payment.pay_id,rent.rent_id, member.Lname,member.Fname,member.Mname, member.member_id,equipment.eqp_id,equipment.eqp_name, equipment.serial_no, rent.rent_date, rent.due_date, rent.date_returned, rent_payment.amount, rent_payment.paid, rent_payment.date_pay,(TIMESTAMPDIFF(hour,rent.due_date,rent.date_returned)*equipment.rent_price/24) as penalty, register.username from rent_payment LEFT JOIN rent ON rent.rent_id = rent_payment.rent_id LEFT JOIN member on member.member_id = rent.member_id LEFT JOIN equipment on equipment.eqp_id = rent.eqp_id LEFT JOIN register ON register.user_id = rent_payment.user_id  WHERE month(date_pay) = month(NOW()) AND YEAR(date_pay) = YEAR(NOW()) GROUP BY rent_payment.pay_id DESC";
          $stmt = $objtUser-> runQuery($query);
          $stmt->execute();

             $count= $stmt->rowCount();

              if($count > 0){
                while($rowUser = $stmt->fetch(PDO::FETCH_ASSOC)){
                  $rent_date = date_create($rowUser['rent_date']);
                  $due_date =  date_create($rowUser['due_date']);
                  $date_pay = date_create($rowUser['date_pay']);
                  $date_returned = date_create($rowUser['date_returned']);

                  if($rowUser['penalty']<=0){
                    $penalty = 0;      
                    $total_amount = $rowUser['amount'] + $penalty;
                    $status = "Ontime";
                 }else{
                    $penalty = ceil($rowUser['penalty']);                                
                    $total_amount = $rowUser['amount'] + $penalty;
                    $status = "Late";
                 }



           $output .='
            <tr>
              <td>'.$rowUser["pay_id"].'</td>
              <td>'.$rowUser["member_id"].'</td>
              <td>'.$objtEncrypt->decrypt($rowUser["Lname"]).'
                  '.$objtEncrypt->decrypt($rowUser["Fname"]).'
                  '.$objtEncrypt->decrypt($rowUser["Mname"]).'</td>
              <td>'.$rowUser["eqp_id"].'</td>
              <td>'.$objtEncrypt->decrypt($rowUser["eqp_name"]).'</td>
              <td>'.$objtEncrypt->decrypt($rowUser["serial_no"]).'</td>
              <td>'.date_format($rent_date,'M d, Y h:i A').'</td>
              <td>'.date_format($due_date,'M d, Y h:i A').'</td>   
              <td>'.date_format($date_returned,'F d, Y h:i A').'</td>
              <td>'.$status.'</td>
              <td>'.number_format($rowUser['amount'],2).'</td> 
              <td>'.number_format($rowUser['paid'],2).'</td> 
              <td>'.date_format($date_pay,'M d, Y h:i A').'</td>  
              <td>'.$rowUser['username'].'</td> 
            </tr>
            ';
              }
            }
            return $output;
          }
if(isset($_GET["transaction_pdf"]))  
 {      
      $content = '';  
      $content .= '   
        <h2 align="center">Sinulatan 1st Agriculture Cooperative </h2>
        <p align="center">Brg. Sinulatan 1st, Camilling, Tarlac</p> 
        <p align="center">CDA Reg No. 9520-1030000000044540</p> 
        <p align="center">TIN: 748-678-180-000</p> 
        <h4 align="center">Rental Payment Transaction</h4>
        <p align="right">'.date('F, j Y').'</p>
        <table border="1" cellspacing="0" cellpadding="3" style="font-size: 10px">
                            <thead >
                              <tr style="font-weight: bold;">
                                <th>Payment ID</th>
                                <th>Member ID</th>
                                <th>Name</th>
                                <th>Equipment ID</th>
                                <th>Serial no.</th>
                                <th>Equipment Name</th>
                                <th>Rent Date</th>
                                <th>Due Date</th>
                                <th>Date Returned</th>
                                <th>Status</th>
                                <th>Amount</th>
                                <th>Paid</th>
                                <th>Date Paid</th>
                                <th>Process by</th>  
                            </tr>
                            </thead>';  
      $content .= fetch_Transaction();  
      $content .= '</table> 
          <pre><strong>
 

          Prepared by:                      Attested by:

          _____________________          ______________________
             SACoop Secretary                       SACoop President
          </strong></pre>
      ';  
      $filename ="Rental Paymen Transaction";
      Lpdf($content,$filename);  
 }  
 ?>

 <!-- Export Paid up Transaction-->
<?php
function fetch_paid_up_transaction(){
    $objtUser = new User();
     $objtEncrypt = new Encryption();
  $output='';
        $query = "SELECT puc_transaction.puc_id, member.member_id, member.Lname, member.Fname, member.Mname, puc_transaction.paid, puc_transaction.date_pay, register.username FROM puc_transaction INNER JOIN member ON member.member_id = puc_transaction.member_id INNER JOIN register ON register.user_id =  puc_transaction.user_id WHERE month(date_pay) = month(NOW()) AND YEAR(date_pay) = YEAR(NOW())";
          $stmt = $objtUser-> runQuery($query);
          $stmt->execute();

             $count= $stmt->rowCount();

              if($count > 0){
                while($rowUser = $stmt->fetch(PDO::FETCH_ASSOC)){      
                  $date_pay = date_create($rowUser['date_pay']);
           $output .='
            <tr>
             <td>'.$rowUser["puc_id"].'</td>
              <td>'.$rowUser["member_id"].'</td>
              <td>'.$objtEncrypt->decrypt($rowUser["Lname"]).'
                  '.$objtEncrypt->decrypt($rowUser["Fname"]).'
                  '.$objtEncrypt->decrypt($rowUser["Mname"]).'</td>
              <td>'.number_format($rowUser['paid'],2).'</td> 
              <td>'.date_format($date_pay,'M d, Y h:i: A').'</td> 
              <td>'.$rowUser['username'].'</td>   
            </tr>
            ';
              }
            }
            return $output;
          }
if(isset($_GET["puc_pdf"]))  
 {      
      
      $content = '';  
      $content .= '

        <h2 align="center">Sinulatan 1st Agriculture Cooperative </h2>
        <p align="center">Brg. Sinulatan 1st, Camilling, Tarlac</p> 
        <p align="center">CDA Reg No. 9520-1030000000044540</p> 
        <p align="center">TIN: 748-678-180-000</p> 
        <h4 align="center">Paid up Capital Transaction</h4>
        <p align="right">'.date('F, j Y').'</p> 
        <table border="1" cellspacing="0" cellpadding="3" style="font-size: 10px">
                            <thead>
                               <tr style="font-weight: bold;">
                                <th>Transaction ID</th>
                                <th>Member ID</th>
                                <th>Name</th>
                                <th>Paid</th>
                                <th>Date Paid</th>
                                <th>Process By</th>
                            </tr>
                            </thead>';  
      $content .= fetch_paid_up_transaction();  
      $content .= '</table> 
          <pre><strong>
 

          Prepared by:                      Attested by:

          _____________________          ______________________
             SACoop Secretary                       SACoop President
          </strong></pre>
      ';  
      $filename="Paid Up Capital Transaction";
      pdfx($content,$filename);  
 }  

 
//genrate monthly report
 function fetch_monthly_report(){
    $objtUser = new User();
     $objtEncrypt = new Encryption();
  $output='';
        $Rquery = "SELECT sum(pay) as rent from rent WHERE month(date_returned) = month(NOW()) AND YEAR(date_returned) = YEAR(NOW())";
         $Rstmt = $objtUser-> runQuery($Rquery);
         $Rstmt->execute();
         $rowRent = $Rstmt->fetch(PDO::FETCH_ASSOC);

        $Mquery ="SELECT SUM(reg_fee) as reg_fee from member where  month(registered_date) = month(NOW()) AND YEAR(registered_date) = YEAR(NOW())";
         $Mstmt = $objtUser-> runQuery($Mquery);
         $Mstmt->execute();
         $rowMember = $Mstmt->fetch(PDO::FETCH_ASSOC);

         $Puc_query="SELECT SUM(paid) as puc FROM puc_transaction WHERE month(date_pay) = month(NOW()) AND YEAR(date_pay) = YEAR(NOW())"; 
         $Puc_stmt = $objtUser-> runQuery($Puc_query);
         $Puc_stmt->execute();
         $rowPuc = $Puc_stmt->fetch(PDO::FETCH_ASSOC);

         $loan_query = "SELECT sum(total) as total from loan WHERE month(date_finish) = month(NOW()) AND YEAR(date_finish) = YEAR(NOW())";
         $loan_stmt = $objtUser-> runQuery($loan_query);
         $loan_stmt->execute();
         $rowLoan = $loan_stmt->fetch(PDO::FETCH_ASSOC);
            
          $total_earnings = $rowRent['rent'] + $rowMember['reg_fee'] + $rowPuc['puc']+$rowLoan['total'];
           $output .='
            <tr>
             <td>Equipment Rental</td>
             <td>'.number_format($rowRent["rent"],2).'</td>   
            </tr>

             <tr>
             <td>Registration Fee</td>
             <td>'.number_format($rowMember["reg_fee"],2).'</td>   
            </tr>

             <tr>
             <td>Paid Up Capital</td>
             <td>'.number_format($rowPuc["puc"],2).'</td>   
            </tr>

            <tr>
             <td>Paid Loan</td>
             <td>'.number_format($rowLoan["total"],2).'</td>   
            </tr>

            <tr>
             <td><strong>TOTAL Earnings</strong></td>
             <td>'.number_format($total_earnings,2).'</td>   
            </tr>
            
            ';
            return $output;
          }
if(isset($_GET["monthly_report"]))  
 {      
      $content = '';  
      //REntal records
      $content .= '
        <h2 align="center">Sinulatan 1st Agriculture Cooperative </h2>
        <p align="center">Brg. Sinulatan 1st, Camilling, Tarlac</p> 
        <p align="center">CDA Reg No. 9520-1030000000044540</p> 
        <p align="center">TIN: 748-678-180-000</p> 
        <h3 align="center">Monthly Report </h3> 
        <h4 align="center" >Rental Records</h4>
        <p align="right">'.date('F, j Y').'</p>
        <table border="1" cellspacing="0" cellpadding="3" align="center" style="font-size: 10px">
                            <thead>
                              <tr style="font-weight: bold;">
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
                            </thead>';  
      $content .= fetch_rent_records();  
      $content .= '</table> <br>'; 

      //paid up capital
      $content .= ' <p style="page-break-before: always">
        <h4 align="center">Paid up Capital Transaction</h4> 
        <table border="1" cellspacing="0" cellpadding="3" >
                            <thead>
                               <tr style="font-weight: bold;">
                                <th>Transaction ID</th>
                                <th>Member ID</th>
                                <th>Name</th>
                                <th>Paid</th>
                                <th>Date Paid</th>
                                <th>Process By</th>
                            </tr>
                            </thead>';  
      $content .= fetch_paid_up_transaction();  
      $content .= '</table> '; 
      //Loan Records 
      $content .= '  
        <br> <p style="page-break-before: always">
        <h4 align="center">Loan Records</h4> 
        <table border="1" cellspacing="0" cellpadding="3" align="center" style="font-size: 10px">
                            <thead>
                            <tr style="font-weight: bold;">
                            <th>Loan ID</th>
                            <th>Member ID</th>
                            <th>Name</th>
                            <th>Loan Amount</th>
                            <th>Date Start</th>
                            <th>Due Date</th>
                            <th>Date Pay</th>
                            <th>Status</th>
                            <th>Interest</th>
                            <th>Penalty</th>
                            <th>Total</th>
                            <th>Process By</th>
                            </tr>
                            </thead>';  
      $content .= fetch_loan_records();  
      $content .= '</table>'; 
      
      //Monthly generated report

       $content .= '<p style="page-break-before: always">
        <h4 align="center">Summary of Earnings</h4> 
        <table border="1" cellspacing="0" cellpadding="3">
                            <thead>
                               <tr style="font-weight: bold;">
                                 <th>Name</th>
                                <th>Earnings</th>             
                            </tr>
                            </thead>
                            ';  
      $content .= fetch_monthly_report(); 
      $content .= '</table> 
          <pre><strong>
 

          Prepared by:                      Attested by:

          _____________________          ______________________
             SACoop Secretary                       SACoop President
          </strong></pre>
      '; 

      $filename="Monthly Report";
      Lpdf($content,$filename);  
 } 
 ?>
<?php 
  if(isset($_GET["cor"]))  
  {      
      $member_id = $_GET['cor'];
      $stmt = $objtUser->runQuery("SELECT * FROM member WHERE member_id = :member_id");
      $stmt->execute(array(":member_id"=> $member_id));
      $rowUser = $stmt->fetch(PDO::FETCH_ASSOC);
      $Lname = $objtEncrypt->decrypt($rowUser['Lname']);
      $Fname = $objtEncrypt->decrypt($rowUser['Fname']);
      $Mname = $objtEncrypt->decrypt($rowUser['Mname']);
      $birthdate = date_create($objtEncrypt->decrypt($rowUser['birthdate']));
      $date_reg = date_create($rowUser['registered_date']);
      
       $content = '';  
       $content .= '  <br>
         <h2 align="center">Sinulatan 1st Agriculture Cooperative </h2>
         <p align="center">Brg. Sinulatan 1st, Camilling, Tarlac</p> 
         <p align="center">CDA Reg No. 9520-1030000000044540</p> 
         <p align="center">TIN: 748-678-180-000</p> 
         <h4 align="center">CERTIFICATE OF REGISTRATION</h4> ';
        $content.='
              <p>Member ID: <strong>'.$rowUser['member_id'].'</strong></p>
              <p>TIN: <strong>'.$objtEncrypt->decrypt($rowUser['TIN']).'</strong></p>
              <p>Member Name: <strong style="text-transform: uppercase;">'.$Lname.', '.$Fname.' '.$Mname.'.</strong></p>
              <p>Birth Date: <strong>'.date_format($birthdate,'F d, Y').'</strong></p>
              <p>Spouse Name: <strong>'.$objtEncrypt->decrypt($rowUser['spouse_name']).'</strong></p>
              <p>Address: <strong>'.$objtEncrypt->decrypt($rowUser['address']).'</strong></p>
              <p>Phone: <strong>'.$objtEncrypt->decrypt($rowUser['contactno']).'</strong></p>
              <p>Land Size: <strong>'.$objtEncrypt->decrypt($rowUser['land_size']).'</strong></p>
              <p>Land Location: <strong>'.$objtEncrypt->decrypt($rowUser['land_location']).'</strong></p>
              <hr>
              <p>Types of Crop Planted:</p>
              <p>First Crop: <strong>'.$objtEncrypt->decrypt($rowUser['crop1']).'</strong>
              <br>Second Crop: <strong>'.$objtEncrypt->decrypt($rowUser['crop2']).'</strong>
              <br>Third Crop: <strong>'.$objtEncrypt->decrypt($rowUser['crop3']).'</strong>
              </p>
              <hr>
              
              <p>Currency: <strong>PHP</strong>
              <br>Capital Build Up: <strong>'.number_format($rowUser['capital_build_up'],2).'</strong></p>
              <p>Paid Up Capital: <strong>'.number_format($rowUser['paid_up_capital'],2).'</strong></p>
              <p>Date Registered: <strong>'.date_format($date_reg,'F d, Y h:i A').'</strong></p>
              <br>
        ';                    
         $content .= "<pre> <strong>
                                                                                                                          __________________
                                                                                                                          Member Signature
           Prepared by:                      Attested by:
 
           ____________________              _________________________
              SACoop Secretary                    SACoop President
           </pre></strong>
         ";
         $filename="Member COR";
       pdfx($content,$filename);  
  } 


?>

<!-- Loan Records -->
<?php
function fetch_loan_records(){
    $objtUser = new User();
    $objtEncrypt = new Encryption();
    $output='';
        $query = "SELECT loan.loan_id, member.member_id, member.Lname, member.Fname, member.Mname, loan.based, loan.date_start, loan.due_date, loan.date_finish, loan.interest, loan.penalty, loan.total, register.username FROM loan INNER JOIN member ON member.member_id = loan.member_id INNER JOIN register ON register.user_id = loan.user_id WHERE month(date_finish) = month(NOW()) AND YEAR(date_finish) = YEAR(NOW()) GROUP BY loan_id DESC ";
          $stmt = $objtUser-> runQuery($query);
          $stmt->execute();

             $count= $stmt->rowCount();

              if($count > 0){
                while($rowLoan = $stmt->fetch(PDO::FETCH_ASSOC)){
                  $date_start = date_create($rowLoan['date_start']);
                  $date_finish = date_create($rowLoan['date_finish']);
                  $due_date = date_create($rowLoan['due_date']);
                  $date1=date_create($rowLoan['date_start']);
                  $date2=date_create($rowLoan['date_finish']);
                  $diff=date_diff($date1,$date2);
                  $number_of_days=$diff->format("%a");

                  if($number_of_days%120 == 0 && !($number_of_days/120 >1) ){
                     $status = "Ontime";
                     $color = "text-success";
                  }else{
                     $status = "Late";
                     $color = "text-danger";
                  }
           $output .='
            <tr>
              <td>'.$rowLoan["loan_id"].'</td>
             <td>'.$rowLoan["member_id"].'</td>
             <td>'.$objtEncrypt->decrypt($rowLoan["Lname"]).'
                  '.$objtEncrypt->decrypt($rowLoan["Fname"]).'
                  '.$objtEncrypt->decrypt($rowLoan["Mname"]).'</td>
              <td>'.number_format($rowLoan["based"],2).'</td>
              <td>'.date_format($date_start,'F d, Y h:i: A').'</td> 
              <td>'.date_format($due_date,'F d, Y h:i: A').'</td> 
              <td>'.date_format($date_finish,'F d, Y h:i: A').'</td> 
             <td>'.$status.'</td>
             <td>'.number_format($rowLoan["interest"],2).'</td>
             <td>'.number_format($rowLoan['penalty'],2).'</td>
             <td>'.number_format($rowLoan['total'],2).'</td>
             <td>'.$rowLoan['username'].'</td>
            </tr>
            ';
              }
            }
            return $output;
          }
if(isset($_GET["loan_pdf"]))  
 {      
      $content = '';  
      $content .= '  
        <h2 align="center">Sinulatan 1st Agriculture Cooperative </h2>
        <p align="center">Brg. Sinulatan 1st, Camilling, Tarlac</p> 
        <p align="center">CDA Reg No. 9520-1030000000044540</p> 
        <p align="center">TIN: 748-678-180-000</p> 
        <h4 align="center">Loan Records</h4> 
        <p align="right">'.date('F, j Y').'</p>
        <table border="1" cellspacing="0" cellpadding="3" align="center" style="font-size: 10px">
                            <thead>
                            <tr style="font-weight: bold;">
                            <th>Loan ID</th>
                            <th>Member ID</th>
                            <th>Name</th>
                            <th>Loan Amount</th>
                            <th>Date Start</th>
                            <th>Due Date</th>
                            <th>Date Pay</th>
                            <th>Status</th>
                            <th>Interest</th>
                            <th>Penalty</th>
                            <th>Total</th>
                            <th>Process By</th>
                            </tr>
                            </thead>';  
      $content .= fetch_loan_records();  
      $content .= '</table> 
          <pre><strong>
 

          Prepared by:                      Attested by:

          _____________________          ______________________
             SACoop Secretary                       SACoop President
          </strong></pre>
      ';   
      $filename="Loan Records";
      Lpdf($content,$filename);  
 }  
 ?>
 <!-- REceipt-->
<?php
    //Print rent form
    if(isset($_GET['rent_form'])){
      $rent_id = $_GET['rent_form'];
  
      $objtUser = new User();
      $objtEncrypt = new Encryption();
      $output='';
      $query = "SELECT rent.rent_id, member.member_id, member.Lname, member.Fname, member.Mname, equipment.eqp_id, equipment.serial_no, equipment.eqp_name,equipment.rent_price, rent.rent_date, rent.due_date, rent.amount, rent.pay, rent.amount-rent.pay as Balance FROM rent INNER JOIN member on member.member_id = rent.member_id INNER JOIN equipment on equipment.eqp_id = rent.eqp_id Where rent_id = :rent_id";
      $stmt = $objtUser-> runQuery($query);
      $stmt->execute(array(":rent_id"=> $rent_id));
      $rowRent = $stmt->fetch(PDO::FETCH_ASSOC);
  
      $rent_date = date_create($rowRent['rent_date']);
      $due_date = date_create($rowRent['due_date']);
      $Lname = $objtEncrypt->decrypt($rowRent['Lname']);
      $Fname = $objtEncrypt->decrypt($rowRent['Fname']);
      $Mname = $objtEncrypt->decrypt($rowRent['Mname']);

      $content = '';  
      $content .= '  <br>
        <h2 align="center">Sinulatan 1st Agriculture Cooperative </h2>
        <p align="center">Brg. Sinulatan 1st, Camilling, Tarlac</p> 
        <p align="center">CDA Reg No. 9520-1030000000044540</p> 
        <p align="center">TIN: 748-678-180-000</p> 
        <h4 align="center">Rent Assessment</h4> 
        <p align="right">'.date_format($rent_date,'F d, Y h:i  A').'</p>
        ';
        
       $content.='
             <p>Rent ID: <strong>'.$rowRent['rent_id'].'</strong>
             <br>Member ID: <strong>'.$rowRent['member_id'].'</strong>
             <br>Name: <strong style="text-transform: uppercase;">'.$Lname.', '.$Fname.' '.$Mname.'.</strong>
             </p>
             <hr>
             <p>Rent Details</p>
             Currency: <strong>PHP</strong>
             <p>Date Rented: <strong> '.date_format($rent_date,'F d, Y h:i A').'</strong> 
             <br>Due Date: <strong> '.date_format($due_date,'F d, Y h:i A').'</strong>
             <br>Equipment ID:<strong> '.$rowRent['eqp_id'].'</strong>
             <br>Equipment Serial no.: <strong> '.$objtEncrypt->decrypt($rowRent['serial_no']).'</strong>
             <br>Equipment Rental Price:<strong> '.$rowRent['rent_price'].'</strong>
             <br>Equipment Name: <strong> '.$objtEncrypt->decrypt($rowRent['eqp_name']).'</strong>
             <br>Total Amount to pay: <strong> '.number_format($rowRent['amount'],2).'</strong>
             <br>Note: *Please return the rented equipment in time to avoid penalty. Thank you
             </p>
             <br>
             <pre><strong>
                                                                                                                          __________________
                                                                                                                            Member Signature
Prepared by:                    Attested by:  

________________            _________________                
SACoop Treasurer             SACoop President

<strong></pre>
       ';
       $filename='Rent Assessment';             
       pdfx($content, $filename); 
    }

  //Rent Pay receipt
  if(isset($_GET['receipt'])){
    $pay_id = $_GET['receipt'];

    $objtUser = new User();
    $objtEncrypt = new Encryption();
    $output='';
    $query = "SELECT rent_payment.pay_id,rent.rent_id, member.Lname,member.Fname,member.Mname, member.member_id,equipment.eqp_id,equipment.eqp_name, equipment.serial_no,equipment.rent_price, rent.rent_date, rent.due_date, rent.date_returned, rent_payment.amount, rent_payment.paid, rent_payment.date_pay,(TIMESTAMPDIFF(hour,rent.due_date,rent.date_returned)*equipment.rent_price/24) as penalty, register.username from rent_payment LEFT JOIN rent ON rent.rent_id = rent_payment.rent_id LEFT JOIN member on member.member_id = rent.member_id LEFT JOIN equipment on equipment.eqp_id = rent.eqp_id LEFT JOIN register ON register.user_id = rent_payment.user_id  WHERE pay_id = :pay_id GROUP BY rent_payment.pay_id DESC";
    $stmt = $objtUser-> runQuery($query);
    $stmt->execute(array(":pay_id"=> $pay_id));
    $rowReceipt = $stmt->fetch(PDO::FETCH_ASSOC);

    $rent_date = date_create($rowReceipt['rent_date']);
    $due_date =  date_create($rowReceipt['due_date']);
    $date_pay = date_create($rowReceipt['date_pay']);
    $date_returned = date_create($rowReceipt['date_returned']);
    $Lname = $objtEncrypt->decrypt($rowReceipt['Lname']);
    $Fname = $objtEncrypt->decrypt($rowReceipt['Fname']);
    $Mname = $objtEncrypt->decrypt($rowReceipt['Mname']);
    if($rowReceipt['penalty']<=0){
      $penalty = 0;      
      $total_amount = $rowReceipt['amount'] + $penalty;
      $status = "Ontime";
    }else{
      $penalty = $rowReceipt['penalty'];                                
      $total_amount = $rowReceipt['amount'] + $penalty;
      $status = "Late";
    }
    $balance = $total_amount - $rowReceipt['paid'];
    $content = '';  
    $content .= '  <br>
      <h2 align="center">Sinulatan 1st Agriculture Cooperative </h2>
      <p align="center">Brg. Sinulatan 1st, Camilling, Tarlac</p> 
      <p align="center">CDA Reg No. 9520-1030000000044540</p> 
      <p align="center">TIN: 748-678-180-000</p> 
      <h4 align="center">Rental Receipt</h4> 
      <p align="right">'.date_format($date_pay,'F d, Y h:i A').'</p>
      ';
      
     $content.='
           <p>Payment ID: <strong>'.$rowReceipt['pay_id'].'</strong>
           <br>Rent ID: <strong>'.$rowReceipt['rent_id'].'</strong>
           <br>Member ID: <strong>'.$rowReceipt['member_id'].'</strong>
           <br>Name: <strong style="text-transform: uppercase;">'.$Lname.', '.$Fname.' '.$Mname.'.</strong>
           </p>
           <hr>
           <p>Equipment Borrowed</p>
           Currency: <strong>PHP</strong>
           <p>Serial no.: <strong>'.$objtEncrypt->decrypt($rowReceipt['serial_no']).'</strong>
            <br>Equipment Name: <strong>'.$objtEncrypt->decrypt($rowReceipt['eqp_name']).'</strong>
            <br>Rental Price: <strong>'.number_format($rowReceipt['rent_price']).'</strong>
           </p>
           <hr>
           <p>Rent Details</p>
           <p>Date Rented: <strong>'.date_format($rent_date,'F d, Y h:i A').'</strong> 
           <br>Due Date: <strong>'.date_format($due_date,'F d, Y h:i A').'</strong>
           <br>Date Returned: <strong>'.date_format($date_returned,'F d, Y h:i A').'</strong>
           </p>
           <hr>
           <p>Payment Details</p>
           <p>Amount: <strong>'.number_format($rowReceipt['amount'],2).'</strong>
           <br>Rent Status: <strong>'.$status.'</strong>
           <br>Penalty: <strong>'.number_format($penalty,2).'</strong>
           <br>Amount to pay: <strong>'.number_format($total_amount,2).'</strong>
           <br>Paid: <strong>'.number_format($rowReceipt['paid'],2).'</strong>
           <br>Balance: <strong>'.number_format($balance,2).'</strong>
          </p>
          <pre><strong>
                                                                                                                            __________________
                                                                                                                              Member Signature
Prepared by:                    Attested by:  

________________            _________________                
SACoop Treasurer             SACoop President

<strong></pre>
          
     ';                    
     $filename='Rental Receipt';             
     pdfx($content, $filename); 
  }


//Print Capital Build up Receipt
if(isset($_GET['puc_receipt'])){
  $puc_id = $_GET['puc_receipt'];

  $objtUser = new User();
  $objtEncrypt = new Encryption();
  $output='';
  $query = "SELECT puc_transaction.puc_id, member.member_id, member.Lname, member.Fname, member.Mname, member.capital_build_up, member.paid_up_capital, puc_transaction.paid, puc_transaction.date_pay, register.username FROM puc_transaction INNER JOIN member ON member.member_id = puc_transaction.member_id INNER JOIN register ON register.user_id =  puc_transaction.user_id WHERE puc_id = :puc_id";
  $stmt = $objtUser-> runQuery($query);
  $stmt->execute(array(":puc_id"=> $puc_id));
  $rowPUC = $stmt->fetch(PDO::FETCH_ASSOC);

  $date = date_create($rowPUC['date_pay']);
  $Lname = $objtEncrypt->decrypt($rowPUC['Lname']);
  $Fname = $objtEncrypt->decrypt($rowPUC['Fname']);
  $Mname = $objtEncrypt->decrypt($rowPUC['Mname']);

  $content = '';  
  $content .= '  <br>
    <h2 align="center">Sinulatan 1st Agriculture Cooperative </h2>
    <p align="center">Brg. Sinulatan 1st, Camilling, Tarlac</p> 
    <p align="center">CDA Reg No. 9520-1030000000044540</p> 
    <p align="center">TIN: 748-678-180-000</p> 
    <h4 align="center">Paid Up Capital Receipt</h4> 
    <p align="right">'.date_format($date,'F d, Y h:i  A').'</p>
    ';
    
   $content.='
         <p>Payment ID: <strong>'.$rowPUC['puc_id'].'</strong>
         <br>Member ID: <strong>'.$rowPUC['member_id'].'</strong>
         <br>Name: <strong style="text-transform: uppercase;">'.$Lname.', '.$Fname.' '.$Mname.'.</strong>
         </p>
         <hr>
         <p>Capital Build UP Details</p>
         Currency: <strong>PHP</strong>
         <br>Capital Build UP:<strong> '.number_format($rowPUC['capital_build_up'],2).'</strong>
         <br>Paid: <strong> '.number_format($rowPUC['paid'],2).'</strong>
         </p>
         <br>
         <pre><strong>
                                                                      __________________
                                                                        Member Signature
          Prepared by:                    Attested by:  

          ________________            _________________                
          SACoop Treasurer             SACoop President

          <strong></pre>
   ';        
   $filename='Paid Up Capital Receipt';             
  pdfx($content, $filename); 
}

//Print Loan Form
if(isset($_GET['loan_form'])){
  $loan_id = $_GET['loan_form'];

  $objtUser = new User();
  $objtEncrypt = new Encryption();
  $output='';
  $query = "SELECT loan.loan_id, member.member_id, member.Lname, member.Fname, member.Mname, loan.based, loan.date_start, loan.due_date, loan.date_finish, loan.interest, loan.penalty, loan.total, register.username FROM loan INNER JOIN member ON member.member_id = loan.member_id INNER JOIN register ON register.user_id = loan.user_id WHERE loan_id = :loan_id";
  $stmt = $objtUser-> runQuery($query);
  $stmt->execute(array(":loan_id"=> $loan_id));
  $rowLoan = $stmt->fetch(PDO::FETCH_ASSOC);

  $date_start = date_create($rowLoan['date_start']);
  $due_date = date_create($rowLoan['due_date']);
  $interest = $rowLoan['based'] * 0.05;
  $estimated_amount = $rowLoan['based']+$interest;
  $Lname = $objtEncrypt->decrypt($rowLoan['Lname']);
  $Fname = $objtEncrypt->decrypt($rowLoan['Fname']);
  $Mname = $objtEncrypt->decrypt($rowLoan['Mname']);
  $content = '';  
  $content .= '  <br>
    <h2 align="center">Sinulatan 1st Agriculture Cooperative </h2>
    <p align="center">Brg. Sinulatan 1st, Camilling, Tarlac</p> 
    <p align="center">CDA Reg No. 9520-1030000000044540</p> 
    <p align="center">TIN: 748-678-180-000</p> 
    <h4 align="center">Loan Assessment</h4> 
    <p align="right">'.date_format($date_start,'F d, Y ').'</p>
    ';
    
   $content.='
         <p>Loan ID: <strong>'.$rowLoan['loan_id'].'</strong>
         <br>Member ID: <strong>'.$rowLoan['member_id'].'</strong>
         <br>Name: <strong style="text-transform: uppercase;">'.$Lname.', '.$Fname.' '.$Mname.'.</strong>
         </p>
         <hr>
         <p>Loan Details</p>
         Currency: <strong>PHP</strong>
         <br>Based Amount Loan:<strong> '.number_format($rowLoan['based'],2).'</strong>
         <br>Interest Rate:<strong> (5%)</strong>
         <br>Interest: <strong> '.number_format($interest,2).'</strong>
         <br>Total amount to pay (if no penalty): <strong> '.number_format($estimated_amount,2).'</strong>
         <br>Due Date: <strong> '.date_format($due_date,'F d, Y ').'</strong>
         <br>Note: *Please pay the total amount on time to avoid penalty. Thank you
         </p>
         <br>
        <pre><strong>
                                                                                                                            __________________
                                                                                                                              Member Signature
        Prepared by:                Attested by:  

        ________________            _________________                
        SACoop Treasurer             SACoop President
                  
        <strong></pre>
   ';  
   $filename='Loan Assessment';                 
  pdfx($content,$filename); 
}

//Print Loan Receipt
if(isset($_GET['loan_receipt'])){
  $loan_id = $_GET['loan_receipt'];

  $objtUser = new User();
  $objtEncrypt = new Encryption();
  $output='';
  $query = "SELECT loan.loan_id, member.member_id, member.Lname, member.Fname, member.Mname, loan.based, loan.date_start, loan.due_date, loan.date_finish, loan.interest, loan.penalty, loan.total, register.username FROM loan INNER JOIN member ON member.member_id = loan.member_id INNER JOIN register ON register.user_id = loan.user_id WHERE loan_id = :loan_id";
  $stmt = $objtUser-> runQuery($query);
  $stmt->execute(array(":loan_id"=> $loan_id));
  $rowLoan = $stmt->fetch(PDO::FETCH_ASSOC);

  $date_start = date_create($rowLoan['date_start']);
  $date_finish = date_create($rowLoan['date_finish']);
  $due_date = date_create($rowLoan['due_date']);
  $date1=date_create($rowLoan['date_start']);
  $date2=date_create($rowLoan['date_finish']);
  $diff=date_diff($date1,$date2);
  $number_of_days=(int)$diff->format("%a");
  $number = $number_of_days/12;
  if($number_of_days<120){
    $status = "Ontime";
  }else{
    $status = "Late";
  }

  $Lname = $objtEncrypt->decrypt($rowLoan['Lname']);
  $Fname = $objtEncrypt->decrypt($rowLoan['Fname']);
  $Mname = $objtEncrypt->decrypt($rowLoan['Mname']);
  $content = '';  
  $content .= '  <br>
    <h2 align="center">Sinulatan 1st Agriculture Cooperative </h2>
    <p align="center">Brg. Sinulatan 1st, Camilling, Tarlac</p> 
    <p align="center">CDA Reg No. 9520-1030000000044540</p> 
    <p align="center">TIN: 748-678-180-000</p> 
    <h4 align="center">Loan Receipt</h4> 
    <p align="right">'.date_format($date_finish,'F d, Y ').'</p>
    ';
    
   $content.='
         <p>Loan ID: <strong>'.$rowLoan['loan_id'].'</strong>
         <br>Member ID: <strong>'.$rowLoan['member_id'].'</strong>
         <br>Name: <strong style="text-transform: uppercase;">'.$Lname.', '.$Fname.' '.$Mname.'.</strong>
         </p>
         <hr>
         <p>Loan Details</p>
         Currency: <strong>PHP</strong>
         <br>Date Start: <strong> '.date_format($date_start,'F d, Y ').'</strong>
         <br>Due Date: <strong> '.date_format($due_date,'F d, Y ').'</strong>
         <br>Date Pay: <strong> '.date_format($date_finish,'F d, Y ').'</strong>
         <br>Based Amount Loan:<strong> '.number_format($rowLoan['based']).'</strong>
         <br>Interest: <strong> '.number_format($rowLoan['interest'],2).'</strong>
         <br>Status: <strong>'.$status.'</strong>
         <br>Penalty: <strong> '.number_format($rowLoan['penalty'],2).'</strong>
         <br>Total: <strong> '.number_format($rowLoan['total'],2).'</strong>
         </p>
         <br>
        <pre><strong>
                                                                                                                          __________________
                                                                                                                            Member Signature
Prepared by:                    Attested by:  

________________            _________________                
SACoop Treasurer             SACoop President

<strong></pre>
      ';  
    $filename='Loan Receipt';                 
    pdfx($content,$filename); 
}

?>
