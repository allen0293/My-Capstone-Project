<?php
date_default_timezone_set('Asia/Manila');
include('includes/security.php');
require_once 'classes/user.php';
require_once 'classes/encryption.php';
$objtEncrypt = new Encryption();
$objtExport = new User();
//Export members
if(isset($_GET['member'])){
	$query = "SELECT * FROM member";
	$stmt = $objtExport-> runQuery($query);
	$stmt->execute();
	$html='<table>
				<tr>
				<td>Member ID</td>
				<td>TIN</td>
				<td>Name</td>
				<td>Spouse Name</td>
				<td>Birthdate</td>
				<td>Address</td>
				<td>Contact no.</td>
				<td>Land Location</td>
				<td>Land_size(Hectares)</td>
				<td>Crop1</td>
				<td>Crop2</td>
				<td>Crop3</td>
				<td>Capital Build Up</td>
				<td>Paid Up Capital</td>
				<td>Registration Fee</td>
				<td>Date Registered</td>
				</tr>';
		$count= $stmt->rowCount();
		if($count > 0){
		while($rowMember = $stmt->fetch(PDO::FETCH_ASSOC)){
		$html.='<tr>
		 			<td>'.$rowMember["member_id"].'</td>
	                <td>'.$objtEncrypt->decrypt($rowMember["TIN"]).'</td>
	                <td>'.$objtEncrypt->decrypt($rowMember["Lname"]).'
	                '.$objtEncrypt->decrypt($rowMember["Fname"]).'
	                '.$objtEncrypt->decrypt($rowMember["Mname"]).'
	                </td>
	                <td>'.$objtEncrypt->decrypt($rowMember["spouse_name"]).'</td>
	                <td>'.$objtEncrypt->decrypt($rowMember["birthdate"]).'</td>
	                <td>'.$objtEncrypt->decrypt($rowMember["address"]).'</td>
	                <td>'.$objtEncrypt->decrypt($rowMember["contactno"]).'</td>
	                <td>'.$objtEncrypt->decrypt($rowMember["land_location"]).'</td>
	                <td>'.$objtEncrypt->decrypt($rowMember["land_size"]).'</td>
	                <td>'.$objtEncrypt->decrypt($rowMember["crop1"]).'</td>
	                <td>'.$objtEncrypt->decrypt($rowMember["crop2"]).'</td>
	                <td>'.$objtEncrypt->decrypt($rowMember["crop3"]).'</td>
	                <td>'.$rowMember["capital_build_up"].'</td>    
	                <td>'.$rowMember["paid_up_capital"].'</td>    
	                <td>'.$rowMember["reg_fee"].'</td>    
	                <td>'.$rowMember["registered_date"].'</td>     
		</tr>';
	}
	}
	$html.='</table>';
	header('Content-Type:application/vnd.ms-excel');
	header('Content-Disposition:attachment;filename=Member_report_'.date('m-d-Y').'.xls');
	echo $html;
}
//Export paid up Capital Transaction
if(isset($_GET['paid_up_capital_excel'])){
	$query = "SELECT puc_transaction.puc_id, member.member_id, member.Lname, member.Fname, member.Mname, puc_transaction.paid, puc_transaction.date_pay, register.username FROM puc_transaction INNER JOIN member ON member.member_id = puc_transaction.member_id INNER JOIN register ON register.user_id =  puc_transaction.user_id";
	$stmt = $objtExport-> runQuery($query);
	$stmt->execute();
	$html='<table>
				<tr>
				<td>Transaction ID</td>
				<td>Member ID</td>
				<td>Name</td>
				<td>paid</td>
				<td>Date Paid</td>
				<td>Process By</td>
				</tr>';
		$count= $stmt->rowCount();
		if($count > 0){
		while($rowPuc = $stmt->fetch(PDO::FETCH_ASSOC)){
			$date_pay = date_create($rowPuc['date_pay']);
		$html.='<tr>
					<td>'.$rowPuc["puc_id"].'</td>
		 			<td>'.$rowPuc["member_id"].'</td>
	                <td>'.$objtEncrypt->decrypt($rowPuc["Lname"]).'
	                '.$objtEncrypt->decrypt($rowPuc["Fname"]).'
	                '.$objtEncrypt->decrypt($rowPuc["Mname"]).'
	                </td>
	                <td>'.$rowPuc["paid"].'</td>
	                <td>'.date_format($date_pay,'F d, Y h:i: A').'</td>
	                <td>'.$rowPuc["username"].'</td>	                   
		</tr>';
	}
	}
	$html.='</table>';
	header('Content-Type:application/vnd.ms-excel');
	header('Content-Disposition:attachment;filename=Paid_up_Capital_Transaction_'.date('m-d-Y').'.xls');
	echo $html;
}

