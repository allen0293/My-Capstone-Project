<?php

require_once 'database.php';

class Equipment {
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
    public function insert($serial_no, $eqp_name, $eqp_model, $eqp_desc, $rent_price, $status){
      try{
        $stmt = $this->conn->prepare("INSERT INTO equipment(serial_no, eqp_name, eqp_model, eqp_desc, rent_price, status) VALUES(:serial_no, :eqp_name, :eqp_model, :eqp_desc, :rent_price, :status)");
        $stmt->bindparam(":serial_no", $serial_no);
        $stmt->bindparam(":eqp_name", $eqp_name);
        $stmt->bindparam(":eqp_model", $eqp_model);
        $stmt->bindparam(":eqp_desc", $eqp_desc);
        $stmt->bindparam(":rent_price", $rent_price);
        $stmt->bindparam(":status", $status);
        $stmt->execute();
        return $stmt;
      }catch(PDOException $e){
        echo $e->getMessage();
      }
    }


    // Update
    public function update($serial_no, $eqp_name, $eqp_model, $eqp_desc, $rent_price,$eqp_id){
      try{
        $stmt = $this->conn->prepare("UPDATE  equipment SET serial_no=:serial_no, eqp_name=:eqp_name, eqp_model=:eqp_model, eqp_desc=:eqp_desc, rent_price=:rent_price WHERE eqp_id = :eqp_id");
        $stmt->bindparam(":serial_no", $serial_no);
        $stmt->bindparam(":eqp_name", $eqp_name);
        $stmt->bindparam(":eqp_model", $eqp_model);
        $stmt->bindparam(":eqp_desc", $eqp_desc);
        $stmt->bindparam(":rent_price", $rent_price);
        $stmt->bindparam(":eqp_id",$eqp_id);
        $stmt->execute();
        return $stmt;
      }catch(PDOException $e){
        echo $e->getMessage();
      }
    }
    //Update status
    public function status_unoccupied($eqp_id){
      try{
        $stmt = $this->conn->prepare("UPDATE  equipment SET status='unoccupied' WHERE status='occupied' AND eqp_id = :eqp_id  ");
        $stmt->bindparam(":eqp_id",$eqp_id);
        $stmt->execute();
        return $stmt;
      }catch(PDOException $e){
        echo $e->getMessage();
      }
    }
    public function status_occupied($eqp_id){
      try{
        $stmt = $this->conn->prepare("UPDATE  equipment SET status='occupied' WHERE status='unoccupied' AND eqp_id = :eqp_id");
        $stmt->bindparam(":eqp_id",$eqp_id);
        $stmt->execute();
        return $stmt;
      }catch(PDOException $e){
        echo $e->getMessage();
      }
    }

    // Delete
    public function delete($eqp_id){
      try{
        $stmt = $this->conn->prepare("DELETE FROM equipment WHERE eqp_id = :eqp_id");
        $stmt->bindparam(":eqp_id", $eqp_id);
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
