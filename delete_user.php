<?php
    session_start();
    require_once('func.php');
    require_once('db.php');
    require_once('user.php');

    if(!findInSession('user')){
        $_SESSION['error'] = 'Вы незалогинены';
        header('location:index.php');
    }
    try {
    $user = Person::loadFromDb(findInSession('user')['username']);
    $user->delete();
    } catch(\Exception $e) {
        $_SESSION['error'] = 'Пользователь удалён!';
        header('Location: logout.php');
    }

header("Location: index.php");