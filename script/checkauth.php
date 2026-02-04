<?php
session_start();
if(!isset($_SESSION['autenticato'])){
    session_unset();
    session_destroy();
    header("Location:login.php");
}
?>
