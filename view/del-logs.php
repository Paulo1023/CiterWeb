<?php

include '../class/Logs.php';
include '../class/Analytics.php';
$analytics = new Analytics();
$logs = new Logs();

if(isset($_POST['delProv']))
{

    $user = $_POST['user'];
    $remarks = "Provider with the name of " . $_POST['delProv'] . " is deleted";
    $logs->insertLog("DELETE", "Provider", $remarks, $user);
    $analytics->deleteData("providerID", $_POST['providerID']);
}

if(isset($_POST['delTopic']))
{
    $user = $_POST['user'];
    $remarks = "Topic with the name of " . $_POST['delTopic'] . " is deleted";
    $logs->insertLog("DELETE", "Topic", $remarks, $user);
    $analytics->deleteData("topicID", $_POST['topicID']);
}

if(isset($_POST['delQuest']))
{
    
    $user = $_POST['user'];
    $remarks = "Question in Topic " . $_POST['delQuest'] . " is deleted";
    $logs->insertLog("DELETE", "Question", $remarks, $user);
    $analytics->deleteData("questionID", $_POST['questID']);
}


?>