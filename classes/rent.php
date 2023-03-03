<?php

require_once 'database.php';

class Rent {
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
    public function insert($eqp_id, $member_id, $rent_date, $due_date,$amount){
      try{
        $stmt = $this->conn->prepare("INSERT INTO rent(eqp_id,member_id,rent_date,due_date,amount,pay) VALUES(:eqp_id, :member_id, :rent_date, :due_date,:amount, 0)");
        $stmt->bindparam(":eqp_id", $eqp_id);
        $stmt->bindparam(":member_id", $member_id);
        $stmt->bindparam(":rent_date", $rent_date);
        $stmt->bindparam(":due_date", $due_date);
        $stmt->bindparam(":amount", $amount);
        $stmt->execute();
        return $stmt;
      }catch(PDOException $e){
        echo $e->getMessage();
      }
    }

    // Update
    public function update($pay,$rent_id){
      try{
        $stmt = $this->conn->prepare("UPDATE  rent SET pay=:pay+pay WHERE rent_id = :rent_id");
        $stmt->bindparam(":pay", $pay);
        $stmt->bindparam(":rent_id", $rent_id);
        $stmt->execute();
        return $stmt;
      }catch(PDOException $e){
        echo $e->getMessage();
      }
    }
    
    //Update rent
    public function update_date_returned($date_returned,$rent_id){
      try{
        $stmt = $this->conn->prepare("UPDATE  rent SET date_returned=:date_returned  WHERE rent_id = :rent_id");
        $stmt->bindparam(":date_returned", $date_returned);
        $stmt->bindparam(":rent_id", $rent_id);
        $stmt->execute();
        return $stmt;
      }catch(PDOException $e){
        echo $e->getMessage();
      }
    }

    //Update Amount
    public function update_amount($amount,$rent_id){
      try{
        $stmt = $this->conn->prepare("UPDATE  rent SET amount=:amount  WHERE rent_id = :rent_id");
        $stmt->bindparam(":amount", $amount);
        $stmt->bindparam(":rent_id", $rent_id);
        $stmt->execute();
        return $stmt;
      }catch(PDOException $e){
        echo $e->getMessage();
      }
    }


    // Delete
    public function delete($rent_id){
      try{
        $stmt = $this->conn->prepare("DELETE FROM rent WHERE rent_id = :rent_id");
        $stmt->bindparam(":rent_id", $rent_id);
        $stmt->execute();
        return $stmt;
      }catch(PDOException $e){
          echo $e->getMessage();
      }
    }
    //Delete all 
     public function delete_all(){
      try{
        $stmt = $this->conn->prepare("DELETE FROM rent WHERE rent_id > 0");
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

}
?>
