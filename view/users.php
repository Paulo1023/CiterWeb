<?php
include './session/session.php';
include '../class/Users.php';


date_default_timezone_set('Asia/Manila');

$users = new Users();
$result = $users->getUsersList();




if(isset($_POST['saveUserData']) && ($_POST['confirmSecret'] == $_POST['secret'])) {
  $usernameValid = true;
  foreach($result as $row){
    if($row['user_name'] == $_POST['username'] )
    {
      $usernameValid = false;
    } 
    
  }
  if($usernameValid)
  {
    $today = date('Y-m-d H:i:s');
    $isRegistered = $users->registerUser($_POST['username'], $_POST['secret'], $today);
    header("Location: users.php");
  } else{ }

}

if(isset($_GET['delete'])){
  $id = validate($_GET['delete']);
  $users->deleteUser($id);
  header("Location: users.php");
}

function validate($value) {
  $value = trim($value);
  $value = stripslashes($value);
  $value = htmlspecialchars($value);
  return $value;
  }


  if(isset($_POST['editUserNewPass']) && isset($_POST['editUserPassConfirm']))
  {
    $usernameValid = true;
    foreach($result as $row){
      if($row['user_name'] == $_POST['editUserName'] )  
      {
        $usernameValid = false;
      } 
    }
    if($usernameValid)
    {
      if($_POST['editUserNewPass'] == $_POST['editUserPassConfirm'])
      {
        $hashPass = md5($_POST['editUserPassConfirm']);
        $id = $_POST['editUserID'];
        $isEdited = $users->updateUser($id, $_POST['editUserName'], $hashPass);
        header("Location: users.php");
      }
    } else { }
    
  }


  if (isset($_GET['pageno'])) {
    $pageno = $_GET['pageno'];
  } else {
    $pageno = 1;
  }
  $usersRow = $users->countUsers();
  
  $no_of_records_per_page = 10;
  $offset = ($pageno-1) * $no_of_records_per_page;
  $total_pages = ceil($usersRow / $no_of_records_per_page); 
  
  $listUsers = $users->usersList($offset, $no_of_records_per_page);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>CITERWEB</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet' type="text/css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />
    <link href='./css/bootstrap.min.css' rel='stylesheet' type="text/css">
    <link href="css/styles.css" rel="stylesheet" type="text/css" />
    <style>
    .userContainer {
      margin-top: 80px;
    }
    .addUserBtn {
        float: right;
        margin-bottom: 15px;
    }
    table, th, td {
        border: 1px solid gray;
    }
    .home-section {
      padding-bottom: 80px;
      padding-left: 80px;
      padding-right: 80px;
      display: table;
    }
    .pagination {
        float: right;
      }
      .pagination li {
        margin-right: 15px;
      }
      
    </style>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</head>
<?php 

?>

