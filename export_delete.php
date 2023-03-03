<?php
date_default_timezone_set('Asia/Manila');
include('includes/security.php');
require_once 'classes/rent.php';
require_once 'classes/encryption.php';
require_once 'classes/payment.php';
require_once 'classes/user.php';
$objtUser = new User();
$objtEncrypt = new Encryption();
$objtRent = new Rent();
$objtPayment = new Payment();
  
   //PDF FUNCTION
  function pdf($content,$filename){  
      require_once('tcpdf_min/tcpdf.php');  
      $obj_pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
      $obj_pdf->SetTitle("Sinulatan 1st Coopertive");  
      $obj_pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);  
      $obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));  
      $obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));  
      $obj_pdf->SetDefaultMonospacedFont('helvetica');  
      $obj_pdf->setPageOrientation('L'); 
      $obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);  
      $obj_pdf->SetMargins(PDF_MARGIN_LEFT, '5', PDF_MARGIN_RIGHT);  
      $obj_pdf->setPrintHeader(false);  
      $obj_pdf->setPrintFooter(true);  
      $obj_pdf->SetAutoPageBreak(TRUE, 10);  
      $obj_pdf->SetFont('helvetica', '', 11);  
      $obj_pdf->AddPage(); 
      //$obj_pdf->Image('img/logo.jpg',10, 10, 30,'JPG');      
      
      $obj_pdf->writeHTML($content);  
      $tDate = date("F j, Y");
      $obj_pdf->Cell(0, 10, 'Date : '.$tDate, 0, false, 'R', 0, '', 0, false, 'T', 'M');
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

