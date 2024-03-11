<?php

include './session/session.php';
require '../class/Firestore.php';
include '../class/Logs.php';
include '../class/Analytics.php';

$analytics = new Analytics();
$logs = new Logs();
$Firestore = new Firestore();

if(isset($_POST['saveQuestionData']))
{
  $uID = "Q" . uniqid();
  $prov = $_POST['questionProvIDAdd'];
  $topic = $_POST['questionTopicAdd'];
  $tID = $_POST['topicIDAdd'];
  
  $sect = $_POST['sectionAdd'];
  $question = $_POST['questionAdd'];
  $qImg = $_POST['questionImageAdd'];
  $answer = $_POST['CorAnswer'];
  
  $choices = array($_POST['choice1'], $_POST['choice2'], $_POST['choice3'], $_POST['choice4']);
  
 try
 {
  if(isset($_POST['field_name']))
  {
    for($i = 0; $i < count($_POST['field_name']); $i++)
    {
      array_push($choices, $_POST['field_name'][$i]);
    }
  }
  $path = "Reviewers/" . $prov . "/Topics/" . $tID . "/Quiz";
  
  $Firestore->insertQuestion($path, $topic, $sect, $question, $qImg, $choices, $answer, $uID);
  $remarks = "Question in Topic " . $_POST['questionTopicAdd'] . " is added" ;
  $logs->insertLog("ADD", "Question", $remarks, $_COOKIE['user']);
  $analytics->insertData("QUESTION", $prov, $tID, $uID);
  header("Location: questionnaires.php");
 }
 catch (error)
 {

 }
 

}

if(isset($_POST['saveEditedQuestionData']))
{
  $qID = $_POST['questionIDEdit'];
  $prov = $_POST['questionProvIDEdit'];
  $topic = $_POST['questionTopicEdit'];
  $tID = $_POST['topicIDEdit'];
  $sect = $_POST['sectionEdit'];
  $question = $_POST['questionEdit'];
  $qImg = $_POST['questionImgEdit'];
  $answer = $_POST['CorAnswerEdit'];
  $choices = array();
  
  if(isset($_POST['choices_name']))
  {

    for($i = 0; $i < count($_POST['choices_name']); $i++)
    {
      array_push($choices, $_POST['choices_name'][$i]);
    }
  }
  if(isset($_POST['field_choices']))
  {
    for($i = 0; $i < count($_POST['field_choices']); $i++)
    {
      array_push($choices, $_POST['field_choices'][$i]);
      
    }
  }
  
  $path = "Reviewers/" . $prov . "/Topics/" . $tID . "/Quiz/" . $qID;
  $Firestore->updateQuestion($path, $qID, $topic, $sect, $question, $qImg, $choices, $answer);
  $remarks = "Question in Topic " . $topic . " is edited" ;
  $logs->insertLog("EDIT", "Question", $remarks, $_COOKIE['user']);
  header("Location: questionnaires.php");
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
        html, body {
            scroll-behavior: smooth;
            display: grid;
            grid-template-columns: auto 0px; 
            }

      .Topics {
        flex: 1;
        border-radius: 100px;
        Padding: 5px;
        background-color: white;
        margin-left: 5px;
        
      }
      .TopicsCont {
        margin-top: 80px;
      }
      .QuestionsCont {
        margin-top: 80px;
      }
      h2 {
        float: left;
      }
      table, th, td {
        border: 1px solid gray;
      }
      .home-section {
        padding-bottom: 80px;
      }
      .addTopicBtn {
        float: right;
      }
      .home-section {
        padding-bottom: 80px;
        padding-left: 80px;
        padding-right: 80px;
        display: table;
      }
      #myBtn {
        display: none;
        position: fixed;
        bottom: 20px;
        right: 30px;
        z-index: 99;
        font-size: 18px;
        border: none;
        outline: none;
        background-color: red;
        color: white;
        cursor: pointer;
        padding: 15px;
        border-radius: 4px;
        }

        #myBtn:hover {
        background-color: #555;
        }
    </style>
