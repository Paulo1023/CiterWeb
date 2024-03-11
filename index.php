<?php
session_start();
include 'class/Users.php';

date_default_timezone_set('Asia/Manila');

use \Member;
if(!empty($_SESSION["userId"])) {

    require_once './class/Member.php';
    $member = new Member();
    $memberResult = $member->getMemberById($_SESSION["userId"]);
    $userName =  $memberResult[0]["user_name"];
    $userType = ($memberResult[0]["superuser"] == 1)? "SUPERUSER" : "User"; 
    setcookie('user', $userName, time()+86400*30);
    setcookie('userType', $userType, time()+86400*30); 

    $users = new Users();
    $today = date('Y-m-d H:i:s');
    $users->loginUser($today, $_SESSION["userId"]);
    header("Location: ./view/dashboard.php");
} else {
    header("Location: ./view/login-form.php");
}
?>