
  
   <!-- Sidebar -->
   <ul class="navbar-nav bg-success sidebar sidebar-dark accordion" id="accordionSidebar">
<!-- Sidebar - Brand -->
<a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
  <div class="sidebar-brand-icon mt-5">
    <img src="img/farmerlogo.png" style="width: 60%">
  </div>
</a>
<!-- Divider -->
<br><br>
<hr class="sidebar-divider my-0">

<!-- Nav Item - Dashboard -->
<li class="nav-item active">
  <a class="nav-link" href="index.php" >
    <i class="fas fa-columns" style="font-size:18px"></i>
    <span class="font_size" >Dashboard</span></a>
</li>
<!-- Divider -->
<hr class="sidebar-divider">
<!-- Heading -->
<!-- Nav Item - Pages Collapse Menu -->
<li class="nav-item">
  <a class="nav-link " href="member.php">
    <i class="fas fa-users" style="font-size:18px"></i>
    <span>Member</span>
  </a>
</li>
<!-- Nav Paid up Capital-->
<li class="nav-item">
  <a class="nav-link " href="loan.php">
  <i class="fas fa-hand-holding-usd" style="font-size:18px"></i>
    <span>Loan</span>
  </a>
</li>
<!-- Equipment table -->
<li class="nav-item">
  <a class="nav-link" href="display_equipment.php" >
    <i class="fas fa-fw fa-wrench" style="font-size:18px"></i>
    <span>Equipment</span>
  </a>
</li>

<!-- Nav  - Rents Form -->
<li class="nav-item">
  <a class="nav-link" href="frm_rent.php">
    <i class="fas fa-fw fa-chart-area" style="font-size:18px"></i>
    <span>Rent</span></a>
</li>

<!-- Nav Rent - Tables -->
<li class="nav-item">
  <a class="nav-link" href="rent_record.php">
    <i class="fas fa-server" style="font-size:18px"></i> 
    <span>Rental Records</span></a>
</li>
<!--li class="nav-item" id="Admin-hide">
  <div class="dropdown">
  <a class="nav-link collapsed" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
   <i class="fas fa-user-cog"></i> <span>Admin  <i class="fas fa-chevron-down"></i></span> 
  </a>
  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
    <h6 class="dropdown-header">Admin:</h6>
    <a class="dropdown-item" href="register.php">Users Account</a>
    <a class="dropdown-item" href="user_logs.php">Users logs</a>
    <a class="dropdown-item" href="user_activity.php">Users Activity</a>
  </div>
</div>
</li-->

    <li class="nav-item" id="Admin-hide">
      <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
        <i class="fas fa-user-cog" style="font-size:18px"></i>
        <span>Admin</span>
      </a>
      <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
          <h6 class="collapse-header">Admin:</h6>
          <a class="collapse-item" href="register.php">Users Account</a>
          <a class="collapse-item" href="user_logs.php">Users logs</a>
          <a class="collapse-item" href="user_activity.php">Users Activity</a>
        </div>
      </div>
    </li>

<!-- Divider -->
<hr class="sidebar-divider d-none d-md-block">

<!-- Sidebar Toggler (Sidebar) -->
<div class="text-center d-none d-md-inline">
  <button class="rounded-circle border-0" id="sidebarToggle"></button>
</div>

</ul>
<!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>

          

            <!-- Nav Item - Alerts -->
          <ul class="navbar-nav ml-auto">
            <div class="topbar-divider d-none d-sm-block"></div>

            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown1" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">      
                <?php echo $_SESSION['username']; ?>
                  
                </span>
                <img class="img-profile rounded-circle" src="img/farmerlogo.png">
              </a>
              <!-- Dropdown - User Information -->
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown1">
              
               <span class="dropdown-item">
                    <?php echo $_SESSION['email']; ?>
                </span>

                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#edit_pass_Modal">
                  <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                  Change Password
                </a>

                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#emailModal" >
                <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                  Change Email
                </a>

                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  Logout
                </a>
              </div>
            </li>

          </ul>

        </nav>
        <!-- End of Topbar -->
  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>
  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body"> 
        Select "Logout" below if you want to logout.
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <form action="logout.php" method="POST"> 
            <button type="submit" name="logout_btn" class="btn btn-primary">Logout</button>
          </form>
        </div>
      </div>
    </div>
  </div>


 <!-- Email modal-->
  <div class="modal fade" id="emailModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Change Email</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
        <form action="" method="post">
          <input class="form-control" type="email" name="email" placeholder="New Email Address" requiered>
     
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
        
            <button type="submit" name="update_email" class="btn btn-primary">Save</button>
          </form>
        </div>
      </div>
    </div>
  </div>


  <!-- Change password modal-->
  <div class="modal fade" id="edit_pass_Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Change Password</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form method="POST">
        <div class="modal-body">
          <div class="form-group">
                  <label>Password</label>
                  <input type="password" id="password" name="oldpassword" class="form-control" placeholder="Enter Old Password" required="" minlength="8">
            </div>

            <div class="form-group">
                  <label>New Password</label>
                  <input type="password" id="newpassword" name="newpassword" class="form-control" placeholder="Enter New Password" required="" minlength="8">
            </div>

            <div class="form-group">
                  <label>Confirm Password</label>
                  <input type="password" id="confirmpassword" name="confirmpassword" class="form-control" placeholder="Confirm Password" required="" minlength="8">
            </div>
            <div class="custom-control custom-checkbox ml-3" >
                  <input type="checkbox" class="custom-control-input" id="customCheck1" onclick="showPassx()"  >
                  <label class="custom-control-label" for="customCheck1">Show Password</label>
              </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            <button type="submit" name="update_pass" class="btn btn-primary">Save</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script>
