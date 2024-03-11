<?php



include './session/session.php';
require '../class/Firestore.php';
include '../class/Logs.php';
include '../class/Analytics.php';

$analytics = new Analytics();
$logs = new Logs();
$Firestore = new Firestore();

if(isset($_POST['saveProvData'])) {
  if($_POST['providerName'] != null)
  {
    $uID = "CP" . uniqid();
    $remarks = "Provider with the name of " . $_POST['providerName'] . " is added";
    $Firestore->insertProvider("Reviewers", $_POST['providerName'], $uID);
    $logs->insertLog("ADD", "Provider", $remarks, $_COOKIE['user']);
    $analytics->insertData("PROVIDER", $uID, "", "");
  }
  header("Location: reviewers.php");
}
if(isset($_POST['saveEditedProvData'])) {
  if($_POST['providerNameEdit'] != null)
  {
    $remarks ="Provider with the name of " . $_POST['providerNameEdit'] . " is edited";
    $path = "Reviewers/" . $_POST['collectionID'];
    $Firestore->updateProvider($_POST['collectionID'], $path, $_POST['providerNameEdit']);
    $logs->insertLog("EDIT", "Provider", $remarks, $_COOKIE['user']);
  }
  header("Location: reviewers.php");
}
if(isset($_POST['saveTopicData']))
{
  $uID = "T" . uniqid();
  $prov = $_POST['topicProvIDAdd'];
  $remarks = "Topic " . $_POST['topicNameAdd'] . " in Provider " . $_POST['provNameAdd'] . " is added";
  $path = "Reviewers/" . $prov . "/Topics";
  if($_POST['topicNameAdd'] != null && $_POST['topicDesc'])
  {
    
    $Firestore->insertTopic($path, $prov, $_POST['provNameAdd'], $_POST['topicNameAdd'], $_POST['topicDesc'], $uID);
    $logs->insertLog("ADD", "Topic", $remarks, $_COOKIE['user']);
    $analytics->insertData("TOPIC", $prov, $uID, "");
  }
  header("Location: reviewers.php");
}
if(isset($_POST['saveEditedTopicData']))
{
  $prov = $_POST['provIDEdit'];
  $path = "Reviewers/" . $prov . "/Topics/" . $_POST['topicIDEdit'];
  $remarks = "Topic " . $_POST['topicNameEdit'] . " in Provider " . $_POST['provNameEdit'] . " is edited";
  if($_POST['topicNameEdit'] != null && $_POST['topicDescEdit'] != null)
  {
    $Firestore->updateTopic($path, $_POST['topicNameEdit'], $_POST['topicDescEdit'], $_POST['topicIDEdit'], $_POST['provNameEdit'], $_POST['provIDEdit']);
    $logs->insertLog("EDIT", "Topic", $remarks, $_COOKIE['user']);
  }
  header("Location: reviewers.php");
}

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
      .certProvContainer {
        background-color: #E8D5C4;
        Padding: 10px;
        Margin-bottom: 10px;
        border-radius: 100px;
        display: flex;
        flex-direction: row;
        min-width: 700px;
        flex-wrap: nowrap;
      }
      .certProvContainerBtn {
        float: right;
        min-width: 145px;
      }
      .certProvContainerText {
        float: left;
        min-width: 100px;
        max-width: 100px;
        Padding: 5px;
      }
      .certProviders {
        flex: 1;
        border-radius: 100px;
        Padding: 5px;
        background-color: white;
        margin-left: 5px;

      }
      .providersCont {
        margin-top: 80px;
      }
      .topicsCont {
        margin-top: 80px;
      }
      .home-section {
        padding-bottom: 80px;
        padding-left: 80px;
        padding-right: 80px;
        display: table;
      }
      .addTopicBtn, .addProvBtn {
        float: right;
        margin-bottom: 10px;
      }
      h2 {
        float: left;
      }
      .lds-ring {
        display: flex;
        position: relative;
        width: 80px;
        height: 80px;
        margin: 0 auto;
      }
      .lds-ring div {
        box-sizing: border-box;
        display: block;
        position: absolute;
        width: 64px;
        height: 64px;
        margin: 8px;
        border: 8px solid #f60;
        border-radius: 50%;
        animation: lds-ring 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
        border-color: #f60 transparent transparent transparent;
      }
      .lds-ring div:nth-child(1) {
        animation-delay: -0.45s;
      }
      .lds-ring div:nth-child(2) {
        animation-delay: -0.3s;
      }
      .lds-ring div:nth-child(3) {
        animation-delay: -0.15s;
      }
      @keyframes lds-ring {
        0% {
          transform: rotate(0deg);
        }
        100% {
          transform: rotate(360deg);
        }
      }

      .fileError {
        color: red;
      }
      
      table, th, td {
        border: 1px solid gray;
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
       <a href="#" class="active">
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
       <a href="./accountSettings.php">
         <i class='bx bx-cog' ></i>
         <span class="links_name">Account Settings</span>
       </a>
       <span class="tooltip">Account Settings</span>
     </li>
     <li class="profile">
         <div class="profile-details">
           <div class="name_job unselectable">
            <input type="text" id="user" value="<?php echo $_COOKIE['user']; ?>" readonly hidden>
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
      <div class="text">Reviewers</div>
      
      <div class="providersCont container-fluid">
        <h2>Providers</h2>
        <button type="button" class="addProvBtn btn btn-primary" data-toggle="modal" data-target="#addProviderModal"><i class='bx bx-plus'></i>Add Provider</button>

        <table class="providerTable table table-striped container-fluid" >
        </table>
        <div class="lds-ring" id="loadingProv"><div></div><div></div><div></div><div></div></div>
      </div>

      <div class="topicsCont container-fluid" id="topicsCont" hidden>
        <h2>Topics</h2>
        <button type="button" id="addTopic" class="addTopicBtn btn btn-primary" data-toggle="modal" data-target="#addTopicModal"><i class='bx bx-plus'></i>Add Topic</button>
        <table class="topicsTable table  table-striped container-fluid" >

        </table>
        <div class="lds-ring" id="loadingTopics" hidden><div></div><div></div><div></div><div></div></div>
      </div>
      


  </section>
  
  <div class="modal fade" id="addProviderModal" tabindex="-1" aria-labelledby="modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modal-title">Add Provider</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="post">
          
            <div class="form-group row">
              <label for="providerName" class="col-sm-2 col-form-label">Provider Title</label>
              <div class="col-sm-10">
                <input class="form-control" name="providerName" id="providerName" type="text" placeholder="i.e., Microsoft, CompTIA" required>
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


  <div class="modal fade" id="editProviderModal" tabindex="-1" aria-labelledby="modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modal-title">Edit Provider</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="post">
            <div class="form-group row">
              <label for="providerNameEdit" class="col-sm-2 col-form-label">Provider Title</label>
              <div class="col-sm-10"><!--dummy data-->
                <input class="form-control" name="providerNameEdit" id="providerNameEdit" type="text" required>
              </div>
            </div>
            <div class="form-group row">
              <label for="collectionID" class="col-sm-2 col-form-label">Collection ID</label>
              <div class="col-sm-10"><!--dummy data-->
                <input class="form-control" name="collectionID" id="collectionID" type="text" readonly>
              </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <input type="submit" class="btn btn-primary" name="saveEditedProvData" value="Save Changes"></input>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- modal add topic-->
  <div class="modal fade" id="addTopicModal" tabindex="-1" aria-labelledby="modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modal-title">Add Topic</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          
          <form method="POST" >
          <div class="form-group row">
              <label for="topicProvIDAdd" class="col-sm-2 col-form-label">CollectionID</label>
              <div class="col-sm-10">
                <input class="form-control" name="topicProvIDAdd" id="topicProvIDAdd" type="text" readonly>
              </div>
            </div>
            <div class="form-group row">
              <label for="topicNameAdd" class="col-sm-2 col-form-label">Topic Title</label>
              <div class="col-sm-10">
                <input class="form-control" name="topicNameAdd" id="topicNameAdd" type="text" placeholder="i.e., Database Fundamentals, IT Fundamentals" required>
              </div>
            </div>
            <div class="form-group row">
              <label for="provNameAdd" class="col-sm-2 col-form-label">Provider</label>
              <div class="col-sm-10">
                <input class="form-control" name="provNameAdd" id="provNameAdd" type="text" readonly>
              </div>
            </div>
            <div class="form-group row">
              <label for="topicDesc" class="col-sm-2 col-form-label">Description</label>
              <div class=" col-sm-10">
                <textarea class="form-control" name="topicDesc" id="topicDesc" aria-label="With textarea"></textarea>
              </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <input type="submit" class="btn btn-primary" name="saveTopicData" value="Save Changes"></input>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- modal edit topic-->
  <div class="modal fade" id="editTopicModal" tabindex="-1" aria-labelledby="modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modal-title">Edit Topic</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="post">
          <div class="form-group row" hidden>
              <label for="topicIDEdit" class="col-sm-2 col-form-label">ID</label>
              <div class="col-sm-10">
                <input class="form-control" name="topicIDEdit" id="topicIDEdit" type="text" readonly>
              </div>
            </div>
            <div class="form-group row">
              <label for="topicNameEdit" class="col-sm-2 col-form-label">Topic Title</label>
              <div class="col-sm-10">
                <input class="form-control" name="topicNameEdit" id="topicNameEdit" type="text" required>
              </div>
            </div>
            <div class="form-group row">
              <label for="provNameEdit" class="col-sm-2 col-form-label">Provider</label>
              <div class="col-sm-10">
                <input class="form-control" name="provNameEdit" id="provNameEdit" type="text" readonly>
              </div>
            </div>
            <div class="form-group row">
              <label for="provIDEdit" class="col-sm-2 col-form-label">Provider ID</label>
              <div class="col-sm-10">
                <input class="form-control" name="provIDEdit" id="provIDEdit" type="text" readonly>
              </div>
            </div>
            <div class="form-group row">
              <label for="topicDescEdit" class="col-sm-2 col-form-label">Description</label>
              <div class="col-sm-10">
                <textarea class="form-control" name="topicDescEdit" id="topicDescEdit" aria-label="With textarea"></textarea>
              </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <input type="submit" class="btn btn-primary" name="saveEditedTopicData" value="Save Changes"></input>
          </form>
        </div>
      </div>
    </div>
  </div>
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <script type="module">
  import FireStoreParser from '../node_modules/firestore-parser/index.js';
  import * as Firestore from '../node_modules/firestore-parser/firebase.js';

  const providers = [];
  var url = 'https://firestore.googleapis.com/v1/projects/'+Firestore.projectID+'/databases/(default)/documents/Reviewers';
  var prov;
  var obj;


    
      var count = 1;
      var response = await fetch(url)
      .then(response => response.json())
      .then(json => FireStoreParser(json));
      obj = await response;
      obj['documents'].forEach(element => {
        prov = {
              ID: count,
              collectionID: element['fields']['collectionID'],
              Name: element['fields']['certProvider'],
              Date_Created: new Date( element['createTime']),
              Load: "load",
              Actions: "actions"
          }
          providers.push(prov);
          count++;
      });
      
      
      

  function viewPDF(topicName)
  {

    let fileName = ''.concat(topicName, ".pdf");
    
    
    let formatedFileName = encodeURIComponent(fileName);
    let fileUrl = "https://firebasestorage.googleapis.com/v0/b/citerapp-1e2e7.appspot.com/o/ReviewerFile%2F"+formatedFileName+"?alt=media";
    

    window.open(fileUrl, "_blank");

  }



  function generateTableHead(table, data) {
    let thead = table.createTHead();
    thead.className = "thead-dark";
    let row = thead.insertRow();
    for(let key of data) {
      let th = document.createElement("th");
      let text = document.createTextNode(key);
      
      if (key == "Description") {
        th.className = "td-truncate";
        
      }
      th.appendChild(text);

      row.appendChild(th);

    }
  }
  var colID;
  async function generateTable(table, data) {
    let tb = table;
    for(let element of data) {
      let row = table.insertRow();
      
      for (let key in element) {
        let cell = row.insertCell();
        let text = document.createTextNode(element[key]);
        
        if(element[key] == "load") {
          let loadBtn = document.createElement("input");
          loadBtn.type = "button";
          loadBtn.className = " btn btn-info";
          loadBtn.addEventListener('click', function loadData(event){
            let provname = document.querySelector('#provNameAdd')
            document.getElementById('topicProvIDAdd').value = element['collectionID']
            provname.value = element['Name'];
            colID = element['collectionID']
            
            document.getElementById('topicsCont').hidden = false;
            
            let loading = document.getElementById("loadingTopics");
            loading.style.visibility = 'visible';
            
            getTopics(element['collectionID']);

          });
          loadBtn.value = "Load Topics";
          cell.appendChild(loadBtn);
        }
        else if (element[key] == "view")
        {
          let viewModuleBtn = document.createElement("input");
          viewModuleBtn.type = "button";
          viewModuleBtn.className = "btn btn-primary";

          viewModuleBtn.addEventListener('click', function(event){
            viewPDF(element['Name']);

          });

          viewModuleBtn.value = "View";
          cell.appendChild(viewModuleBtn);
        }
        else if (element[key] == "actions") {
          let editBtn = document.createElement("input");
          editBtn.type = "button";
          editBtn.className = "btn btn-primary";
          //
          editBtn.addEventListener('click', function(event) {
            
            
              
            if(element['Provider']==null)
            {
              $("#editProviderModal").modal("show");
              var providerInput = document.getElementById("providerNameEdit");
              var providerID = document.getElementById("collectionID");
              providerInput.value = element['Name'];
              providerID.value = element['collectionID'];
            }
            else 
            {
              $("#editTopicModal").modal("show");
              
              var tpTitle = document.getElementById("topicNameEdit");
              var pvName = document.getElementById("provNameEdit");
              var pvID = document.getElementById("provIDEdit");
              var tpDesc = document.getElementById("topicDescEdit");
              var tpID = document.getElementById("topicIDEdit");
              tpTitle.value = element['Name'];
              pvName.value = element['Provider'];
              pvID.value = element['ProviderID'];
              tpDesc.value = element['Description'];
              tpID.value = element['ID'];
              console.log(element['ID']);

            }
            

          });
          editBtn.value = "Edit";
          cell.appendChild(editBtn);
          let deleteBtn = document.createElement("input");
          deleteBtn.type = "button";
          deleteBtn.className = "btn btn-danger";
          
          deleteBtn.addEventListener('click', function deleteData(event){ 
            
            if(element['Provider']==null)
            {
              
                Swal.fire({
                  title: 'Are you sure?',
                  text: "You won't be able to revert this! the topics and questionnaires related to this Certificate Provider will be permanently deleted are you sure you want to continue this action?",
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                  if (result.isConfirmed) {
                    fetch(url + '/' + element['collectionID'] + "?currentDocument.exists=true", {
                      method: 'delete'
                    })
                    .then(response => response.json())
                    .then(response => {
                      
                      Swal.fire(
                      'Deleted!',
                      'Your file has been deleted.',
                      'success'
                      ).then(function(){
                      var user = document.getElementById("user").value;
                      var httpr = new XMLHttpRequest();
                      httpr.open("POST", "del-logs.php", true);
                      httpr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
                      httpr.send("delProv=" + element['Name'] + "&user=" + user + "&providerID=" + element['collectionID']);
                      window.location = "reviewers.php";
                    })
                    });
                  }
                });
            }
            else
            {

              Swal.fire({
                  title: 'Are you sure?',
                  text: "You won't be able to revert this! the questionnaires related to this Topic will be permanently deleted are you sure you want to continue this action?",
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                  if (result.isConfirmed) {
                    console.log(url + '/' + colID + "/Topics/" + element['ID'] + "?currentDocument.exists=true");
                    fetch(url + '/' + colID + "/Topics/" + element['ID'] + "?currentDocument.exists=true", {
                      method: 'delete'
                    })
                    .then(response => response.json())
                    .then(response => {
                      Swal.fire(
                      'Deleted!',
                      'Your file has been deleted.',
                      'success'
                      ).then(function(){

                      var user = document.getElementById("user").value;
                      var httpr = new XMLHttpRequest();
                      httpr.open("POST", "del-logs.php", true);
                      httpr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
                      httpr.send("delTopic=" + element['Name'] + "&user=" + user + "&topicID=" + element['ID']);
                      window.location = "reviewers.php";
                    })
                    });
                  }
                });

            }
          });
          deleteBtn.value = "Delete";
          let form = document.createElement("form");
          cell.appendChild(deleteBtn);
        }
        else {
          if (element[key] == null) {

          } else {
            if(element[key].length > 200) {
              text = document.createTextNode(element[key].substr(0, 200)+"...");
            }
          }
           cell.appendChild(text);
        }
      }
    }
    const loading = document.getElementById("loadingProv");
    if(loading) {
      loading.remove()
    } else { 

    }
  }
 
  let table = document.querySelector(".providerTable");
  let data = Object.keys(providers[0]);
  generateTableHead(table, data);
  generateTable(table, providers);

  

async function getTopics(provider){
    let topicTable = document.querySelector(".topicsTable");
      while(topicTable.rows.length > 0) {
        topicTable.deleteRow(0);
    }
    const topics = [];
    var url = 'https://firestore.googleapis.com/v1/projects/'+Firestore.projectID+'/databases/(default)/documents/Reviewers/'+provider+'/Topics';
    var topic;
    var obj;
    var response = await fetch(url)
      .then(response => response.json())
      .then(json => FireStoreParser(json));
      obj = await response;
      obj['documents'].forEach(element => {
          topic = {
              ID: element['fields']['ID'],
              Name: element['fields']['title'],
              Provider: element['fields']['Provider'],
              ProviderID: element['fields']['ProviderID'],
              Description: element['fields']['Description'],
              Date_Created: new Date( element['createTime']),
              Date_Updated: new Date( element['updateTime']),
              Module: "view",
              Actions: "actions"

          }
          topics.push(topic);
      });

      
      
      let data = Object.keys(topics[0]);
      generateTableHead(topicTable, data);
      generateTable(topicTable, topics);
      const loading = document.getElementById("loadingTopics");
      if(loading) {
        loading.style.visibility = 'hidden';
      } else {
        
      }
  }
  

  let remFile = document.querySelector('#removeFile');

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

