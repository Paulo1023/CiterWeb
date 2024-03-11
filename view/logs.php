<?php

include './session/session.php';
include '../class/Logs.php';
 

$logs = new Logs();
if (isset($_GET['pageno'])) {
  $pageno = $_GET['pageno'];
} else {
  $pageno = 1;
}
$logsRow = $logs->countLogs();

$no_of_records_per_page = 10;
$offset = ($pageno-1) * $no_of_records_per_page;
$total_pages = ceil($logsRow / $no_of_records_per_page); 

$listLogs = $logs->getLogs($offset, $no_of_records_per_page);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>CITERWEB</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='./css/bootstrap.min.css' rel='stylesheet' type="text/css">
    <link href="css/styles.css" rel="stylesheet" type="text/css" />
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet' type="text/css">
    <style>
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
      .selectRow {
        display: flex;
        padding: 5px;
      }
      .row {
        justify-content: space-between;
        margin-left: 15px;
        margin-right: 15px;
      }
      h6 {
        margin-right: 5px;
        padding: 5px;
        
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
       <a href="#" class="active">
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
        <div class="text">Logs</div>
      <div class="TopicsCont container-fluid">
      <div class="row">
        <h2>Activity Logs</h2>
        
      </div>
      
      <table class="table ">
      <thead class="thead-dark">
        <tr>
          <th scope="col">ID</th>
          <th scope="col">Operation</th>
          <th scope="col">Segment</th>
          <th scope="col">Remarks</th>
          <th scope="col">Date</th>
          <th scope="col">Account</th>
        </tr>
      </thead>
      <tbody>
        <?php
            if(is_countable($listLogs)){
            for($i=0; $i < count($listLogs); $i++)
            {
        ?>
          <tr>
                  <th><?php echo $listLogs[$i]['id']; ?></th>
                  <td><?php echo $listLogs[$i]['operation']; ?></td>
                  <td><?php echo $listLogs[$i]['segment']; ?></td>
                  <td><?php echo $listLogs[$i]['remarks']; ?></td>
                  <td><?php echo $listLogs[$i]['date'];  ?></td>
                  <td><?php echo $listLogs[$i]['user_name'];  ?></td>
          </tr>
        <?php 
            }}
        ?>
      </tbody>
    </table>
      <div>
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
    </div>
  </section>
  <script>
  let sidebar = document.querySelector(".sidebar");
  let closeBtn = document.querySelector("#btn");

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