<body>
  <div class="sidebar">
    <div class="logo-details">
        <div class="logo_name unselectable">CITER APP Content Management</div>
        <i class='bx bx-menu' id="btn" ></i>
    </div>
    <ul class="nav-list">
      <li>
        <a href="./dashboard.php">
          <i class='bx bx-grid-alt'></i>
          <span class="links_name">Dashboard</span>
        </a>
         <span class="tooltip">Dashboard</span>
      </li>
      <li>
       <a href="./reviewers.php">
         <i class='bx bx-library' ></i>
         <span class="links_name">Reviewers</span>
       </a>
       <span class="tooltip">Reviewers</span>
     </li>
     <li>
       <a href="./questionnaires.php">
         <i class='bx bx-notepad' ></i>
         <span class="links_name">Questionnaires</span>
       </a>
       <span class="tooltip">Questionnaires</span>
     </li>
     <?php if($_COOKIE['userType'] == "SUPERUSER"){ ?>
     <li>
       <a href='users.php'>
         <i class='bx bx-user' ></i>
         <span class='links_name'>Users</span>
       </a>
       <span class='tooltip'>Users</span>
     </li>
     <?php } ?>
     <li>
       <a href="./logs.php">
         <i class='bx bx-receipt' ></i>
         <span class="links_name">Logs</span>
       </a>
       <span class="tooltip">Logs</span>
     </li>
     <li>
       <a href="./accountSettings.php">
         <i class='bx bx-cog' ></i>
         <span class="links_name">Account Settings</span>
       </a>
       <span class="tooltip">Account Settings</span>
     </li>
     <li class="profile">
         <div class="profile-details">
           <div class="name_job unselectable">
           <div class="name"><?php echo strtoupper($_COOKIE['user']); ?></div>
             <div class="job"><?php echo $_COOKIE['userType']; ?></div>
           </div>
         </div>
            <a title="Logout" href="./../logout.php" id="log_out">
                <i class='bx bx-log-out'  ></i>
            </a>        
     </li>
    </ul>
  </div>
  <section class="home-section">
      <div class="text">Users</div>
      <div class="userContainer container-fluid">
      <input type="button" class="addUserBtn btn btn-primary" data-toggle="modal" data-target="#addUserModal" value="Add Account"></input>
        <table class="table container-fluid">
            <thead class="thead-dark">
                <tr>
                <th scope="col">ID</th>
                <th scope="col">Username</th>
                <th scope="col">Date Created</th>
                <th scope="col">Last Logged In</th>
                <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
                for($i=0; $i < count($listUsers); $i++){
                  if($listUsers[$i]['user_name'] == $_COOKIE['user'] || $listUsers[$i]['superuser'] == 1) {}
                  else {
            ?>
                <tr>
                <th><?php echo $listUsers[$i]['id']; ?></th>
                <td><?php echo $listUsers[$i]['user_name']; ?></td>
                <td><?php echo $listUsers[$i]['date_created']; ?></td>
                <td><?php echo $listUsers[$i]['last_loggedin'];  ?></td>
                <td>

                
                    <a id="editUserBtn" class="btn btn-info" href="?edit=<?php echo $listUsers[$i]['id']; ?>" >Edit </a>
                    <a id="delUserBtn" name="delUserBtn" class="btn btn-danger" href="?delete=<?php echo $listUsers[$i]['id']; ?>" >Delete</a>
                    
                </td>
                </tr>
                <?php } } ?>
            </tbody>
            </table>
            <ul class="pagination" >
                <li class="<?php if($pageno <= 1){ echo 'disabled'; } ?>">
                    <a class="btn btn-primary" href="<?php if($pageno <= 1){ echo '#'; } else { echo "?pageno=".($pageno - 1); } ?>">Prev</a>
                </li>
                <li><?php echo $pageno; ?> / <?php echo $total_pages; ?></li>
                <li class="<?php if($pageno >= $total_pages){ echo 'disabled'; } ?>">
                    <a class="btn btn-primary" href="<?php if($pageno >= $total_pages){ echo '#'; } else { echo "?pageno=".($pageno + 1); } ?>">Next</a>
                </li>
            </ul>
      </div>
  </section>

  <!-- modal add user-->
  <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modal-title">Add User</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="post">
            <div class="form-group row">
              <label for="username" class="col-sm-2 col-form-label">Username</label>
              <div class="col-sm-10">
                <input class="form-control" name="username" id="username" type="text" placeholder="username" required>
              </div>
            </div>
            <div class="form-group row">
              <label for="secret" class="col-sm-2 col-form-label">Password</label>
              <div class="col-sm-9">
                <input class="form-control" name="secret" id="secret" type="password" placeholder="password" required>
              </div>
              <i class="col-sm-1 bi bi-eye-slash" id="togglePassAdd"></i>
            </div>
            <div class="form-group row">
              <label for="confirmSecret" class="col-sm-2 col-form-label">Confirm Password</label>
              <div class="col-sm-9">
                <input class="form-control" name="confirmSecret" id="confirmSecret" type="password" placeholder="confirm password" required>
              </div>
              <i class="col-sm-1 bi bi-eye-slash" id="toggleConfPassAdd"></i>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <input type="submit" class="btn btn-primary" name="saveUserData"></input>
          </form>
        </div>
      </div>
    </div>
  </div>

