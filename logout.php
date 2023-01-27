<?php
    session_start();
    require_once('func.php');
    require_once('db.php');

deleteUserIfExist();

function deleteUserIfExist(){
    if($_SESSION['user']){
        unset($_SESSION['user']);
    }
}

header("Location: index.php");