<?php
session_start();


if (!empty($_POST["login"])) {
    $username = filter_var($_POST["username"], FILTER_SANITIZE_STRING);
    $password = filter_var($_POST["password"], FILTER_SANITIZE_STRING);
    require_once ("./class/Member.php");

    $member = new Member();
    $isLoggedIn = $member->processLogin($username, $password);
    if (! $isLoggedIn) {
        $_SESSION["errorMessage"] = "Invalid Credentials";
    }
        setcookie('secret', $password, time()+86400*30);
        header("Location: ./index.php");
        exit();
        
}
?>
