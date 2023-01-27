<?php
    session_start();
    require_once('func.php');
    require_once('db.php');

    $username = find('username');
    $password = find('password');
    if(checkUser($username)){
        $_SESSION['error'] = 'Пользователь с таким именем уже есть!';
    } 
    else {
        if ($password && $username) {
            register($username, $password);
        } 
        else {
            $_SESSION['error'] = 'Нужно заполнить все поля!';
        }
    }

    //register(find('username'), find('password'));
    header("Location: index.php");