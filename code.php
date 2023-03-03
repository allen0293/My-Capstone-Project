<?php 
	error_reporting(0);
	date_default_timezone_set('Asia/Manila');
	include('includes/security.php');
	require_once 'classes/Cregister.php';
	require_once 'classes/user_logs.php';
	require_once 'classes/user.php';
	$objtactivity =  new User();
	$objtUser_logs = new User_logs();
	$objtRegister = new Cregistration();

// GET
	if(isset($_GET['edit_id'])){
	    $user_id = $_GET['edit_id'];
	    $stmt = $objtUser->runQuery("SELECT * FROM register WHERE user_id = :user_id");
	    $stmt->execute(array(":user_id"=> $member_id));
	    $rowUser = $stmt->fetch(PDO::FETCH_ASSOC);
	}else{
	    $user_id = null;
	    $rowUser = null; 
	}

//POST inserT  user
	
	if(isset($_POST['registerbtn'])){
		$username = strip_tags($_POST['username']);
		$password = strip_tags($_POST['password']);
		$cpassword = strip_tags($_POST['confirmpassword']);
		$user_type = strip_tags($_POST['user_type']);
		$email = strip_tags($_POST['email']);
		$query = "SELECT * FROM register WHERE username = '$username'";
		$stmt = $objtRegister-> runQuery($query);
        $stmt->execute();
		$count= $stmt->rowCount();
          if($count > 0){
            	$objtRegister->redirect('register.php?username');		
				}else{
					$query = "SELECT * FROM register WHERE email = '$email'";
					$stmt = $objtRegister-> runQuery($query);
					$stmt->execute();
					$count= $stmt->rowCount();
					if($count>0){
						$objtRegister->redirect('register.php?email');
					}else{
						//encrypt password
						$hash = password_hash($password,PASSWORD_BCRYPT);
						if($objtRegister->insert($username, $email, $hash, $user_type)){	
								$user_id = $_SESSION['user_id'];
								$url = $_SERVER['REQUEST_URI'];
								$date_time = date("Y-m-d H:i:s");
								$action = "Added new User";
								$objtactivity->user_activity($user_id, $action, $url, $date_time);						
							$objtRegister->redirect('register.php?inserted');
						}else{		
							$objtRegister->redirect('register.php?error');
						}	
					}
			}       	
	}
	
	//set up admin
	if(isset($_POST['set_up'])){
		$username = strip_tags($_POST['username']);
		$password = strip_tags($_POST['password']);
		$cpassword = strip_tags($_POST['confirmpassword']);
		$user_type = strip_tags($_POST['user_type']);
		$email = strip_tags($_POST['email']);
		$password_length = strlen($password);
		if($password === $cpassword){
		//encrypt password
		if($password_length >= 8){
			if (preg_match('/[a-zA-Z]/', $password) && preg_match('/\d/',$password)&& preg_match('/[^a-zA-Z\d]/',$password)&&preg_match("/[A-Z]/",$password)){
				$hash = password_hash($password,PASSWORD_BCRYPT);
				if($objtRegister->insert($username, $email, $hash, $user_type)){	
					$_SESSION['set_up']='Success';
					header("Locatin: login.php");
				}	

			}else{
				$_SESSION['weak_password']='weak';
				header("Locatin: login.php");
			}
		}else{ 
			$_SESSION['short_password']='short';
			header("Locatin: login.php");
		}
	}else{ 
		$_SESSION['confirmation_pass']='incorrect';
		header("Locatin: index.php");
		}
    }

   //Login
   if(isset($_POST['login_btn'])){
   $username = $_POST['username'];
   $password = $_POST['password'];
        $query = "SELECT user_id, password, user_type, email FROM register WHERE username = '$username'";
        $stmt = $objtRegister-> runQuery($query);
        $stmt->execute();
        $count= $stmt->rowCount();
          if($count > 0){
             $rowUser = $stmt->fetch(PDO::FETCH_ASSOC);
             $user_id = $rowUser['user_id'];
             $user_type = $rowUser['user_type'];
              if(password_verify($password,$rowUser['password'])){
	              	$_SESSION['username']=$username;
	              	$_SESSION['user_type']=$user_type;
					$_SESSION['user_id']=$user_id;
					$_SESSION['email']= $rowUser['email'];
					$_SESSION['user_id'] = $rowUser['user_id'];
                    $login =  date("Y-m-d H:i:s");
	              	$objtUser_logs->insert($user_id,$login);
                    if($user_type == "user"){
					  $_SESSION['success']='Login Success';
					  $objtRegister->redirect('index.php');
                      }else{
						  $_SESSION['admin_success']='Login Success';
                          $objtRegister->redirect('register.php');
                      }
              }else{
				$_SESSION['error']="error";
            	$objtRegister->redirect('login.php');
          		}
          }else{
				$_SESSION['error']="error";
            $objtRegister->redirect('login.php');
          }
}

// update security key
if(isset($_POST['security'])){
	$ans1 = $_POST['ans1'];
	$ans2 = $_POST['ans2'];
	$ans3 = $_POST['ans3'];
	$user_id = $_SESSION['user_id'];

	$ans1 = password_hash($ans1,PASSWORD_BCRYPT);
	$ans2 = password_hash($ans2,PASSWORD_BCRYPT);
	$ans3 = password_hash($ans3,PASSWORD_BCRYPT);

	if($objtRegister->update_security($ans1, $ans2, $ans3, $user_id)){
		$_SESSION['security_key']="success";
		header("Location:index.php");
	}else{
	  echo'<script>swal ("Database Error", "", "error");  </script>';
	}
}


if(isset($_POST['admin_security'])){
	$ans1 = $_POST['ans1'];
	$ans2 = $_POST['ans2'];
	$ans3 = $_POST['ans3'];
	$user_id = $_SESSION['user_id'];

	$ans1 = password_hash($ans1,PASSWORD_BCRYPT);
	$ans2 = password_hash($ans2,PASSWORD_BCRYPT);
	$ans3 = password_hash($ans3,PASSWORD_BCRYPT);

	if($objtRegister->update_security($ans1, $ans2, $ans3, $user_id)){
		$_SESSION['security_key']="success";
		header("Location:register.php");
	}
}


?>