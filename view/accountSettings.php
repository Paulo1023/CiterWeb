<?php

include './session/session.php';
include '../class/Users.php';

$users = new Users();


if(isset($_POST['pass']) && isset($_POST['changeName']))
{
  if($_COOKIE['secret'] == $_POST['CNpass'])
  {
    $_COOKIE['user'] = $_POST['changeName'];
    $isEdited = $users->updateUser($_SESSION['userId'], $_POST['changeName'], md5($_POST['CNpass']));
    header("Location: accountSettings.php");
  }
}
 
if(isset($_POST['oldPass']) && isset($_POST['newPass']) && isset($_POST['confirmPass']))
{
    if(($_POST['oldPass'] == $_COOKIE['secret']) && ($_POST['newPass'] == $_POST['confirmPass']))
    {
      $_COOKIE['secret'] = $_POST['confirmPass'];
      $isEdited = $users->updateUser($_SESSION['userId'], $_COOKIE['user'],  md5($_POST['confirmPass']));
      header("Location: accountSettings.php");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>CITERWEB</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/styles.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet' type="text/css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    <style>
      .confirmBtn {
        float: right;
      }
      .cont {
        max-width: 740px;
        min-width: 400px;
      }
      .home-section {
        padding-bottom: 80px;
        padding-left: 80px;
        padding-right: 80px;
        display: table;
      }
      #togglePass, #toggleCNPass, #toggleOldPass, #toggleNewPass, #toggleConfPass {
        text-align: center;
        margin: 0;
        position: absolute;
        right: 15px;
        top: 45%;
        -ms-transform: translateY(-50%);
        transform: translateY(-50%);
      }
      .inputCont {
        margin: auto;
      }
      #toggleCNPass, #toggleOldPass, #toggleNewPass, #toggleConfPass {
        right: 25px;
      }
    </style>