<!-- modal edit user -->
<?php  

  if(isset($_GET['edit'])){
    $uname;
    $pass;
    $editId = $_GET['edit'];
    for($i=0; $i < count($result); $i++){
      if($result[$i]['id'] == $editId){
        $uname = $result[$i]['user_name'];
        $pass = $result[$i]['password'];
      }
    }
    echo "<script type='text/javascript'>
            $(document).ready(function(){
              $('#editUserModal').modal('show');
            });
          </script>";
  }
?>
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modal-title">Edit User</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="post">
          <div class="form-group row" hidden>
              <label for="editUserID" class="col-sm-2 col-form-label">ID</label>
              <div class="col-sm-10">
                <input class="form-control" name="editUserID" id="editUserID" type="text" value="<?php echo $editId; ?>" readonly>
              </div>
            </div>
            <div class="form-group row">
              <label for="editUserName" class="col-sm-2 col-form-label">Username</label>
              <div class="col-sm-10">
                <input class="form-control" name="editUserName" id="editUserName" type="text" value="<?php echo $uname; ?>" required>
              </div>
            </div>
            
            <div class="form-group row">
              <label for="editUserNewPass" class="col-sm-2 col-form-label">new Password</label>
              <div class="col-sm-9">
                <input class="form-control" name="editUserNewPass" id="editUserNewPass" type="password" placeholder="new password" required>
              </div>
              <i class="col-sm-1 bi bi-eye-slash" id="togglenewPassEdit"></i>
            </div>
            <div class="form-group row">
              <label for="editUserPassConfirm" class="col-sm-2 col-form-label">Confirm Password</label>
              <div class="col-sm-9">
                <input class="form-control" name="editUserPassConfirm" id="editUserPassConfirm" type="password" placeholder="confirm password" required>
              </div>
              <i class="col-sm-1 bi bi-eye-slash" id="toggleConfPassEdit"></i>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <input type="submit" class="btn btn-primary" name="saveUserEditData" ></input>
          </form>
        </div>
      </div>
    </div>
  </div>
<?php

?>
  
  
  <script>
  let sidebar = document.querySelector(".sidebar");
  let closeBtn = document.querySelector("#btn");
  
  const toggleNewPassEdit = document.querySelector('#togglenewPassEdit');
  const newPasswordEdit = document.querySelector('#editUserNewPass');

  toggleNewPassEdit.addEventListener('click', function (e) {
    
      const type = newPasswordEdit.getAttribute('type') === 'password' ? 'text' : 'password';
      newPasswordEdit.setAttribute('type', type);
      
      this.classList.toggle('bi-eye');
          
        
    });

  const toggleConfPassEdit = document.querySelector('#toggleConfPassEdit');
  const confPasswordEdit = document.querySelector('#editUserPassConfirm');

  toggleConfPassEdit.addEventListener('click', function (e) {
    
      const type = confPasswordEdit.getAttribute('type') === 'password' ? 'text' : 'password';
      confPasswordEdit.setAttribute('type', type);
      
      this.classList.toggle('bi-eye');
          
        
    });

    const togglePassAdd = document.querySelector('#togglePassAdd');
    const passwordAdd = document.querySelector('#secret');

    togglePassAdd.addEventListener('click', function (e) {
      
      const type = passwordAdd.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordAdd.setAttribute('type', type);
      
      this.classList.toggle('bi-eye');
          
        
    });

    const toggleConfPassAdd = document.querySelector('#toggleConfPassAdd');
    const confPasswordAdd = document.querySelector('#confirmSecret');

    toggleConfPassAdd.addEventListener('click', function (e) {
      
      const type = confPasswordAdd.getAttribute('type') === 'password' ? 'text' : 'password';
      confPasswordAdd.setAttribute('type', type);
      
      this.classList.toggle('bi-eye');
          
        
    });

  
  closeBtn.addEventListener("click", ()=>{
    sidebar.classList.toggle("open");
    menuBtnChange();
  });

 
  function menuBtnChange() {
   if(sidebar.classList.contains("open")){
     closeBtn.classList.replace("bx-menu", "bx-menu-alt-right");//replacing the iocns class
   }else {
     closeBtn.classList.replace("bx-menu-alt-right","bx-menu");//replacing the iocns class
   }
  }
  </script>
</body>
</html>

