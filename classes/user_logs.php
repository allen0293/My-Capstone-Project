<?php

require_once 'database.php';

class User_logs {
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

    // Insert users
    public function insert($user_id,$login){
      try{
        $stmt = $this->conn->prepare("INSERT INTO user_logs(user_id,login) VALUES(:user_id, :login)");
        $stmt->bindparam(":user_id", $user_id);
        $stmt->bindparam(":login", $login);
        $stmt->execute();
        return $stmt;
      }catch(PDOException $e){
        echo $e->getMessage();
      }
    }
    //update user logs
    public function update($logout){
      try{
        $stmt = $this->conn->prepare("UPDATE user_logs SET logout = :logout WHERE logout is null ");
        $stmt->bindparam(":logout", $logout);
        $stmt->execute();
        return $stmt;
      }catch(PDOException $e){
        echo $e->getMessage();
      }
    }
    //Delete logs
    public function delete($log_id){
      try{
        $stmt = $this->conn->prepare("DELETE FROM user_logs WHERE log_id = :log_id");
        $stmt->bindparam(":log_id", $log_id);
        $stmt->execute();
        return $stmt;
      }catch(PDOException $e){
          echo $e->getMessage();
      }
    }

    //Delete all logs
    public function delete_all(){
      try{
        $stmt = $this->conn->prepare("DELETE FROM user_logs WHERE log_id > 0");
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