function showPassx() {
  var x = document.getElementById("password");
  var y = document.getElementById("newpassword");
  var z = document.getElementById("confirmpassword");
  if (x.type === "password" || y.type === "password" || z.type === "password") {
    x.type = "text";
    y.type = "text";
    z.type = "text";
  } else {
    x.type = "password";
    y.type = "password";
    z.type = "password";
  }
}
</script>
  <?php include('includes/scripts.php'); ?>
  
 <?php 
 date_default_timezone_set('Asia/Manila');
 require_once 'classes/database.php';
 class Update {
     private $conn;
 
     // Constructor
     public function __construct(){
       $database = new Database();
       $db = $database->dbConnection();
       $this->conn = $db;
     }

     
    //Update Email
    public function update_email($new_email,$username){
      try{
        $stmt = $this->conn->prepare("UPDATE register SET email = :new_email WHERE username=:username");
        $stmt->bindparam(":new_email", $new_email);
        $stmt->bindparam(":username", $username);
        $stmt->execute();
        return $stmt;
      }catch(PDOException $e){
        echo $e->getMessage();
      }
    }
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

    public function runQuery($sql){
      $stmt = $this->conn->prepare($sql);
      return $stmt;
    }

  }

  $objRegister = new Update();
  if(isset($_POST['update_email'])){
      $new_email = $_POST['email'];
      $username = $_SESSION['username'];
      $stmt = $objRegister->runQuery("SELECT * FROM register WHERE email = :email");
      $stmt->execute(array(":email"=> $new_email));
      $rowUser = $stmt->fetch(PDO::FETCH_ASSOC);
      $count = $stmt->rowCount();
      if($count>0){
          echo'
            <script>
                swal("Email already existed, Try another email.", "", "info");
            </script>
          ';
      }else{
        $stmt = $objRegister->runQuery("SELECT * FROM register WHERE username = :username");
        $stmt->execute(array(":username"=> $username));
        $rowUser = $stmt->fetch(PDO::FETCH_ASSOC);
        if($objRegister->update_email($new_email,$username)){
          $user_id = $_SESSION['user_id'];
          $url = $_SERVER['REQUEST_URI'];
          $date_time = date("Y-m-d H:i:s");
          $action = "Changed Email";
          $objRegister->user_activity($user_id, $action, $url, $date_time);
          echo'
          <script>
              swal("Email successfully Updated", "", "success");
          </script>
        ';
        }else{
          die();
        }
      }
  }
  

  if(isset($_POST['update_pass'])){
    $username = $_SESSION['username'];
    $oldpassword = $_POST['oldpassword'];
    $newpassword = $_POST['newpassword'];
    $cpassword = $_POST['confirmpassword'];
    $password_length = strlen($newpassword);
         $query = "SELECT user_id, password FROM register WHERE username = '$username'";
         $stmt = $objRegister-> runQuery($query);
         $stmt->execute();
         $count= $stmt->rowCount();
 
           if($count > 0){
              $rowUser = $stmt->fetch(PDO::FETCH_ASSOC);
              $id = $rowUser['user_id'];
               
                 if(password_verify($oldpassword,$rowUser['password'])){
                     if ($newpassword === $cpassword) {
                        
                        if($password_length >= 8){
                          if (preg_match('/[a-zA-Z]/', $newpassword) && preg_match('/\d/',$newpassword)&& preg_match('/[^a-zA-Z\d]/',$newpassword)&&preg_match("/[A-Z]/",$newpassword)){
                          
                              $hash = password_hash($newpassword,PASSWORD_DEFAULT);
                        
                                if($objRegister->update_password($hash, $id)){     
                                    $user_id = $_SESSION['user_id'];
                                    $url = $_SERVER['REQUEST_URI'];
                                    $date_time = date("Y-m-d H:i:s");
                                    $action = "Changed password";
                                    $objRegister->user_activity($user_id, $action, $url, $date_time);
          
                                    echo'<script>swal ("Password Successfully Updated", "", "success");  </script>';
                                }else{  
                                      echo'<script>swal ("Db Error, Something went wrong", "", "error");  </script>';
                                    }
                              }else{
                                  echo'<script>swal ("Please provide aleast one letter with uppercase, one number and one special character", "New password not set", "error");  </script>';
                              }
                          }else{ 
                            echo'<script>swal ("Your password is too short, Please provide 8 character lenght or more, to secure your password", "New password not set", "error");  </script>';
                          }
                       
                       }else{ 
                          echo'<script>swal ("Confirm Password Did not match, Please try again", "", "error");  </script>';
                        }
                 
                      }else{ 
                        echo'<script>swal ("Password Incorrect, Please try again", "", "error");  </script>';
                      }
                   }
 }
 ?> 