<?php

require_once 'database.php';

class Payment {
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
    public function insert($rent_id, $amount, $paid,$date_pay,$user_id){
      try{
        $stmt = $this->conn->prepare("INSERT INTO  rent_payment(rent_id,  amount, paid, date_pay,user_id) VALUES(:rent_id, :amount, :paid , :date_pay, :user_id )");
        $stmt->bindparam(":rent_id", $rent_id);
        $stmt->bindparam(":amount", $amount);
        $stmt->bindparam(":paid", $paid);
        $stmt->bindparam(":date_pay", $date_pay);
        $stmt->bindparam(":user_id", $user_id);
        $stmt->execute();
        return $stmt;
      }catch(PDOException $e){
        echo $e->getMessage();
      }
    }
    //Delete  transaction payment for Rent
    public function delete($pay_id){
      try{
        $stmt = $this->conn->prepare("DELETE FROM rent_payment WHERE pay_id = :pay_id");
        $stmt->bindparam(":pay_id", $pay_id);
        $stmt->execute();
        return $stmt;
      }catch(PDOException $e){
          echo $e->getMessage();
      }
    }
    //Delete all Trasaction payment for Rent
     public function delete_all(){
      try{
        $stmt = $this->conn->prepare("DELETE FROM rent_payment WHERE pay_id > 0");
        $stmt->execute();
        return $stmt;
      }catch(PDOException $e){
          echo $e->getMessage();
      }
    }



  //Delete  transaction payment for Paid Up Capital
    public function delete_puc($puc_id){
      try{
        $stmt = $this->conn->prepare("DELETE FROM puc_transaction WHERE puc_id = :puc_id");
        $stmt->bindparam(":puc_id", $puc_id);
        $stmt->execute();
        return $stmt;
      }catch(PDOException $e){
          echo $e->getMessage();
      }
    }

    //Delete all Trasaction payment for Paid up Capital
     public function delete_all_puc(){
      try{
        $stmt = $this->conn->prepare("DELETE FROM puc_transaction WHERE puc_id > 0");
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