</head>
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
       <a href='logs.php'>
         <i class='bx bx-receipt' ></i>
         <span class='links_name'>Logs</span>
       </a>
       <span class='tooltip'>Logs</span>
     </li>
     <li>
       <a href="./accountSettings.php" class="active">
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
      <div class="text">Account Settings</div>
      <div class="cont container-fluid">
        <h2>Profile</h2>
        <form action="post">
            <label class="font-weight-bold" for="userN">USERNAME</label>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                <span class="input-group-text">Username</span>
                </div>
                <input type="text" class="form-control" name="userN" id="userN" value="<?php echo $_COOKIE['user']; ?>" readonly> 
                <button type="button" data-toggle="modal" data-target="#changeUsernameModal" class="confirmBtn btn btn-primary">Change Username</button>
            </div>
            <label class="font-weight-bold" for="userN">PASSWORD</label>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                <span class="input-group-text">Password</span>
                </div>
                <input type="password" class="form-control" name="Pass" id="Pass" value="<?php echo $_COOKIE['secret'] ?>" readonly>
                <i class="bi bi-eye-slash" id="togglePass"></i>
                
            </div>
            <button type="button" data-toggle="modal" data-target="#changePassModal" class="confirmBtn btn btn-primary">Change Password</button>
          

        </form>
      </div>
  </section>

  <div class="modal fade" id="changeUsernameModal" tabindex="-1" aria-labelledby="modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modal-title">Change Username</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="post">
          
            <div class="form-group row">
              <label for="changeName" class="col-sm-2 col-form-label">Username</label>
              <div class="col-sm-10">
                <input class="form-control" name="changeName" id="changeName" type="text" value="<?php echo $_COOKIE['user']; ?>" required>
              </div>
            </div>
            <div class="form-group row">
              <label for="" class="col-sm-12 col-form-label">ENTER PASSWORD TO CONFIRM CHANGES</label>
            </div>
            <div class="form-group row">
              <label for="pass" class="col-sm-2 col-form-label">Password</label>
              <div class="inputCont col-sm-10">
                <input class="form-control" name="CNpass" id="CNpass" type="password" placeholder="" required>
                <i class="col-sm-1 bi bi-eye-slash" id="toggleCNPass"></i>
              </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <input type="submit" class="btn btn-primary" name="saveProvData" value="Save Changes"></input>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- change pass -->
  <div class="modal fade" id="changePassModal" tabindex="-1" aria-labelledby="modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modal-title">Change Password</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="post">
          
            <div class="form-group row">
              <label for="oldPass" class="col-sm-2 col-form-label">Password</label>
              <div class="inputCont col-sm-9">
                <input class="form-control" name="oldPass" id="oldPass" type="password" placeholder="old password" required>
                <i class="bi bi-eye-slash" id="toggleOldPass"></i>
              </div>
            </div> 
            <div class="form-group row">
              <label for="newPass" class="col-sm-2 col-form-label">new Password</label>
              <div class="inputCont col-sm-9">
                <input class="form-control" name="newPass" id="newPass" type="password" placeholder="new password" required>
                <i class="bi bi-eye-slash" id="toggleNewPass"></i>
              </div>
            </div>
            
            <div class="form-group row">
              <label for="confirmPass" class="col-sm-2 col-form-label">Confirm Password</label>
              <div class="inputCont col-sm-9">
                <input class="form-control" name="confirmPass" id="confirmPass" type="password" placeholder="confirm password" required>
                <i class="bi bi-eye-slash" id="toggleConfPass"></i>
              </div>
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


  <script>
  let sidebar = document.querySelector(".sidebar");
  let closeBtn = document.querySelector("#btn");

  closeBtn.addEventListener("click", ()=>{
    sidebar.classList.toggle("open");
    menuBtnChange();
  });


  const togglePass = document.querySelector('#togglePass');
    const Password = document.querySelector('#Pass');

    togglePass.addEventListener('click', function (e) {
      // toggle the type attribute
      const type = Password.getAttribute('type') === 'password' ? 'text' : 'password';
      Password.setAttribute('type', type);
      // toggle the eye / eye slash icon
      this.classList.toggle('bi-eye');
          
        
    });

    const toggleCNPass = document.querySelector('#toggleCNPass');
    const CNPassword = document.querySelector('#CNpass');

    toggleCNPass.addEventListener('click', function (e) {
      // toggle the type attribute
      const type = CNPassword.getAttribute('type') === 'password' ? 'text' : 'password';
      CNPassword.setAttribute('type', type);
      // toggle the eye / eye slash icon
      this.classList.toggle('bi-eye');
          
        
    });

    const toggleOldPass = document.querySelector('#toggleOldPass');
    const oldPassword = document.querySelector('#oldPass');

    toggleOldPass.addEventListener('click', function (e) {
      // toggle the type attribute
      const type = oldPassword.getAttribute('type') === 'password' ? 'text' : 'password';
      oldPassword.setAttribute('type', type);
      // toggle the eye / eye slash icon
      this.classList.toggle('bi-eye');
          
        
    });

    const toggleNewPass = document.querySelector('#toggleNewPass');
    const newPassword = document.querySelector('#newPass');

    toggleNewPass.addEventListener('click', function (e) {
      // toggle the type attribute
      const type = newPassword.getAttribute('type') === 'password' ? 'text' : 'password';
      newPassword.setAttribute('type', type);
      // toggle the eye / eye slash icon
      this.classList.toggle('bi-eye');
          
        
    });

    const toggleconfPass = document.querySelector('#toggleConfPass');
    const confPassword = document.querySelector('#confirmPass');

    toggleconfPass.addEventListener('click', function (e) {
      // toggle the type attribute
      const type = confPassword.getAttribute('type') === 'password' ? 'text' : 'password';
      confPassword.setAttribute('type', type);
      // toggle the eye / eye slash icon
      this.classList.toggle('bi-eye');
          
        
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

