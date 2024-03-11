<?php
session_start();

date_default_timezone_set('Asia/Manila');

include 'session/session.php';
include '../class/Analytics.php';
include '../class/Logs.php';

$analytics = new Analytics();
$logs = new Logs();
$Providers = $analytics->countData("PROVIDER");
$Topics = $analytics->countData("TOPIC");
$Questions = $analytics->countData("QUESTION");
$userActChartData = $logs->userActivities($_COOKIE['user']);
$topChartData = $logs->filterLogs("Topic");
$questChartData = $logs->filterLogs("Question");


$userActArray = [];
if(is_countable($userActChartData)){
    for($i = 0; $i < count($userActChartData); $i++){
        array_push($userActArray, date("m/d/Y", strtotime($userActChartData[$i]['date'])));
    }
}

$topicArray = [];
if(is_countable($topChartData)){
    for($i = 0; $i < count($topChartData); $i++){
        array_push($topicArray, date("m/d/Y", strtotime($topChartData[$i]['date'])));
    }
}

$questArray = [];
if(is_countable($questChartData)){
    for($i = 0; $i < count($questChartData); $i++){
        array_push($questArray, date("m/d/Y", strtotime($questChartData[$i]['date'])));
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>CITERWEB</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='./css/bootstrap.min.css' rel='stylesheet' type="text/css">
    <link href="./css/styles.css" rel="stylesheet" type="text/css" />
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet' type="text/css">
    <style>
      .dashboardCont{
        padding: 50px;
        display: flex;
        flex-direction: row;
        flex-flow: wrap;
      }
      @media (max-width: 600px) {
        .dashboardCont {
          flex-direction: column;
        }
      }
    </style>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
</head>
<body>
  <div class="sidebar">
    <div class="logo-details">
        <div class="logo_name unselectable">CITER APP Content Management</div>
        <i class='bx bx-menu' id="btn" ></i>
    </div>
    <ul class="nav-list">
      <li>
        <a href="#" class="active">
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
       <a href="accountSettings.php">
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
    <div class="text">Dashboard</div>
    <div class="dashboardCont container-md">
    <div class="row">
      <div class="card" style="width: 18em; margin: 10px;">
        <div class="card-body">
          <h5 class="card-title">Certificate Providers</h5>
          <h1 class="card-text"><?php echo $Providers; ?></h1>
        </div>
      </div>
      <div class="card" style="width: 18em; margin: 10px;">
        <div class="card-body">
          <h5 class="card-title">Topics</h5>
          <h1 class="card-text"><?php echo $Topics; ?></h1>
        </div>
      </div>
      <div class="card" style="width: 18em; margin: 10px;">
        <div class="card-body">
          <h5 class="card-title">Questionnaires</h5>
          <h1 class="card-text"><?php echo $Questions; ?></h1>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="card" style="width: 500px; margin: 10px;">
        <div class="card-body">
          <h5 class="card-title">Topic Added</h5>
          <canvas id="myChart" style="width:100%;max-width:600px;"></canvas>
        </div>
      </div>
      <div class="card" style="width: 500px; margin: 10px;">
        <div class="card-body">
          <h5 class="card-title">Questionnaire Added</h5>
          <canvas id="myChart1" style="width:100%;max-width:600px;"></canvas>
        </div>
      </div>
      <div class="card" style="width: 500px; margin: 10px;">
        <div class="card-body">
          <div class="col col-xs">
            <h5 class="card-title">User Activity</h5>
          </div>
          <canvas id="myChart2" style="width:100%;max-width:600px;"></canvas>
        </div>
      </div>
      
      
    </div>


  </section>
  
  <script>

const today = new Date();

Date.prototype.addDays = function(days) {
    var date = new Date(this.valueOf());
    date.setDate(date.getDate() - days);
    return date;
}




const xValues = [];
const yValues = [];

const yValues1 = [];

const yValues2 = [];

for(var i = 0; i < 7; i++){
  xValues.push(today.addDays(i).toLocaleDateString());
}

var topicArr =
<?php
  echo json_encode($topicArray);
?>;

for(var i = 0; i < xValues.length; i++)
{
  var count = 0;
  for(var j = 0 ; j < topicArr.length ; j++)
  {
    if(new Date(xValues[i]).getTime() === new Date(topicArr[j]).getTime())
    {
      count++;
    }
  }
  yValues.push(count);
  
}

var questArr = 
<?php
  echo json_encode($questArray);
?>;

for(var i = 0; i < xValues.length; i++)
{
  var count = 0;
  for(var j = 0 ; j < questArr.length ; j++)
  {
    if(new Date(xValues[i]).getTime() === new Date(questArr[j]).getTime())
    {
      count++;
    }
  }
  yValues1.push(count);
  
}

var userActArr =
<?php
  echo json_encode($userActArray);
?>;

for(var i = 0; i < xValues.length; i++)
{
  var count = 0;
  for(var j = 0 ; j < userActArr.length ; j++)
  {
    if(new Date(xValues[i]).getTime() === new Date(userActArr[j]).getTime())
    {
      count++;
    }
  }
  yValues2.push(count);
  
}


new Chart("myChart", {
  type: "line",
  data: {
    labels: xValues.reverse(),
    datasets: [{
      fill: false,
      lineTension: 0,
      backgroundColor: "rgba(0,0,255,1.0)",
      borderColor: "rgba(0,0,255,0.1)",
      data: yValues.reverse()
    }]
  },
  options: {
    legend: {display: false},
    scales: {
      yAxes: [{ticks: {min: 0, max: (Math.max(...yValues)==0)? 10 : Math.max(...yValues) + (Math.max(...yValues)/2)}}],
    }
  }
});
new Chart("myChart1", {
  type: "line",
  data: {
    labels: xValues,
    datasets: [{
      fill: false,
      lineTension: 0,
      backgroundColor: "rgba(0,0,255,1.0)",
      borderColor: "rgba(0,0,255,0.1)",
      data: yValues1.reverse()
    }]
  },
  options: {
    legend: {display: false},
    scales: {
      yAxes: [{ticks: {min: 0, max: (Math.max(...yValues1)==0)? 10 : Math.max(...yValues1) + (Math.max(...yValues1)/2)}}],
    }
  }
});

new Chart("myChart2", {
  type: "line",
  data: {
    labels: xValues,
    datasets: [{
      fill: false,
      lineTension: 0,
      backgroundColor: "rgba(0,0,255,1.0)",
      borderColor: "rgba(0,0,255,0.1)",
      data: yValues2.reverse()
    }]
  },
  options: {
    legend: {display: false},
    scales: {
      yAxes: [{ticks: {min: 0, max: (Math.max(...yValues2)==0)? 10 : Math.max(...yValues2) + (Math.max(...yValues2)/2)}}],
    }
  }
});


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

