<?php
    session_start();

    foreach($_SESSION as $key => $value){
        unset($_SESSION[$key]);
    }

    if ($_POST){
        foreach($_POST as $key => $value){
            unset($_POST[$key]);
        }
    }

    if ($_GET){
        foreach($_GET as $key => $value){
            unset($_GET[$key]);
        }
    }

    session_unset();
    session_destroy();

    header('Location: login.php');
?>