</head>
<body>

  <button id="myBtn" title="Go to top"><i class='bx bx-chevrons-up' ></i> Top</button>
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
       <a href="#" class="active">
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
      <div class="text">Questionnaires</div>
      <div class="TopicsCont container-fluid">
        <div class="row" style="padding-bottom: 50px;">
          <h2>Filter Questionnaires</h2>
        </div>
        
      <div class="container-md">
        <div class="row">
          <h5 class="col-sm-4">Provider</h5>
          <h5 class="col-sm-4">Topic</h5>
        </div>
        <div class="filter row">
          <select class="col-sm-4 form-select" id="selectProv" aria-label="Default select example">
            <option selected>Choose a Certificate Provider</option>
          </select>
          <select class="col-sm-4 form-select" id="selectTopic" aria-label="Default select example">
            <option value="default" selected>Choose a Topic</option>
          </select>
          <button class="btn btn-primary col-sm-4" id="queryBtn">QUERY</button>
        </div>
      </div>
        
        
      
    </div>
    <div class="QuestionsCont container-fluid" id="QuestionsCont" hidden>
      <h2>Questions</h2>
      <input type="button" class="addTopicBtn btn btn-primary" value="Add Question" data-toggle="modal" data-target="#AddQuestionModal"></input>
    
    <table class="tableQuestionnaires table" >
      
    </table>
    </div>
    
  </section>

  
<div class="modal fade" id="AddQuestionModal" tabindex="-1" aria-labelledby="modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modal-title">Add Question</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="post" action="" name="questForm">
          <div class="form-group row" hidden>
            <label for="questionIDAdd" class="col-sm-2 col-form-label">ID</label>
            <div class="col-sm-10">
              <input class="form-control" name="questionIDAdd" id="questionIDAdd" type="text"  readonly >
            </div>
          </div>
          <div class="form-group row" hidden>
            <label for="questionProvIDAdd" class="col-sm-2 col-form-label">Provider ID</label>
            <div class="col-sm-10">
              <input class="form-control" name="questionProvIDAdd" id="questionProvIDAdd" type="text"  readonly >
            </div>
          </div>
          <div class="form-group row" hidden>
            <label for="topicIDAdd" class="col-sm-2 col-form-label">Topic ID</label>
            <div class="col-sm-10">
              <input class="form-control" name="topicIDAdd" id="topicIDAdd" type="text"  readonly >
            </div>
          </div>
          <div class="form-group row">
              <label for="questionTopicAdd" class="col-sm-2 col-form-label">Topic</label>
              <div class="col-sm-10">
                <input class="form-control" name="questionTopicAdd" id="questionTopicAdd" type="text"  readonly>
              </div>
            </div>
            <div class="form-group row">
              <label for="sectionAdd" class="col-sm-2 col-form-label" >Section</label>
              <div class="col-sm-10">
                <input class="form-control" name="sectionAdd" id="sectionAdd" type="text" required>
              </div>
            </div>
            <div class="form-group row">
              <label for="providerNameAdd" class="col-sm-2 col-form-label">Question</label>
              <div class="col-sm-10">
                <input class="form-control" name="questionAdd" id="questionAdd" type="text" required>
              </div>
            </div>
            <div class="form-group row">
              <label for="questionImageAdd" class="col-sm-2 col-form-label">Question Image</label>
              <div class="col-sm-10">
                <input class="form-control" name="questionImageAdd" id="questionImageAdd" type="text">
              </div>
            </div>
            <div class="form-group row" id="choicesCont">
                <div class="row col-sm-12">
                  <label for="choice1" class="col-sm-2 col-form-label">Choices</label>
                  <div class="col-sm-9">
                    <input class="form-control" name="choice1" id="choice1" type="text" required ></input>
                  </div>
                  <input type="button" class="col-sm-1 btn btn-danger" value="" hidden></input>
                </div>
                <div class="row col-sm-12">
                  <label for="choice2" class="col-sm-2 col-form-label"></label>
                  <div class="col-sm-9">
                    <input class="form-control" name="choice2" id="choice2" type="text" required ></input>
                  </div>
                  <input type="button" class="col-sm-1 btn btn-danger" value="" hidden></input>
                </div>
                <div class="row col-sm-12">
                  <label for="choice3" class="col-sm-2 col-form-label"></label>
                  <div class="col-sm-9">
                    <input class="form-control" name="choice3" id="choice3" type="text" required ></input>
                  </div>
                  <input type="button" class="col-sm-1 btn btn-danger" value="" hidden></input>
                </div>
                <div class="row col-sm-12">
                  <label for="choice4" class="col-sm-2 col-form-label"></label>
                  <div class="col-sm-9">
                    <input class="form-control" name="choice4" id="choice4" type="text" required ></input>
                  </div>
                  <input type="button" class="col-sm-1 btn btn-danger" value="" hidden></input>
                </div>
            
            </div>
            <div class="form-group row">
              <label class="col-sm-5"></label>
              <input type="button" class="col-sm-2 btn btn-primary" id="addChoices" value="+">
              <label class="col-sm-5"></label>
            </div>
            <div class="form-group row">
              <label class="col-sm-2 col-form-label" for="CorAnswer">Correct Answer</label>
              <div class="col-sm-10">
                <input class="form-control" name="CorAnswer" id="CorAnswer" type="text" required>
              </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <input type="submit" class="btn btn-primary" name="saveQuestionData" value="Save Changes"></input>
          </form>
        </div>
      </div>
    </div>
  </div>


