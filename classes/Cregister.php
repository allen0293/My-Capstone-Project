<?php

require_once 'database.php';

class Cregistration {
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
    public function insert($username, $email, $password, $user_type){
      try{
        $stmt = $this->conn->prepare("INSERT INTO register(username,email,password,user_type) VALUES(:username, :email, :password,:user_type)");
        $stmt->bindparam(":username", $username);
        $stmt->bindparam(":email", $email);
        $stmt->bindparam(":password", $password);
        $stmt->bindparam(":user_type", $user_type);
        $stmt->execute();
        return $stmt;
      }catch(PDOException $e){
        echo $e->getMessage();
      }
    }
    //update user Password
    public function update($password, $user_type, $user_id){
      try{
        $stmt = $this->conn->prepare("UPDATE register SET password = :password, user_type = :user_type WHERE user_id=:user_id");
        $stmt->bindparam(":password", $password);
        $stmt->bindparam(":user_type", $user_type);
        $stmt->bindparam(":user_id", $user_id);
        $stmt->execute();
        return $stmt;
      }catch(PDOException $e){
        echo $e->getMessage();
      }
    }
    //update password
    public function update_password($password, $user_id){
      try{
        $stmt = $this->conn->prepare("UPDATE register SET password = :password WHERE user_id=:user_id");
        $stmt->bindparam(":password", $password);
        $stmt->bindparam(":user_id", $user_id);
        $stmt->execute();
        return $stmt;
      }catch(PDOException $e){
        echo $e->getMessage();
      }
    }
    //update code
    public function update_code($code, $email){
      try{
        $stmt = $this->conn->prepare("UPDATE register SET code = :code, time_expired = DATE_ADD(NOW(), INTERVAL  30 MINUTE) WHERE email=:email");
        $stmt->bindparam(":code", $code);
        $stmt->bindparam(":email", $email);
        $stmt->execute();
        return $stmt;
      }catch(PDOException $e){
        echo $e->getMessage();
      }
    }

    //reset password
    public function reset_password($password, $email){
      try{
        $stmt = $this->conn->prepare("UPDATE register SET password = :password WHERE email=:email");
        $stmt->bindparam(":password", $password);
        $stmt->bindparam(":email", $email);
        $stmt->execute();
        return $stmt;
      }catch(PDOException $e){
        echo $e->getMessage();
      }
    }

    //reset password using Questionaire
    public function reset_passwords($password, $username){
      try{
        $stmt = $this->conn->prepare("UPDATE register SET password = :password WHERE username=:username");
        $stmt->bindparam(":password", $password);
        $stmt->bindparam(":username", $username);
        $stmt->execute();
        return $stmt;
      }catch(PDOException $e){
        echo $e->getMessage();
      }
    }

    //reset Code
    public function reset_code($email){
      try{
        $stmt = $this->conn->prepare("UPDATE register SET code = '' WHERE email=:email");
        $stmt->bindparam(":email", $email);
        $stmt->execute();
        return $stmt;
      }catch(PDOException $e){
        echo $e->getMessage();
      }
    }
    
    //Delete user
    public function delete($user_id){
      try{
        $stmt = $this->conn->prepare("DELETE FROM register WHERE user_id = :user_id");
        $stmt->bindparam(":user_id", $user_id);
        $stmt->execute();
        return $stmt;
      }catch(PDOException $e){
          echo $e->getMessage();
      }
    }
    
    public function update_security($ans1, $ans2, $ans3, $user_id){
      try{
        $stmt = $this->conn->prepare("UPDATE register SET ans1 = :ans1, ans2 = :ans2, ans3 = :ans3 WHERE user_id=:user_id");
        $stmt->bindparam(":ans1", $ans1);
        $stmt->bindparam(":ans2", $ans2);
        $stmt->bindparam(":ans3", $ans3);
        $stmt->bindparam(":user_id", $user_id);
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
