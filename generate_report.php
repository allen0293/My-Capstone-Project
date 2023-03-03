<?php
date_default_timezone_set('Asia/Manila');
include('includes/security.php');
require_once 'classes/user.php';
include('includes/header.php'); 
require_once 'classes/encryption.php';
$objtEncrypt = new Encryption();
$objtUser = new User();
    
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
      ///$obj_pdf->Image('img/logo.jpg',60, 10, 30,'JPG');      
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

if(isset($_POST['generate_month'])){
$month = $_POST['month'];
$year = $_POST['year'];

function fetch_rent_records(){
    $month = $_POST['month'];
    $year = $_POST['year'];
    
    $objtUser = new User();
    $objtEncrypt = new Encryption();
    $output='';
        $query = "SELECT rent.rent_id, member.member_id, member.Lname, member.Fname, member.Mname, equipment.eqp_id ,equipment.serial_no, equipment.eqp_name,equipment.rent_price, rent.rent_date, rent.due_date, rent.date_returned, (TIMESTAMPDIFF(day,rent.rent_date,rent.due_date)*equipment.rent_price) amount,(TIMESTAMPDIFF(hour,rent.due_date,rent.date_returned)*equipment.rent_price/24) as penalty, rent.pay FROM rent INNER JOIN member on member.member_id = rent.member_id INNER JOIN equipment on equipment.eqp_id = rent.eqp_id  WHERE monthname(date_returned) = :months AND YEAR(date_returned) = :years GROUP BY rent_id DESC ";
          $stmt = $objtUser-> runQuery($query);
          $stmt->execute(['months' => $month, 'years' => $year]);

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

function fetch_Transaction(){
    $month = $_POST['month'];
    $year = $_POST['year'];
    $objtUser = new User();
    $objtEncrypt = new Encryption();
    $output='';
       $query = "SELECT rent_payment.pay_id,rent.rent_id, member.Lname,member.Fname,member.Mname, member.member_id,equipment.eqp_id,equipment.eqp_name, equipment.serial_no, rent.rent_date, rent.due_date, rent.date_returned, rent_payment.amount, rent_payment.paid, rent_payment.date_pay,(TIMESTAMPDIFF(hour,rent.due_date,rent.date_returned)*equipment.rent_price/24) as penalty, register.username from rent_payment LEFT JOIN rent ON rent.rent_id = rent_payment.rent_id LEFT JOIN member on member.member_id = rent.member_id LEFT JOIN equipment on equipment.eqp_id = rent.eqp_id LEFT JOIN register ON register.user_id = rent_payment.user_id  WHERE monthname(date_pay) = :months AND YEAR(date_pay) = :years ORDER BY rent_payment.pay_id DESC";
          $stmt = $objtUser-> runQuery($query);
          $stmt->execute(['months' => $month, 'years' => $year]); 

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
                    $penalty = $rowUser['penalty'];                                
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
              <td>'.date_format($date_returned,'M d, Y h:i A').'</td>
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

 
function fetch_paid_up_transaction(){
    $month = $_POST['month'];
    $year = $_POST['year'];
    $objtUser = new User();
    $objtEncrypt = new Encryption();
    $output='';
        $query = "SELECT puc_transaction.puc_id, member.member_id, member.Lname, member.Fname, member.Mname, puc_transaction.paid, puc_transaction.date_pay, register.username FROM puc_transaction INNER JOIN member ON member.member_id = puc_transaction.member_id INNER JOIN register ON register.user_id =  puc_transaction.user_id WHERE monthname(date_pay) = :months AND YEAR(date_pay) = :years";
          $stmt = $objtUser-> runQuery($query);
          $stmt->execute(['months' => $month, 'years' => $year]); 

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
//loan Recrods 

function fetch_loan_records(){
  $month = $_POST['month'];
  $year = $_POST['year'];
  $objtUser = new User();
  $objtEncrypt = new Encryption();
  $output='';
      $query = "SELECT loan.loan_id, member.member_id, member.Lname, member.Fname, member.Mname, loan.based, loan.date_start, loan.due_date, loan.date_finish, loan.interest, loan.penalty, loan.total, register.username FROM loan INNER JOIN member ON member.member_id = loan.member_id INNER JOIN register ON register.user_id = loan.user_id WHERE monthname(date_finish) = :months AND YEAR(date_finish) = :years AND date_finish IS NOT NULL GROUP BY loan_id DESC ";
        $stmt = $objtUser-> runQuery($query);
        $stmt->execute(['months' => $month, 'years' => $year]);

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
 //genrate report
 function fetch_monthly_report(){
    $month = $_POST['month'];
    $year = $_POST['year'];
    $objtUser = new User();
    $objtEncrypt = new Encryption();
  $output='';
         $Rquery = "SELECT sum(pay) as rent from rent WHERE monthname(date_returned) = :months AND YEAR(date_returned) = :years AND date_returned IS NOT NULL";
         $Rstmt = $objtUser-> runQuery($Rquery);
         $Rstmt->execute(['months' => $month, 'years' => $year]);
         $rowRent = $Rstmt->fetch(PDO::FETCH_ASSOC);

        $Mquery ="SELECT SUM(reg_fee) as reg_fee from member WHERE monthname(registered_date) = :months AND YEAR(registered_date) = :years";
         $Mstmt = $objtUser-> runQuery($Mquery);
         $Mstmt->execute(['months' => $month, 'years' => $year]);
         $rowMember = $Mstmt->fetch(PDO::FETCH_ASSOC);

         $Puc_query="SELECT SUM(paid) as puc FROM puc_transaction WHERE monthname(date_pay) = :months AND YEAR(date_pay) = :years"; 
         $Puc_stmt = $objtUser-> runQuery($Puc_query);
         $Puc_stmt->execute(['months' => $month, 'years' => $year]);
         $rowPuc = $Puc_stmt->fetch(PDO::FETCH_ASSOC);
         
         $loan_query = "SELECT sum(total) as total from loan WHERE monthname(date_finish) = :months AND YEAR(date_finish) = :years";
         $loan_stmt = $objtUser-> runQuery($loan_query);
         $loan_stmt->execute(['months' => $month, 'years' => $year]);
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
     
      $content = '';  
      //REntal records
      $content .= '
        <h2 align="center">Sinulatan 1st Agriculture Cooperative </h2>
        <p align="center">Brg. Sinulatan 1st, Camilling, Tarlac</p> 
        <p align="center">CDA Reg No. 9520-1030000000044540</p> 
        <p align="center">TIN: 748-678-180-000</p> 
        <h3 align="center">Report as of '.$month.' '.$year.'  </h3> 
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

      //loan Records
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
      $filename='Report as of '.$month.' '.$year.'';
      Lpdf($content,$filename);  
}
 ?>




<!-- GENERATE YEAR -->
 <?php 
 if(isset($_POST['generate_year'])){
  $year = $_POST['years'];
  
  function fetch_rent_records_years(){
      $year = $_POST['years'];
      
      $objtUser = new User();
      $objtEncrypt = new Encryption();
      $output='';
          $query = "SELECT rent.rent_id, member.member_id, member.Lname, member.Fname, member.Mname, equipment.eqp_id ,equipment.serial_no, equipment.eqp_name,equipment.rent_price, rent.rent_date, rent.due_date, rent.date_returned, (TIMESTAMPDIFF(day,rent.rent_date,rent.due_date)*equipment.rent_price) amount,(TIMESTAMPDIFF(hour,rent.due_date,rent.date_returned)*equipment.rent_price/24) as penalty, rent.pay FROM rent INNER JOIN member on member.member_id = rent.member_id INNER JOIN equipment on equipment.eqp_id = rent.eqp_id  WHERE YEAR(date_returned) = :years GROUP BY rent_id DESC ";
            $stmt = $objtUser-> runQuery($query);
            $stmt->execute(['years' => $year]);
  
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
  
  function fetch_Transaction_years(){
      $year = $_POST['years'];
      $objtUser = new User();
      $objtEncrypt = new Encryption();
      $output='';
         $query = "SELECT rent_payment.pay_id,rent.rent_id, member.Lname,member.Fname,member.Mname, member.member_id,equipment.eqp_id,equipment.eqp_name, equipment.serial_no, rent.rent_date, rent.due_date, rent.date_returned, rent_payment.amount, rent_payment.paid, rent_payment.date_pay,(TIMESTAMPDIFF(hour,rent.due_date,rent.date_returned)*equipment.rent_price/24) as penalty, register.username from rent_payment LEFT JOIN rent ON rent.rent_id = rent_payment.rent_id LEFT JOIN member on member.member_id = rent.member_id LEFT JOIN equipment on equipment.eqp_id = rent.eqp_id LEFT JOIN register ON register.user_id = rent_payment.user_id  WHERE YEAR(date_pay) = :years ORDER BY rent_payment.pay_id DESC";
            $stmt = $objtUser-> runQuery($query);
            $stmt->execute(['years' => $year]); 
  
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
                      $penalty = $rowUser['penalty'];                                
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
  
   
  function fetch_paid_up_transaction_years(){
      $year = $_POST['years'];
      $objtUser = new User();
      $objtEncrypt = new Encryption();
      $output='';
          $query = "SELECT puc_transaction.puc_id, member.member_id, member.Lname, member.Fname, member.Mname, puc_transaction.paid, puc_transaction.date_pay, register.username FROM puc_transaction INNER JOIN member ON member.member_id = puc_transaction.member_id INNER JOIN register ON register.user_id =  puc_transaction.user_id WHERE YEAR(date_pay) = :years";
            $stmt = $objtUser-> runQuery($query);
            $stmt->execute(['years' => $year]); 
  
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
  //loan Recrods 
  
  function fetch_loan_records_years(){
    $year = $_POST['years'];
    $objtUser = new User();
    $objtEncrypt = new Encryption();
    $output='';
        $query = "SELECT loan.loan_id, member.member_id, member.Lname, member.Fname, member.Mname, loan.based, loan.date_start, loan.due_date, loan.date_finish, loan.interest, loan.penalty, loan.total, register.username FROM loan INNER JOIN member ON member.member_id = loan.member_id INNER JOIN register ON register.user_id = loan.user_id WHERE YEAR(date_finish) = :years AND date_finish IS NOT NULL GROUP BY loan_id DESC ";
          $stmt = $objtUser-> runQuery($query);
          $stmt->execute(['years' => $year]);
  
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
   //genrate report
   function fetch_yearly_report(){
      $year = $_POST['years'];
      $objtUser = new User();
      $objtEncrypt = new Encryption();
    $output='';
           $Rquery = "SELECT sum(pay) as rent from rent WHERE  YEAR(date_returned) = :years AND date_returned IS NOT NULL";
           $Rstmt = $objtUser-> runQuery($Rquery);
           $Rstmt->execute(['years' => $year]);
           $rowRent = $Rstmt->fetch(PDO::FETCH_ASSOC);
  
          $Mquery ="SELECT SUM(reg_fee) as reg_fee from member WHERE YEAR(registered_date) = :years";
           $Mstmt = $objtUser-> runQuery($Mquery);
           $Mstmt->execute(['years' => $year]);
           $rowMember = $Mstmt->fetch(PDO::FETCH_ASSOC);
  
           $Puc_query="SELECT SUM(paid) as puc FROM puc_transaction WHERE YEAR(date_pay) = :years"; 
           $Puc_stmt = $objtUser-> runQuery($Puc_query);
           $Puc_stmt->execute(['years' => $year]);
           $rowPuc = $Puc_stmt->fetch(PDO::FETCH_ASSOC);
           
           $loan_query = "SELECT sum(total) as total from loan WHERE YEAR(date_finish) = :years";
           $loan_stmt = $objtUser-> runQuery($loan_query);
           $loan_stmt->execute(['years' => $year]);
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
       
        $content = '';  
        //REntal records
        $content .= '
          <h2 align="center">Sinulatan 1st Agriculture Cooperative </h2>
          <p align="center">Brg. Sinulatan 1st, Camilling, Tarlac</p> 
          <p align="center">CDA Reg No. 9520-1030000000044540</p> 
          <p align="center">TIN: 748-678-180-000</p> 
          <h3 align="center">Report as of '.$year.'  </h3> 
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
        $content .= fetch_rent_records_years();  
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
        $content .= fetch_paid_up_transaction_years();  
        $content .= '</table> '; 
        
        //loan Records
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
        $content .= fetch_loan_records_years();  
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
        $content .= fetch_yearly_report(); 
        $content .= '</table> 
            <pre><strong>
   
  
            Prepared by:                      Attested by:
  
            _____________________          ______________________
               SACoop Secretary                       SACoop President
            </strong></pre>
        '; 
        $filename='Report as of  '.$year.'';
      Lpdf($content,$filename);  
  }
   ?>
 
 
 
 ?>