<!--Modal Edit question-->
  <div class="modal fade" id="editQuestionModal" tabindex="-1" aria-labelledby="modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modal-title">Edit Question</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="post">
            <div class="form-group row" hidden>
            <label for="questionIDEdit" class="col-sm-2 col-form-label">ID</label>
            <div class="col-sm-10">
              <input class="form-control" name="questionIDEdit" id="questionIDEdit" type="text"  readonly >
            </div>
          </div>
          <div class="form-group row" hidden>
            <label for="questionProvIDEdit" class="col-sm-2 col-form-label">Provider ID</label>
            <div class="col-sm-10">
              <input class="form-control" name="questionProvIDEdit" id="questionProvIDEdit" type="text"  readonly >
            </div>
          </div>
          <div class="form-group row">
              <label for="questionTopicEdit" class="col-sm-2 col-form-label">Topic</label>
              <div class="col-sm-10"><!--dummy data-->
                <input class="form-control" name="questionTopicEdit" id="questionTopicEdit" type="text" required readonly>
              </div>
            </div>
            <div class="form-group row" hidden>
              <label for="topicIDEdit" class="col-sm-2 col-form-label">Topic ID</label>
              <div class="col-sm-10"><!--dummy data-->
                <input class="form-control" name="topicIDEdit" id="topicIDEdit" type="text" required readonly>
              </div>
            </div>
            <div class="form-group row">
              <label for="sectionEdit" class="col-sm-2 col-form-label">Section</label>
              <div class="col-sm-10">
                <input class="form-control" name="sectionEdit" id="sectionEdit" type="text" required>
              </div>
            </div>
            <div class="form-group row">
              <label for="questionEdit" class="col-sm-2 col-form-label">Question</label>
              <div class="col-sm-10"><!--dummy data-->
                <input class="form-control" name="questionEdit" id="questionEdit" type="text" required>
              </div>
            </div>
            <div class="form-group row">
              <label for="questionImgEdit" class="col-sm-2 col-form-label">Image</label>
              <div class="col-sm-10"><!--dummy data-->
                <input class="form-control" name="questionImgEdit" id="questionImgEdit" type="text" value="" >
              </div>
            </div>
            <div class="form-group row">
              <label for="" class="col-sm-2 col-form-label">Choices</label>
              <div class=" row col-sm-10" id="editChoices">
                
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-5"></label>
              <input type="button" class="col-sm-2 btn btn-primary" id="addChoicesEdit" value="+">
              <label class="col-sm-5"></label>
            </div>
            
            <div class="form-group row">
              <label class="col-sm-2 col-form-label" for="CorAnswerEdit">Correct Answer</label>
              <div class="col-sm-10">
                <input class="form-control" name="CorAnswerEdit" id="CorAnswerEdit" type="text" required>
              </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <input type="submit" class="btn btn-primary" name="saveEditedQuestionData" value="Save Changes"></input>
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
  


  const topics = [];
  const providers = [];
  const questionnaires = [];
  var url = 'https://firestore.googleapis.com/v1/projects/'+Firestore.projectID+'/databases/(default)/documents/Reviewers';
  var prov;
  var obj;
  var response = await fetch(url)
  .then(response => response.json())
  .then(json => FireStoreParser(json));
  obj = await response;
  obj['documents'].forEach(element => {
    prov = {
          collectionID: element['fields']['collectionID'],
          Name: element['fields']['certProvider']
      }
      providers.push(prov);
  });

  var select = document.getElementById("selectProv");
  var provID;
  for(var i = 0; i < providers.length; i++) {
    var opt = providers[i]['Name'];
    var colID = providers[i]['collectionID'];
    var el = document.createElement("option");
    el.textContent = opt;
    el.value = colID;
    select.appendChild(el);
  }
  
    var selProv;
    let selProvID;
    let selectedID;
    var selTopic;
    var seltopic = document.getElementById("selectTopic");
    var topicSelected;
    seltopic.addEventListener('change', function(){
      
      selTopic = $('#selectTopic').find(":selected").text();
      

    });

    let questTable = document.querySelector(".tableQuestionnaires");
    document.getElementById("queryBtn").onclick = async function(){

      selTopic = $('#selectTopic').find(":selected").text();
      selProv = $('#selectProv').find(":selected").text();
      

      for(var i = 0; i < topics.length; i++)
      {
          if(topics[i]['Name'] == selTopic)
          {
            selectedID = topics[i]['ID'];
            document.getElementById('topicIDAdd').value = selectedID;
          }
          
      }
      for(var i = 0; i < providers.length; i++)
      {
        if(providers[i]['Name'] == selProv)
        {
          selProvID = providers[i]['collectionID']
        }
      }
      
      
      
      if(selTopic != "Choose a Topic")
      {
        document.getElementById("QuestionsCont").hidden = false; 
        document.getElementById("questionTopicAdd").value = selTopic; 
        document.getElementById("questionProvIDAdd").value = selProvID; 

        questionnaires.length = 0;
        if (questTable.rows.length > 0) { 
          while(questTable.rows.length > 0) {
            questTable.deleteRow(0);
        }
        } else {}
        

        await getQuestionnaires(selProvID, selectedID); 
        let table = document.querySelector(".tableQuestionnaires");
        let data = Object.keys(questionnaires[0]);
        generateTableHead(table, data);
        generateTable(table, questionnaires);
      
      }
      

  };
  
   
  select.addEventListener('change', async function() {
    
    await getTopics(select.value);
    
    for(var i = 0; i < topics.length; i++) {
      var opt = topics[i]['Name'];
      var prov1 = topics[i]['ProvID'];
      var id = topics[i]['ID'];
      
      var el = document.createElement("option");
      el.textContent = opt;
      el.value = prov1;
      $('#selectTopic')
      .find('option[value!= '+prov1+']')
      .remove();

      seltopic.appendChild(el);
      
    }
    
      topicSelected = $('#selectTopic option:selected').text();
      
  });


  

