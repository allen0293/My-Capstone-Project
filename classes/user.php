<?php

require_once 'database.php';

class User {
    private $conn;

    // Constructor
    public function __construct(){
      $database = new Database();
      $db = $database->dbConnection();
      $this->conn = $db;
    }


    // Execute queries SQL
    public function runQuery($sql){
      $stmt = $this->conn->prepare($sql);
      return $stmt;
    }

    // Insert
    public function insert($TIN, $Lname, $Fname, $Mname, $birthdate, $spouse_name, $address, $contactno,$land_location, $land_size, $crop1, $crop2, $crop3, $capital_build_up, $paid_up_capital,$reg_fee,$reg_date){
      try{
        $stmt = $this->conn->prepare("INSERT INTO member(TIN, Lname, Fname, Mname, birthdate, spouse_name, address, contactno, land_location, land_size, crop1, crop2, crop3, capital_build_up, paid_up_capital, reg_fee, registered_date) VALUES(:TIN, :Lname, :Fname, :Mname, :birthdate, :spouse_name, :address, :contactno, :land_location, :land_size, :crop1, :crop2, :crop3, :capital_build_up, :paid_up_capital, :reg_fee, :reg_date)");

        $stmt->bindparam(":TIN", $TIN);
        $stmt->bindparam(":Lname", $Lname);
        $stmt->bindparam(":Fname", $Fname);
        $stmt->bindparam(":Mname", $Mname);
        $stmt->bindparam(":birthdate", $birthdate);
        $stmt->bindparam(":spouse_name",$spouse_name);
        $stmt->bindparam(":address", $address);
        $stmt->bindparam(":contactno", $contactno);
        $stmt->bindparam(":land_location",$land_location);
        $stmt->bindparam(":land_size", $land_size);
        $stmt->bindparam(":crop1",$crop1);
        $stmt->bindparam(":crop2",$crop2);
        $stmt->bindparam(":crop3",$crop3);
        $stmt->bindparam(":capital_build_up",$capital_build_up);
        $stmt->bindparam(":paid_up_capital",$paid_up_capital);
        $stmt->bindparam(":reg_fee",$reg_fee);
        $stmt->bindparam(":reg_date",$reg_date);
        $stmt->execute();
        return $stmt;
      }catch(PDOException $e){
        echo $e->getMessage();
      }
    }


    // Update
    public function update($TIN, $Lname, $Fname, $Mname, $birthdate, $spouse_name, $address, $contactno, $land_location, $land_size, $crop1, $crop2, $crop3, $member_id){
      try{
        $stmt = $this->conn->prepare("UPDATE  member SET TIN = :TIN, Lname = :Lname, Fname = :Fname, Mname = :Mname, birthdate = :birthdate, spouse_name = :spouse_name, address = :address, contactno = :contactno, land_location = :land_location, land_size = :land_size, crop1 = :crop1, crop2 = :crop2, crop3 = :crop3 WHERE member_id = :member_id");
        $stmt->bindparam(":TIN", $TIN);
        $stmt->bindparam(":Lname", $Lname);
        $stmt->bindparam(":Fname", $Fname);
        $stmt->bindparam(":Mname", $Mname);
        $stmt->bindparam(":birthdate", $birthdate);
        $stmt->bindparam(":spouse_name",$spouse_name);
        $stmt->bindparam(":address", $address);
        $stmt->bindparam(":contactno", $contactno);
        $stmt->bindparam(":land_location",$land_location);
        $stmt->bindparam(":land_size", $land_size);
        $stmt->bindparam(":crop1",$crop1);
        $stmt->bindparam(":crop2",$crop2);
        $stmt->bindparam(":crop3",$crop3);
        $stmt->bindparam(":member_id",$member_id);
        $stmt->execute();
        return $stmt;
      }catch(PDOException $e){
        echo $e->getMessage();
      }
    }

    //update paid up capital
    public function update_puc($paid_up_capital,$member_id){
      try{
        $stmt = $this->conn->prepare("UPDATE  member SET paid_up_capital = :paid_up_capital+paid_up_capital WHERE member_id = :member_id");
        $stmt->bindparam(":paid_up_capital",$paid_up_capital);
        $stmt->bindparam(":member_id",$member_id);
        $stmt->execute();
        return $stmt;
      }catch(PDOException $e){
        echo $e->getMessage();
      }
    }