//Export Equipments
if(isset($_GET['equipment'])){
	$query = "SELECT * FROM equipment";
	$stmt = $objtExport-> runQuery($query);
	$stmt->execute();
	$html='<table>
				<tr>
				   <th>Equipment ID</th>
	               <th>Serial Number</th>
	               <th>Equipment Name</th>
	               <th>Equipment Model</th>
	               <th>Description</th>
	               <th>Rental Price</th>
				</tr>';
		$count= $stmt->rowCount();
		if($count > 0){
		while($rowEqp = $stmt->fetch(PDO::FETCH_ASSOC)){
		$html.='<tr>
	         <td>'.$rowEqp["eqp_id"].'</td>
             <td>'.$objtEncrypt->decrypt($rowEqp["serial_no"]).'</td>
             <td>'.$objtEncrypt->decrypt($rowEqp["eqp_name"]).'</td>
             <td>'.$objtEncrypt->decrypt($rowEqp["eqp_model"]).'</td>
             <td>'.$objtEncrypt->decrypt($rowEqp["eqp_desc"]).'</td>
             <td>'.$rowEqp['rent_price'].'</td>     
		</tr>';
	}
	}
	$html.='</table>';
	header('Content-Type:application/vnd.ms-excel');
	header('Content-Disposition:attachment;filename=Equipment_report_'.date('m-d-Y').'.xls');
	echo $html;
}

//Export Unpaid
if(isset($_GET['rent'])){
	$query = "SELECT rent.rent_id, member.member_id, member.Lname, member.Fname, member.Mname, equipment.eqp_id, equipment.serial_no, equipment.eqp_name, rent.rent_date, rent.due_date, TIMESTAMPDIFF(day,rent.rent_date,rent.due_date)*equipment.rent_price as amount, rent.pay, TIMESTAMPDIFF(day,rent.rent_date,rent.due_date)*equipment.rent_price - rent.pay as Balance FROM rent INNER JOIN member on member.member_id = rent.member_id INNER JOIN equipment on equipment.eqp_id = rent.eqp_id Where TIMESTAMPDIFF(day,rent.rent_date,rent.due_date)*equipment.rent_price > pay";
	$stmt = $objtExport-> runQuery($query);
	$stmt->execute();
	$html='<table>
				<tr>
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
				</tr>';
		$count= $stmt->rowCount();
		if($count > 0){
		while($rowRent = $stmt->fetch(PDO::FETCH_ASSOC)){
		$html.='<tr>
	         <td>'.$rowRent["rent_id"].'</td>
	         <td>'.$rowRent["member_id"].'</td>
	                <td>'.$objtEncrypt->decrypt($rowRent["Lname"]).'
	                '.$objtEncrypt->decrypt($rowRent["Fname"]).'
	                '.$objtEncrypt->decrypt($rowRent["Mname"]).'
	                </td>
	         <td>'.$rowRent["eqp_id"].'</td>
             <td>'.$objtEncrypt->decrypt($rowRent["serial_no"]).'</td>
             <td>'.$objtEncrypt->decrypt($rowRent["eqp_name"]).'</td>  
             <td>'.$rowRent["rent_date"].'</td>
             <td>'.$rowRent["due_date"].'</td>   
             <td>'.$rowRent["amount"].'</td>   
             <td>'.$rowRent["pay"].'</td>   
             <td>'.$rowRent["Balance"].'</td>              
		</tr>';
	}
	}
	$html.='</table>';
	header('Content-Type:application/vnd.ms-excel');
	header('Content-Disposition:attachment;filename=Upaid_rent_report_'.date('m-d-Y').'.xls');
	echo $html;
}
// Export R ent Payment Transaction
if(isset($_GET['rental_transaction'])){
	$query = "SELECT rent_payment.pay_id,rent.rent_id, member.Lname,member.Fname,member.Mname, member.member_id,equipment.eqp_id,equipment.eqp_name, equipment.serial_no, rent.rent_date, rent.due_date, rent.date_returned, rent_payment.amount, rent_payment.paid, rent_payment.date_pay,(TIMESTAMPDIFF(hour,rent.due_date,rent.date_returned)*equipment.rent_price/24) as penalty, register.username from rent_payment LEFT JOIN rent ON rent.rent_id = rent_payment.rent_id LEFT JOIN member on member.member_id = rent.member_id LEFT JOIN equipment on equipment.eqp_id = rent.eqp_id LEFT JOIN register ON register.user_id = rent_payment.user_id ORDER BY rent_payment.pay_id DESC";
	$stmt = $objtExport-> runQuery($query);
	$stmt->execute();
	$html='<table>
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
				</tr>';
		$count= $stmt->rowCount();
		if($count > 0){
		while($rowRent = $stmt->fetch(PDO::FETCH_ASSOC)){
			 $date_pay = date_create($rowRent['date_pay']);
             $rent_date = date_create($rowRent['rent_date']);
             $due_date = date_create($rowRent['due_date']);
             $date_returned = date_create($rowRent['date_returned']);

             if($rowRent['penalty']<=0){
              $penalty = 0;      
              $total_amount = $rowRent['amount'] + $penalty;
              $status = "Ontime";
            }else{
              $penalty = ceil($rowRent['penalty']);                                
              $total_amount = $rowRent['amount'] + $penalty;
              $status = "Late";
            }

		$html.='<tr>
	         <td>'.$rowRent["rent_id"].'</td>
	         <td>'.$rowRent["member_id"].'</td>
	                <td>'.$objtEncrypt->decrypt($rowRent["Lname"]).'
	                '.$objtEncrypt->decrypt($rowRent["Fname"]).'
	                '.$objtEncrypt->decrypt($rowRent["Mname"]).'
	                </td>
	         <td>'.$rowRent["eqp_id"].'</td>
             <td>'.$objtEncrypt->decrypt($rowRent["serial_no"]).'</td>
             <td>'.$objtEncrypt->decrypt($rowRent["eqp_name"]).'</td>  
             <td>'.date_format($rent_date,'F d, Y h:i: A').'</td> 
             <td>'.date_format($due_date,'F d, Y h:i: A').'</td> 
             <td>'.date_format($date_returned,'F d, Y h:i: A').'</td>   
             <td>'.$status.'</td>  
             <td>'.$rowRent["amount"].'</td>   
             <td>'.$rowRent["paid"].'</td>   
             <td>'.date_format($date_pay,'F d, Y h:i: A').'</td>  
             <td>'.$rowRent["username"].'</td>           
		</tr>';
	}
	}
	$html.='</table>';
	header('Content-Type:application/vnd.ms-excel');
	header('Content-Disposition:attachment;filename=Rent_Payment_Transaction_'.date('m-d-Y').'.xls');
	echo $html;
}