function Ppdf($content,$filename){  
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
?>


<!-- START OF RENT RECORD CODE -->
<?php
 
//export PDF
if (isset($_POST['rent_record_pdf'])) {
     
    if (empty($_POST['no'])) {
        header('Location: export.php?rent_record_pdf');
    }else{
      $rent_id = []; $member_id = []; $Lname = []; $Fname = []; $Mname = []; $eqp_id = [];
      $eqp_serial = []; $eqp_name = []; $rent_date = []; $due_date = []; $date_returned = [];
      $amount=[]; $arr_penalty=[]; $arr_total_amount=[]; $arr_status=[]; $pay=[];
          foreach($_POST["no"] as $id)
      {
         $query = "SELECT rent.rent_id, member.member_id, member.Lname, member.Fname, member.Mname, equipment.eqp_id ,equipment.serial_no, equipment.eqp_name,equipment.rent_price, rent.rent_date, rent.due_date, rent.date_returned, (TIMESTAMPDIFF(day,rent.rent_date,rent.due_date)*equipment.rent_price) amount,(TIMESTAMPDIFF(hour,rent.due_date,rent.date_returned)*equipment.rent_price/24) as penalty, rent.pay FROM rent INNER JOIN member on member.member_id = rent.member_id INNER JOIN equipment on equipment.eqp_id = rent.eqp_id WHERE rent.rent_id = '$id' GROUP BY rent_id DESC ";
            
             $stmt = $objtRent-> runQuery($query);
             $stmt->execute();
             $rowRent = $stmt->fetch(PDO::FETCH_ASSOC);
             $rent_id[] = $rowRent['rent_id'];
             $member_id[] =$rowRent['member_id']; 
             $Lname[] = $objtEncrypt->decrypt($rowRent['Lname']); 
             $Fname[] = $objtEncrypt->decrypt($rowRent['Fname']); 
             $Mname[] = $objtEncrypt->decrypt($rowRent['Mname']); 
             $eqp_id[] = $rowRent['eqp_id'];
             $eqp_serial[] = $objtEncrypt->decrypt($rowRent['serial_no']);
             $eqp_name[] = $objtEncrypt->decrypt($rowRent['eqp_name']);
             $rent_date[] = date_create($rowRent['rent_date']);
             $due_date[]=date_create($rowRent['due_date']);
             $date_returned[]=date_create($rowRent['date_returned']);
             $output='';
            if($rowRent['penalty']<=0){
              $penalty = 0;      
              $total_amount = $rowRent['amount'] + $penalty;
              $status = "Ontime";
           }else{
              $penalty = ceil($rowRent['penalty']);                                
              $total_amount = $rowRent['amount'] + $penalty;
              $status = "Late";
           }
           $amount[] = $rowRent['amount'];
           $arr_penalty[] = $penalty;
           $arr_total_amount[] = $total_amount;
           $arr_status[] = $status;
           $pay[] = $rowRent['pay'];
     }

          $count = count($rent_id);
          if($count>0){
            for($i=0; $i<$count; $i++){
                $output .='
                        <tr>
                         <td>'.$rent_id[$i].'</td>
                          <td>'.$member_id[$i].'</td>
                          <td>'.$Lname[$i].'
                              '.$Fname[$i].'
                              '.$Mname[$i].'</td>
                          <td>'.$eqp_id[$i].'</td> 
                          <td>'.$eqp_serial[$i].'</td> 
                          <td>'.$eqp_name[$i].'</td> 
                          <td>'.date_format($rent_date[$i],'F d, Y h:i: A').'</td> 
                          <td>'.date_format($due_date[$i],'F d, Y h:i: A').'</td> 
                          <td>'.date_format($date_returned[$i],'F d, Y h:i: A').'</td> 
                          <td>'.$arr_status[$i].'</td>
                          <td>'.$amount[$i].'</td>
                          <td>'.$arr_penalty[$i].'</td>   
                          <td>'.$arr_total_amount[$i].'</td>
                          <td>'.$pay[$i].'</td>
                        </tr>
                        ';
            }
               $content = '';  
               $content .= '

                <h2 align="center">Sinulatan 1st Agriculture Cooperative </h2>
                <p align="center">Brg. Sinulatan 1st, Camilling, Tarlac</p> 
                <p align="center">CDA Reg No. 9520-1030000000044540</p> 
                <p align="center">TIN: 748-678-180-000</p> 
                <h4 align="center">Rent Records</h4> 
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
                                         <th>Due Date</th>
                                         <th>Date Returned</th>
                                         <th>Status</th>
                                         <th>Amount</th>
                                         <th>Penalty</th>
                                         <th>Total Amount</th>
                                         <th>Paid</th>
                                    </tr>
                                    </thead>';  
              $content .= $output;  
              $content .= '</table> 
                <pre><strong>
      

                Prepared by:                      Attested by:

                _____________________          ______________________
                  SACoop Secretary                       SACoop President
                </strong></pre>
            ';  
            $filename='Rent Records';
              pdf($content,$filename); 
        }           
}
}


//Export Excel 
if (isset($_POST['rent_record_excel'])) {
     

    if (empty($_POST['no'])) {
        header('Location: export_excel.php?rental_records');
    }else{    
        $rent_id = []; $member_id = []; $Lname = []; $Fname = []; $Mname = []; $eqp_id = [];
      $eqp_serial = []; $eqp_name = []; $rent_date = []; $due_date = []; $date_returned = [];
      $amount=[]; $arr_penalty=[]; $arr_total_amount=[]; $arr_status=[]; $pay=[];
      $html='<table>
      <thead>
          <tr>
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
      foreach($_POST['no'] as $id)
      {
         $query = "SELECT rent.rent_id, member.member_id, member.Lname, member.Fname, member.Mname, equipment.eqp_id ,equipment.serial_no, equipment.eqp_name,equipment.rent_price, rent.rent_date, rent.due_date, rent.date_returned, (TIMESTAMPDIFF(day,rent.rent_date,rent.due_date)*equipment.rent_price) amount,(TIMESTAMPDIFF(hour,rent.due_date,rent.date_returned)*equipment.rent_price/24) as penalty, rent.pay FROM rent INNER JOIN member on member.member_id = rent.member_id INNER JOIN equipment on equipment.eqp_id = rent.eqp_id WHERE rent.rent_id = '$id' GROUP BY rent_id DESC ";
             $stmt = $objtRent-> runQuery($query);
             $stmt->execute();
             $rowRent = $stmt->fetch(PDO::FETCH_ASSOC);

             $rent_id[] = $rowRent['rent_id'];
             $member_id[] =$rowRent['member_id']; 
             $Lname[] = $objtEncrypt->decrypt($rowRent['Lname']); 
             $Fname[] = $objtEncrypt->decrypt($rowRent['Fname']); 
             $Mname[] = $objtEncrypt->decrypt($rowRent['Mname']); 
             $eqp_id[] = $rowRent['eqp_id'];
             $eqp_serial[] = $objtEncrypt->decrypt($rowRent['serial_no']);
             $eqp_name[] = $objtEncrypt->decrypt($rowRent['eqp_name']);
             $rent_date[] = date_create($rowRent['rent_date']);
             $due_date[]=date_create($rowRent['due_date']);
             $date_returned[]=date_create($rowRent['date_returned']);
                          $output='';
            if($rowRent['penalty']<=0){
              $penalty = 0;      
              $total_amount = $rowRent['amount'] + $penalty;
              $status = "Ontime";
           }else{
              $penalty = ceil($rowRent['penalty']);                                
              $total_amount = $rowRent['amount'] + $penalty;
              $status = "Late";
           }
           $amount[] = $rowRent['amount'];
           $arr_penalty[] = $penalty;
           $arr_total_amount[] = $total_amount;
           $arr_status[] = $status;
           $pay[] = $rowRent['pay'];
     }
          $count = count($rent_id);
          if($count>0){
            for($i=0; $i<$count; $i++){
              $Fullname[] = $Lname[$i].', '.$Fname[$i].' '.$Mname[$i];

                $html .='
                <tbody>
                  <tr>
                   <td>'.$rent_id[$i].'</td>
                    <td>'.$member_id[$i].'</td>
                    <td>'.$Lname[$i].'
                        '.$Fname[$i].'
                        '.$Mname[$i].'</td>
                    <td>'.$eqp_id[$i].'</td> 
                    <td>'.$eqp_serial[$i].'</td> 
                    <td>'.$eqp_name[$i].'</td> 
                    <td>'.date_format($rent_date[$i],'F d, Y h:i: A').'</td> 
                    <td>'.date_format($due_date[$i],'F d, Y h:i: A').'</td> 
                    <td>'.date_format($date_returned[$i],'F d, Y h:i: A').'</td> 
                    <td>'.$arr_status[$i].'</td>
                    <td>'.$amount[$i].'</td>
                    <td>'.$arr_penalty[$i].'</td>   
                    <td>'.$arr_total_amount[$i].'</td>
                    <td>'.$pay[$i].'</td>
                  </tr>
                  </tbody>';
            }
            
    }
      
      $html.='</table>';
            header("Content-type: application/octet-stream"); 
            header('Content-Disposition:attachment;filename=Rental_Record_'.date('m-d-Y').'.xls');
            header("Pragma: no-cache"); 
            header("Expires: 0"); 
            echo $html; 
    
  } 
}
  //Multiple Delete
  if(isset($_POST['delete_rent']))
  {
  if(!empty($_POST["no"])){
        foreach($_POST["no"] as $id)
           {
              $query = "DELETE FROM rent WHERE rent_id = '".$id."'";
              $stmt = $objtRent-> runQuery($query);
              $stmt->execute();
          }   
             $user_id = $_SESSION['user_id'];
             $url = $_SERVER['REQUEST_URI'];
             $date_time = date("Y-m-d H:i:s");
             $action = "Deleted a rent record";
             $objtUser->user_activity($user_id, $action, $url, $date_time);  
              header('Location: rent_record.php?deleted');
        }else{
            header('Location: rent_record.php?no_data');
  }
  }
 ?>

<!-- END of RENTAL RECORD CODE-->




<!-- Start OF RENTAL  Payment Record CODE -->
<?php
   //Multiple Delete For Rent Payment
  if(isset($_POST['delete_payment']))
{
  if(!empty($_POST["no"])){
    foreach($_POST["no"] as $id)
     {
        $query = "DELETE FROM rent_payment WHERE pay_id = '".$id."'";
        $stmt = $objtPayment-> runQuery($query);
        $stmt->execute();
    }
     $user_id = $_SESSION['user_id'];
     $url = $_SERVER['REQUEST_URI'];
     $date_time = date("Y-m-d H:i:s");
     $action = "Deleted a payment transaction";
     $objtUser->user_activity($user_id, $action, $url, $date_time);  
    header('Location: payment_record.php?deleted');
  }else{
    header('Location: payment_record.php?no_payment_data');
  }

}
//export PDF
if (isset($_POST['rental_transaction_pdf'])) {
      
    if (empty($_POST['no'])) {
        header('Location: export.php?transaction_pdf');
    }else{
      $pay_id = []; $member_id = []; $Lname = []; $Fname = []; $Mname = []; $eqp_id = [];
      $eqp_serial = []; $eqp_name = []; $rent_date = []; $due_date = []; $date_returned = [];
      $amount=[]; $arr_penalty=[]; $arr_total_amount=[]; $arr_status=[]; $pay=[]; $date_pay=[]; $process=[];
          foreach($_POST["no"] as $id)
      {
         $query = "SELECT rent_payment.pay_id,rent.rent_id, member.Lname,member.Fname,member.Mname, member.member_id,equipment.eqp_id,equipment.eqp_name, equipment.serial_no, rent.rent_date, rent.due_date, rent.date_returned, rent_payment.amount, rent_payment.paid, rent_payment.date_pay,(TIMESTAMPDIFF(hour,rent.due_date,rent.date_returned)*equipment.rent_price/24) as penalty, register.username from rent_payment LEFT JOIN rent ON rent.rent_id = rent_payment.rent_id LEFT JOIN member on member.member_id = rent.member_id LEFT JOIN equipment on equipment.eqp_id = rent.eqp_id LEFT JOIN register ON register.user_id = rent_payment.user_id WHERE rent_payment.pay_id = '$id' ORDER BY rent_payment.pay_id DESC";
            
             $stmt = $objtPayment-> runQuery($query);
             $stmt->execute();
             $rowPayment = $stmt->fetch(PDO::FETCH_ASSOC);
             $pay_id[] = $rowPayment['pay_id'];
             $member_id[] =$rowPayment['member_id']; 
             $Lname[] = $objtEncrypt->decrypt($rowPayment['Lname']); 
             $Fname[] = $objtEncrypt->decrypt($rowPayment['Fname']); 
             $Mname[] = $objtEncrypt->decrypt($rowPayment['Mname']); 
             $eqp_id[] = $rowPayment['eqp_id'];
             $eqp_serial[] = $objtEncrypt->decrypt($rowPayment['serial_no']);
             $eqp_name[] = $objtEncrypt->decrypt($rowPayment['eqp_name']);
             $rent_date[] = date_create($rowPayment['rent_date']);
             $due_date[]=date_create($rowPayment['due_date']);
             $date_returned[]=date_create($rowPayment['date_returned']);
             $date_pay[]=date_create($rowPayment['date_pay']);
             $process[] = $rowPayment['username'];
             $output='';
            if($rowPayment['penalty']<=0){
              $status = "Ontime";
           }else{
              $penalty = ceil($rowPayment['penalty']);                                
              $status = "Late";
           }
           $amount[] = $rowPayment['amount'];
           $arr_status[] = $status;
           $pay[] = $rowPayment['paid'];
     }
          $count = count($pay_id);
          if($count>0){
            for($i=0; $i<$count; $i++){
                $output .='
                        <tr>
                         <td>'.$pay_id[$i].'</td>
                          <td>'.$member_id[$i].'</td>
                          <td>'.$Lname[$i].'
                              '.$Fname[$i].'
                              '.$Mname[$i].'</td>
                          <td>'.$eqp_id[$i].'</td> 
                          <td>'.$eqp_serial[$i].'</td> 
                          <td>'.$eqp_name[$i].'</td> 
                          <td>'.date_format($rent_date[$i],'F d, Y h:i: A').'</td> 
                          <td>'.date_format($due_date[$i],'F d, Y h:i: A').'</td> 
                          <td>'.date_format($date_returned[$i],'F d, Y h:i: A').'</td> 
                          <td>'.$arr_status[$i].'</td>
                          <td>'.$amount[$i].'</td>
                          <td>'.$pay[$i].'</td>
                          <td>'.date_format($date_pay[$i],'F d, Y h:i: A').'</td> 
                          <td>'.$process[$i].'</td>
                        </tr>
                        ';
            }
               $content = '';  
               $content .= '

                <h2 align="center">Sinulatan 1st Agriculture Cooperative </h2>
                <p align="center">Brg. Sinulatan 1st, Camilling, Tarlac</p> 
                <p align="center">CDA Reg No. 9520-1030000000044540</p> 
                <p align="center">TIN: 748-678-180-000</p> 
                <h4 align="center">Rental Payment Transaction</h4> 
                <table border="1" cellspacing="0" cellpadding="3" style="font-size: 10px">
                                    <thead>
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
              $content .= $output;  
              $content .= '</table> 
                  <pre><strong>
        

                  Prepared by:                      Attested by:

                  _____________________          ______________________
                    SACoop Secretary                       SACoop President
                  </strong></pre>
              '; 
              $filename='Rental Payment Transaction';
              pdf($content,$filename); 
        }
}
}

if (isset($_POST['rental_transaction_excel'])) {
    
    if (empty($_POST['no'])) {
        header('Location: export_excel.php?rental_transaction');
    }else{
          $html='<table>
            <thead>
              <tr>
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
                 <th>Paid</th>
                 <th>Date Pay</th>
                 <th>Process BY</th>
              </tr>
          </thead>';
        foreach($_POST["no"] as $id)
            {
          $query = "SELECT rent_payment.pay_id,rent.rent_id, member.Lname,member.Fname,member.Mname, member.member_id,equipment.eqp_id,equipment.eqp_name, equipment.serial_no, rent.rent_date, rent.due_date, rent.date_returned, rent_payment.amount, rent_payment.paid, rent_payment.date_pay,(TIMESTAMPDIFF(hour,rent.due_date,rent.date_returned)*equipment.rent_price/24) as penalty, register.username from rent_payment LEFT JOIN rent ON rent.rent_id = rent_payment.rent_id LEFT JOIN member on member.member_id = rent.member_id LEFT JOIN equipment on equipment.eqp_id = rent.eqp_id LEFT JOIN register ON register.user_id = rent_payment.user_id WHERE rent_payment.pay_id = '$id' ORDER BY rent_payment.pay_id DESC";
            
             $stmt = $objtPayment-> runQuery($query);
             $stmt->execute();
             $rowPayment = $stmt->fetch(PDO::FETCH_ASSOC);
             $pay_id[] = $rowPayment['pay_id'];
             $member_id[] =$rowPayment['member_id']; 
             $Lname[] = $objtEncrypt->decrypt($rowPayment['Lname']); 
             $Fname[] = $objtEncrypt->decrypt($rowPayment['Fname']); 
             $Mname[] = $objtEncrypt->decrypt($rowPayment['Mname']); 
             $eqp_id[] = $rowPayment['eqp_id'];
             $eqp_serial[] = $objtEncrypt->decrypt($rowPayment['serial_no']);
             $eqp_name[] = $objtEncrypt->decrypt($rowPayment['eqp_name']);
             $rent_date[] = date_create($rowPayment['rent_date']);
             $due_date[]=date_create($rowPayment['due_date']);
             $date_returned[]=date_create($rowPayment['date_returned']);
             $date_pay[]=date_create($rowPayment['date_pay']);
             $process[] = $rowPayment['username'];
             $output='';
            if($rowPayment['penalty']<=0){   
              $status = "Ontime";
           }else{
              $penalty = ceil($rowPayment['penalty']);                                
              $status = "Late";
           }
           $amount[] = $rowPayment['amount'];
           $arr_status[] = $status;
           $pay[] = $rowPayment['paid'];
     }
      $count = count($pay_id);
          $count = count($pay_id);
          if($count>0){
            for($i=0; $i<$count; $i++){
            $html .='
                  <tr>
                   <td>'.$pay_id[$i].'</td>
                    <td>'.$member_id[$i].'</td>
                    <td>'.$Lname[$i].'
                        '.$Fname[$i].'
                        '.$Mname[$i].'</td>
                    <td>'.$eqp_id[$i].'</td> 
                    <td>'.$eqp_serial[$i].'</td> 
                    <td>'.$eqp_name[$i].'</td> 
                    <td>'.date_format($rent_date[$i],'F d, Y h:i: A').'</td> 
                    <td>'.date_format($due_date[$i],'F d, Y h:i: A').'</td> 
                    <td>'.date_format($date_returned[$i],'F d, Y h:i: A').'</td> 
                    <td>'.$arr_status[$i].'</td>
                    <td>'.$amount[$i].'</td>     
                    <td>'.$pay[$i].'</td>
                    <td>'.date_format($date_pay[$i],'F d, Y h:i: A').'</td> 
                    <td>'.$process[$i].'</td>
                  </tr>
                  ';
            }
          }
      
      $html.='</table>';
            header("Content-type: application/octet-stream"); 
            header('Content-Disposition:attachment;filename=Rent_Payment_Transaction_'.date('m-d-Y').'.xls');
            header("Pragma: no-cache"); 
            header("Expires: 0"); 
            echo $html; 
    }
}

 ?>


 <!-- END OF RENTAL TRANSACTION CODE-->





 <!-- START OF PAID UP CAPOTAL TRANSAC TION -->

 <?php 
   //Multiple Delete For Paid up Capital
  if(isset($_POST['delete_puc']))
{
  if(!empty($_POST["no"])){
    foreach($_POST["no"] as $id)
     {
        $query = "DELETE FROM puc_transaction WHERE puc_id = '".$id."'";
        $stmt = $objtPayment-> runQuery($query);
        $stmt->execute();
    }
    $user_id = $_SESSION['user_id'];
    $url = $_SERVER['REQUEST_URI'];
    $date_time = date("Y-m-d H:i:s");
    $action = "Deleted a paid up capital transaction";
    $objtUser->user_activity($user_id, $action, $url, $date_time); 
    header('Location: puc_transaction.php?deleted');
  }else{
      header("Location:puc_transaction.php?no_puc_data");
  }
}

//Export PDF
if (isset($_POST['paid_up_capital_pdf'])) {
          
    if (empty($_POST['no'])) {        
        header('Location: export.php?puc_pdf');
    }else{
        
        $puc_id = [];
        $member_id = [];
        $Lname = [];
        $Fname = [];
        $Mname = [];
        $paid = [];
        $date_pay = [];
        $process = [];
        $output='';
         foreach($_POST["no"] as $id)
           {
              $query = "SELECT puc_transaction.puc_id, member.member_id, member.Lname, member.Fname, member.Mname, puc_transaction.paid, puc_transaction.date_pay, register.username FROM puc_transaction INNER JOIN member ON member.member_id = puc_transaction.member_id INNER JOIN register ON register.user_id =  puc_transaction.user_id WHERE puc_id = '".$id."'";
              $stmt = $objtPayment-> runQuery($query);
              $stmt->execute();
              $rowPayment = $stmt->fetch(PDO::FETCH_ASSOC);

              $puc_id[] = $rowPayment["puc_id"];
              $member_id[] = $rowPayment['member_id'];
              $Lname[] = $objtEncrypt->decrypt($rowPayment["Lname"]);
              $Fname[] = $objtEncrypt->decrypt($rowPayment["Fname"]);
              $Mname[] = $objtEncrypt->decrypt($rowPayment["Mname"]);
              $paid[] = $rowPayment['paid'];
              $date_pay[] = date_create($rowPayment['date_pay']);
              $process[] = $rowPayment['username'];
          }

              $count  = count($puc_id);
              if($count>0){
                for($i=0; $i<$count; $i++){
                      $output .='
                        <tr>
                         <td>'.$puc_id[$i].'</td>
                          <td>'.$member_id[$i].'</td>
                          <td>'.$Lname[$i] .'
                              '.$Fname[$i].'
                              '.$Mname[$i].'</td>
                          <td>'.$paid[$i].'</td> 
                          <td>'.date_format($date_pay[$i],'F d, Y h:i: A').'</td> 
                          <td>'.$process[$i].'</td>   
                        </tr>
                        ';
            }
               $content = '';  
               $content .= '

                <h2 align="center">Sinulatan 1st Agriculture Cooperative </h2>
                <p align="center">Brg. Sinulatan 1st, Camilling, Tarlac</p> 
                <p align="center">CDA Reg No. 9520-1030000000044540</p> 
                <p align="center">TIN: 748-678-180-000</p> 
                <h4 align="center">Paid up Transaction</h4> 
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
              $content .= $output;  
              $content .= '</table> 
                  <pre><strong>
        

                  Prepared by:                      Attested by:

                  _____________________          ______________________
                    SACoop Secretary                       SACoop President
                  </strong></pre>
              '; 
              $filename='Paid Up Capital Transaction';
              Ppdf($content,$filename); 
}
}
}
//---------------------------------------BREAK LINE-----------------------

//Export EXCEL FILE
if (isset($_POST['paid_up_capital_excel'])) {
         
    if (empty($_POST['no'])) {
        header('Location: export_excel.php?paid_up_capital_excel');
    }else{
        $puc_id = [];
        $member_id = [];
        $Lname = [];
        $Fname = [];
        $Mname = [];
        $paid = [];
        $date_pay = [];
        $process = [];
        $html='<table>
        <tr>
        <td>Transaction ID</td>
        <td>Member ID</td>
        <td>Name</td>
        <td>paid</td>
        <td>Date Paid</td>
        <td>Process By</td>
        </tr>';
         foreach($_POST["no"] as $id)
           {
              $query = "SELECT puc_transaction.puc_id, member.member_id, member.Lname, member.Fname, member.Mname, puc_transaction.paid, puc_transaction.date_pay, register.username FROM puc_transaction INNER JOIN member ON member.member_id = puc_transaction.member_id INNER JOIN register ON register.user_id =  puc_transaction.user_id WHERE puc_id = '".$id."'";
              $stmt = $objtPayment-> runQuery($query);
              $stmt->execute();
              $rowPayment = $stmt->fetch(PDO::FETCH_ASSOC);

              $puc_id[] = $rowPayment["puc_id"];
              $member_id[] = $rowPayment['member_id'];
              $Lname[] = $objtEncrypt->decrypt($rowPayment["Lname"]);
              $Fname[] = $objtEncrypt->decrypt($rowPayment["Fname"]);
              $Mname[] = $objtEncrypt->decrypt($rowPayment["Mname"]);
              $paid[] = $rowPayment['paid'];
              $date_pay[] = date_create($rowPayment['date_pay']);
              $process[] = $rowPayment['username'];
          }
            $count  = count($puc_id);
              if($count>0){
                for($i=0; $i<$count; $i++){
                      $html .='
                        <tr>
                         <td>'.$puc_id[$i].'</td>
                          <td>'.$member_id[$i].'</td>
                          <td>'.$Lname[$i] .'
                              '.$Fname[$i].'
                              '.$Mname[$i].'</td>
                          <td>'.$paid[$i].'</td> 
                          <td>'.date_format($date_pay[$i],'F d, Y h:i: A').'</td> 
                          <td>'.$process[$i].'</td>   
                        </tr>
                        ';
                  }
            }
            $html.='</table>';
            header("Content-type: application/octet-stream"); 
            header('Content-Disposition:attachment;filename=Paid_up_Capital_Transaction_'.date('m-d-Y').'.xls');
            header("Pragma: no-cache"); 
            header("Expires: 0"); 
            echo $html; 
    }
}
 ?>
 <!-- END OF PAID UP CAPITAL TRANSACTION -->

 <!-- start of  Loan Report -->
<?php 
//Export PDF
if (isset($_POST['loan_record_pdf'])) {
  
if (empty($_POST['no'])) {        
 header('Location: export.php?loan_pdf');
}else{
 
 $loan_id = [];
 $member_id = [];
 $Lname = [];
 $Fname = [];
 $Mname = [];
 $loan_amount = [];
 $date_start = [];
 $due_date = [];
 $date_finish = [];
 $status_arr = [];
 $interest = [];
 $penalty = [];
 $total = [];
 $process_by = [];
 $output='';
  foreach($_POST["no"] as $id)
    {
       $query = "SELECT loan.loan_id, member.member_id, member.Lname, member.Fname, member.Mname, loan.based, loan.date_start, loan.due_date, loan.date_finish, loan.interest, loan.penalty, loan.total, register.username FROM loan INNER JOIN member ON member.member_id = loan.member_id INNER JOIN register ON register.user_id = loan.user_id WHERE loan_id = '".$id."' ";
       $stmt = $objtPayment-> runQuery($query);
       $stmt->execute();
       $rowLoan = $stmt->fetch(PDO::FETCH_ASSOC);

       $loan_id[] = $rowLoan["loan_id"];
       $member_id[] = $rowLoan['member_id'];
       $Lname[] = $objtEncrypt->decrypt($rowLoan["Lname"]);
       $Fname[] = $objtEncrypt->decrypt($rowLoan["Fname"]);
       $Mname[] = $objtEncrypt->decrypt($rowLoan["Mname"]);
       $loan_amount[] = $rowLoan['based'];
       $date_start[] = date_create($rowLoan['date_start']);
       $due_date[]=date_create($rowLoan['due_date']);
       $date_finish[]=date_create($rowLoan['date_finish']);

       $date1=date_create($rowLoan['date_start']);
       $date2=date_create($rowLoan['date_finish']);
       $diff=date_diff($date1,$date2);
       $number_of_days=$diff->format("%a");

       if($number_of_days%120 == 0 && !($number_of_days/120 >1) ){
        $status = "Ontime";
       }else{
        $status = "Late";
       }
       $status_arr[]=$status;
       $interest[] = $rowLoan['interest'];
       $penalty[]= $rowLoan['penalty'];
       $total[]=$rowLoan['total'];
       $proces_by[]=$rowLoan['username'];
      }

       $count = count($loan_id);
       if($count>0){
         for($i=0; $i<$count; $i++){
               $output .='
                 <tr>
                  <td>'.$loan_id[$i].'</td>
                   <td>'.$member_id[$i].'</td>
                   <td>'.$Lname[$i] .'
                       '.$Fname[$i].'
                       '.$Mname[$i].'</td>
                   <td>'.$loan_amount[$i].'</td> 
                   <td>'.date_format($date_start[$i],'F d, Y h:i: A').'</td> 
                   <td>'.date_format($due_date[$i],'F d, Y h:i: A').'</td> 
                   <td>'.date_format($date_finish[$i],'F d, Y h:i: A').'</td> 
                   <td>'.$status_arr[$i].'</td> 
                   <td>'.$interest[$i].'</td> 
                   <td>'.$penalty[$i].'</td> 
                   <td>'.$total[$i].'</td> 
                   <td>'.$proces_by[$i].'</td> 
                 </tr>
                 ';
     }
        $content = '';  
        $content .= '

         <h2 align="center">Sinulatan 1st Agriculture Cooperative </h2>
         <p align="center">Brg. Sinulatan 1st, Camilling, Tarlac</p> 
         <p align="center">CDA Reg No. 9520-1030000000044540</p> 
         <p align="center">TIN: 748-678-180-000</p> 
         <h4 align="center">Loan Records</h4> 
         <table border="1" cellspacing="0" cellpadding="3" style="font-size: 10px">
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
       $content .= $output;  
       $content .= '</table> 
           <pre><strong>
 

           Prepared by:                      Attested by:

           _____________________          ______________________
             SACoop Secretary                       SACoop President
           </strong></pre>
       '; 
        $filename='Loan Records';
        pdf($content,$filename); 
}
}
}

//Excel 
//Export EXCEL FILE
if (isset($_POST['loan_record_excel'])) {
  
if (empty($_POST['no'])) {
  header('Location: export_excel.php?loan_records');
}else{
  $loan_id = [];
  $member_id = [];
  $Lname = [];
  $Fname = [];
  $Mname = [];
  $loan_amount = [];
  $date_start = [];
  $due_date = [];
  $date_finish = [];
  $status_arr = [];
  $interest = [];
  $penalty = [];
  $total = [];
  $process_by = [];
  $html='<table>
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
</tr>';
   foreach($_POST["no"] as $id)
     {
        $query = "SELECT loan.loan_id, member.member_id, member.Lname, member.Fname, member.Mname, loan.based, loan.date_start, loan.due_date, loan.date_finish, loan.interest, loan.penalty, loan.total, register.username FROM loan INNER JOIN member ON member.member_id = loan.member_id INNER JOIN register ON register.user_id = loan.user_id WHERE loan_id = '".$id."' ";
        $stmt = $objtPayment-> runQuery($query);
        $stmt->execute();
        $rowLoan = $stmt->fetch(PDO::FETCH_ASSOC);

       $loan_id[] = $rowLoan["loan_id"];
       $member_id[] = $rowLoan['member_id'];
       $Lname[] = $objtEncrypt->decrypt($rowLoan["Lname"]);
       $Fname[] = $objtEncrypt->decrypt($rowLoan["Fname"]);
       $Mname[] = $objtEncrypt->decrypt($rowLoan["Mname"]);
       $loan_amount[] = $rowLoan['based'];
       $date_start[] = date_create($rowLoan['date_start']);
       $due_date[]=date_create($rowLoan['due_date']);
       $date_finish[]=date_create($rowLoan['date_finish']);

       $date1=date_create($rowLoan['date_start']);
       $date2=date_create($rowLoan['date_finish']);
       $diff=date_diff($date1,$date2);
       $number_of_days=$diff->format("%a");

       if($number_of_days%120 == 0 && !($number_of_days/120 >1) ){
        $status = "Ontime";
       }else{
        $status = "Late";
       }
       $status_arr[]=$status;
       $interest[] = $rowLoan['interest'];
       $penalty[]= $rowLoan['penalty'];
       $total[]=$rowLoan['total'];
       $proces_by[]=$rowLoan['username'];
    }
      $count  = count($loan_id);
        if($count>0){
          for($i=0; $i<$count; $i++){
                $html .='
                <tr>
                <td>'.$loan_id[$i].'</td>
                 <td>'.$member_id[$i].'</td>
                 <td>'.$Lname[$i] .'
                     '.$Fname[$i].'
                     '.$Mname[$i].'</td>
                 <td>'.$loan_amount[$i].'</td> 
                 <td>'.date_format($date_start[$i],'F d, Y h:i: A').'</td> 
                 <td>'.date_format($due_date[$i],'F d, Y h:i: A').'</td> 
                 <td>'.date_format($date_finish[$i],'F d, Y h:i: A').'</td> 
                 <td>'.$status_arr[$i].'</td> 
                 <td>'.$interest[$i].'</td> 
                 <td>'.$penalty[$i].'</td> 
                 <td>'.$total[$i].'</td> 
                 <td>'.$proces_by[$i].'</td> 
               </tr>
                  ';
            }
      }
      $html.='</table>';
      header("Content-type: application/octet-stream"); 
      header('Content-Disposition:attachment;filename=Loan_Records'.date('m-d-Y').'.xls');
      header("Pragma: no-cache"); 
      header("Expires: 0"); 
      echo $html; 
}
}


//delete loan
if(isset($_POST['delete_loan']))
{
  if(!empty($_POST["no"])){
    foreach($_POST["no"] as $id)
     {
        $query = "DELETE FROM loan WHERE loan_id = '".$id."'";
        $stmt = $objtPayment-> runQuery($query);
        $stmt->execute();
    }
    $user_id = $_SESSION['user_id'];
    $url = $_SERVER['REQUEST_URI'];
    $date_time = date("Y-m-d H:i:s");
    $action = "Deleted Loan Records";
    $objtUser->user_activity($user_id, $action, $url, $date_time); 
    header('Location: loan_records.php?deleted');
  }else{
      header("Location:loan_records.php?no_data");
  }
}

?>
 