    //CAapital Build up
    public function update_cbu($cbu,$member_id){
      try{
        $stmt = $this->conn->prepare("UPDATE  member SET  capital_build_up = :capital_build_up+capital_build_up WHERE member_id = :member_id");
        $stmt->bindparam(":capital_build_up",$cbu);
        $stmt->bindparam(":member_id",$member_id);
        $stmt->execute();
        return $stmt;
      }catch(PDOException $e){
        echo $e->getMessage();
      }
    }

    //insert transaction record in paid_up_capital
    public function insert_puc($member_id,$paid,$date_pay,$user_id){
      try{
        $stmt = $this->conn->prepare("INSERT INTO puc_transaction(member_id,paid,date_pay,user_id) VALUES (:member_id, :paid,:date_pay,:user_id)");
        $stmt->bindparam(":member_id",$member_id);
        $stmt->bindparam(":paid",$paid);
        $stmt->bindparam(":date_pay",$date_pay);
        $stmt->bindparam(":user_id",$user_id);
        $stmt->execute();
        return $stmt;
      }catch(PDOException $e){
        echo $e->getMessage();
      }
    }

    // Delete
    public function delete($member_id){
      try{
        $stmt = $this->conn->prepare("DELETE FROM member WHERE member_id = :member_id");
        $stmt->bindparam(":member_id", $member_id);
        $stmt->execute();
        return $stmt;
      }catch(PDOException $e){
          echo $e->getMessage();
      }
    }
  
    // Redirect URL method
    public function redirect($url){
      header("Location: $url");
    }


    public function user_activity($user_id, $action, $url, $date_time){
      try{  
          $stmt = $this->conn->prepare("INSERT INTO user_activities(user_id, action, act_url, date_time) VALUES(:user_id, :action, :url, :date_time)");
          $stmt->bindparam(":user_id",$user_id);
          $stmt->bindparam(":action",$action);
          $stmt->bindparam(":url",$url);
          $stmt->bindparam(":date_time",$date_time);
          $stmt->execute();
          return $stmt;
      }catch(PDOException $e){
          echo $e->getMessage();
      }
    }

    //insert Loan
    public function insert_loan($member_id, $based,$user_id){
      try{  
          $stmt = $this->conn->prepare("INSERT INTO loan(member_id, based, date_start, due_date, date_finish, interest, penalty,total,user_id) VALUES(:member_id, :based,  NOW(), NULL, NULL, 0, 0, 0,:user_id)");
          $stmt->bindparam(":member_id",$member_id);
          $stmt->bindparam(":based",$based);
          $stmt->bindparam(":user_id",$user_id);
          $stmt->execute();
          return $stmt;
      }catch(PDOException $e){
          echo $e->getMessage();
      }
    }
    
    public function update_due_date(){
      try{  
          $stmt = $this->conn->prepare("UPDATE loan SET due_date = DATE_ADD(NOW(), INTERVAL  120 DAY) WHERE due_date is null");
          $stmt->execute();
          return $stmt;
      }catch(PDOException $e){
          echo $e->getMessage();
      }
    }

    public function update_loan($interest, $penalty,$total, $loan_id){
      try{  
          $stmt = $this->conn->prepare("UPDATE loan SET date_finish = NOW(), interest=:interest, penalty=:penalty, total=:total WHERE loan_id = :loan_id");
          $stmt->bindparam(":interest",$interest);
          $stmt->bindparam(":penalty",$penalty);
          $stmt->bindparam(":total",$total);
          $stmt->bindparam(":loan_id",$loan_id);
          $stmt->execute();
          return $stmt;
      }catch(PDOException $e){
          echo $e->getMessage();
      }
    }


    public function delete_loan($loan_id){
      try{
        $stmt = $this->conn->prepare("DELETE FROM loan WHERE loan_id = :loan_id");
        $stmt->bindparam(":loan_id", $loan_id);
        $stmt->execute();
        return $stmt;
      }catch(PDOException $e){
          echo $e->getMessage();
      }
    }

}
?>