///-----------------------BREAK LINE
//Export Rental Records
if(isset($_GET['rental_records'])){
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

	$query = "SELECT rent.rent_id, member.member_id, member.Lname, member.Fname, member.Mname, equipment.eqp_id ,equipment.serial_no, equipment.eqp_name,equipment.rent_price, rent.rent_date, rent.due_date, rent.date_returned, (TIMESTAMPDIFF(day,rent.rent_date,rent.due_date)*equipment.rent_price) amount,(TIMESTAMPDIFF(hour,rent.due_date,rent.date_returned)*equipment.rent_price/24) as penalty, rent.pay FROM rent INNER JOIN member on member.member_id = rent.member_id INNER JOIN equipment on equipment.eqp_id = rent.eqp_id  GROUP BY rent_id DESC ";
	$stmt = $objtExport-> runQuery($query);
	$stmt->execute();
	
		$count= $stmt->rowCount();
		if($count > 0){
		while($rowRent = $stmt->fetch(PDO::FETCH_ASSOC)){
			$rent_date = date_create($rowRent['rent_date']);
            $due_date = date_create($rowRent['due_date']);
            $date_returned = date_create($rowRent['date_returned']);
           
           if($rowRent['penalty']<=0){
              $penalty = 0;      
              $total_amount = $rowRent['amount'] + $penalty;
              $status = "Ontime";
           }else{
              $penalty = ceil($rowRent['penalty']);                                
              $total_amount = $rowRent['amount'] + $penalty;
              $status = "Late";
           }
           $total_amount = $total_amount;
		$html.='<tr>
	         <td>'.$rowRent["rent_id"].'</td>
	         <td>'.$rowRent["member_id"].'</td>
	                <td>'.$objtEncrypt->decrypt($rowRent["Lname"]).'
	                '.$objtEncrypt->decrypt($rowRent["Fname"]).'
	                '.$objtEncrypt->decrypt($rowRent["Mname"]).'
	                </td>
	         <td>'.$rowRent["eqp_id"].'</td>
             <td>'.$objtEncrypt->decrypt($rowRent["serial_no"]).'</td>
             <td>'.$objtEncrypt->decrypt($rowRent["eqp_name"]).'</td>  
             <td>'.date_format($rent_date,'F d, Y h:i: A').'</td> 
             <td>'.date_format($due_date,'F d, Y h:i: A').'</td> 
             <td>'.date_format($date_returned,'F d, Y h:i: A').'</td> 
             <td>'.$status.'</td>  
             <td>'.$rowRent["amount"].'</td>
             <td>'.$penalty.'</td>   
             <td>'.$total_amount.'</td>   
             <td>'.$rowRent["pay"].'</td>             
		</tr>';
	}
	}
	$html.='</table>';
	header('Content-Type:application/vnd.ms-excel');
	header('Content-Disposition:attachment;filename=Rental_Record_'.date('m-d-Y').'.xls');
	echo $html;
}
 ?>



<!-- Export Selected Record -->
<?php 
///-----------------------BREAK LINE
//Export Rental Records
if(isset($_GET['loan_records'])){
	$html='<table>
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

	$query = "SELECT loan.loan_id, member.member_id, member.Lname, member.Fname, member.Mname, loan.based, loan.date_start, loan.due_date, loan.date_finish, loan.interest, loan.penalty, loan.total, register.username FROM loan INNER JOIN member ON member.member_id = loan.member_id INNER JOIN register ON register.user_id = loan.user_id";
	$stmt = $objtExport-> runQuery($query);
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
	   $html .='
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
	$html.='</table>';
	header('Content-Type:application/vnd.ms-excel');
	header('Content-Disposition:attachment;filename=Loan_Records_'.date('m-d-Y').'.xls');
	echo $html;
}
 ?>