async function getTopics(provider){
  
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
              ProvID: element['fields']['ProviderID'],
              Provider: element['fields']['Provider'],
              Name: element['fields']['title']
              
          }
          topics.push(topic);
      });
  }


  async function getQuestionnaires(provider, topic)
  {
    
    
    var url = 'https://firestore.googleapis.com/v1/projects/'+Firestore.projectID+'/databases/(default)/documents/Reviewers/'+provider+'/Topics/'+topic+'/Quiz?pageSize=100';
    
    var questionnaire;
    var obj;
    var response = await fetch(url)
      .then(response => response.json())
      .then(json => FireStoreParser(json));
      obj = await response;
      obj['documents'].forEach(element => {
        
        
        questionnaire = {
          
              ID: element['fields']['ID'],
              Topic: element['fields']['topic'],
              Section: element['fields']['section'],
              Question: element['fields']['question'],
              QuestionImage: element['fields']['qImage'],
              Choices: element['fields']['choices'].join(" | "),
              CorrectAnswer: element['fields']['correctAnswer'],
              Action: "actions"
          }
          questionnaires.push(questionnaire);
      });
      
      console.log(questionnaires.length);
  }


 


  function generateTableHead(table, data) {
    let thead = table.createTHead();
    thead.className = "thead-dark";
    let row = thead.insertRow();
    for(let key of data) {
      let th = document.createElement("th");
      let text = document.createTextNode(key);
      
      th.appendChild(text);
      
      row.appendChild(th);
      
    }
  }

  function generateTable(table, data) {
    for(let element of data) {
      let row = table.insertRow();
      
      for (let key in element) {
        let cell = row.insertCell();
        let text = document.createTextNode(element[key]);
        
        if (element[key] == "actions") {
          let editBtn = document.createElement("input");
          editBtn.type = "button";
          editBtn.className = "btn btn-primary";
          editBtn.addEventListener('click', function (){
            
            $("#editQuestionModal").modal("show");
            
            var arrChoices = element['Choices'].split(" | ");
            document.getElementById('questionIDEdit').value = element['ID'];
            document.getElementById('topicIDEdit').value = selectedID;
            document.getElementById('questionProvIDEdit').value = selProvID;
            document.getElementById('questionTopicEdit').value = element['Topic'];
            document.getElementById('sectionEdit').value = element['Section'];
            document.getElementById('questionEdit').value = element['Question'];
            document.getElementById('questionImgEdit').value = element['QuestionImage']; 
            var parent = document.getElementById('editChoices');
            while (parent.hasChildNodes()){
            parent.firstChild.remove()
            }
            for(var i = 0; i < arrChoices.length; i++)
            {
              var newRowAdd;
              if(i<=3)
              {
                newRowAdd =
                '<div class="row col-sm-12">' + 
                  '<div class="col-sm-11">' +
                    '<input class="form-control" name="choices_name[]" type="text" value="'+arrChoices[i]+'" required ></input>' +
                  '</div>' + 
                '</div>';
              }
              else
              {
                newRowAdd =
                '<div class="row col-sm-12">' + 
                  '<div class="col-sm-11">' +
                    '<input class="form-control" name="choices_name[]" type="text" value="'+arrChoices[i]+'" required ></input>' +
                  '</div>' +
                  '<input type="button" class="col-sm-1 btn btn-danger" id="removeInputEdit" value="-"></input>' +
                '</div>';
              }
              


              $('#editChoices').append(newRowAdd);
            }
            document.getElementById('CorAnswerEdit').value = element['CorrectAnswer'];
            let c = arrChoices.length;
            if(c == 1)
              {
                var b = document.getElementById('removeInputEdit');
                
              }
            $("#editChoices").on('click', '#removeInputEdit', function(e){
              e.preventDefault();
              c--;
              //if(document.getElementById('editChoices').childElementCount == 1)
              

              $(this).parent('div').remove();

            });
            
            
          });
          editBtn.value = "Edit";
          cell.appendChild(editBtn);
          let deleteBtn = document.createElement("input");
          deleteBtn.type = "button";
          deleteBtn.className = "btn btn-danger"; 
          deleteBtn.addEventListener('click', function (){ 
              Swal.fire({
                  title: 'Are you sure?',
                  text: "You won't be able to revert this! this questionnaire will be permanently deleted are you sure you want to continue this action?",
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                  if (result.isConfirmed) {
                    
                    fetch(url + '/' + selProvID + "/Topics/" + selectedID + "/Quiz/" + element['ID'] + "?currentDocument.exists=true", {
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
                      httpr.send("delQuest=" + element['Topic'] + "&user=" + user + "&questID=" + element['ID']);
                      window.location = "questionnaires.php";
                    })
                    });
                  }
                });
            

          });
          deleteBtn.value = "Delete";
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
    }

    var optionCount = 0;
    var choicesCount = 0;

    $("#addChoicesEdit").click( function(){
      choicesCount++;
      var newRowAdd =
      '<div class="row col-sm-12">' +
        '<label for="providerNameEdit" class="col-sm-2 col-form-label"></label>' +
        '<div class="col-sm-9">' +
          '<input class="form-control" name="field_choices[]" type="text" value="" required ></input>' +
        '</div>' +
        '<input type="button" class="col-sm-1 btn btn-danger" id="removeChoice" value="-"></input>' +
      '</div>';


      $('#editChoices').append(newRowAdd);

    });

    $("#editChoices").on('click', '#removeChoice', function(e){
      e.preventDefault();
      choicesCount--;


      $(this).parent('div').remove();

    });

    $("#addChoices").click( function(){
      optionCount++;
      var newRowAdd =
      '<div class="row col-sm-12">' +
        '<label for="providerNameEdit" class="col-sm-2 col-form-label"></label>' +
        '<div class="col-sm-9">' +
          '<input class="form-control" name="field_name[]" type="text" value="" required ></input>' +
        '</div>' +
        '<input type="button" class="col-sm-1 btn btn-danger" id="removeInput" value="-"></input>' +
      '</div>';

      $('#choicesCont').append(newRowAdd);

    });

    $("#choicesCont").on('click', '#removeInput', function(e){
      e.preventDefault();
      optionCount--;

      $(this).parent('div').remove();

    });




  var selectCorAns = document.getElementById("selectCorAnswer");
  



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


  // Get the button
    let mybutton = document.getElementById("myBtn");

    // When the user scrolls down 20px from the top of the document, show the button
    window.onscroll = function() {scrollFunction()};

    function scrollFunction() {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            mybutton.style.display = "block";
        } else {
            mybutton.style.display = "none";
        }
    }

    $("#myBtn").on('click', function(e){
        topFunction();
    });
    // When the user clicks on the button, scroll to the top of the document
    function topFunction() {
        document.body.scrollTop = 0;
        document.documentElement.scrollTop = 0;
    }
  </script>
</body>
</html>

