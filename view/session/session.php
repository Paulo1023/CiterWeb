<?php
session_start();

if(empty($_SESSION["userId"])) {
    header("Location: ./login-form.php");
} 

?>
