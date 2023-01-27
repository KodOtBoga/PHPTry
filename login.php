<?php
session_start();
require_once('func.php');
require_once('db.php');

$username = find('username');
$password = find('password');

try {
    authenticate($username, $password);
}
catch(\Exception $e) {
    $_SESSION['error'] = $e->getMessage();
}

    // if ($password && $username) {
    //     if($user = findUser($username)){
    //         if(password_verify($password, $user['password'])){
    //             $_SESSION['error'] = 'Неправильный пароль!';
    //         }
    //     }
    //     else{
    //         $_SESSION['error'] = 'Пользователь не найден!';
    //     }
    // } else {
    //     $_SESSION['error'] = 'Нужно заполнить все поля!';
    // }


    function authenticate(string $username, string $password){
        if(!$username || !$password){
            throw new \Exception('Заполните все поля!');
        }
        if(!$user = findUser($username)){
            throw new \Exception('Пользователь не найден!');
        }
        if(!password_verify($password, $user['password'])){
            throw new \Exception('Пароль не верный!');
        }
        unset($user['password']);
        $_SESSION['user'] = $user;
    }

header("Location: index.php");

// if(isset($user)){
//     if(password_verify($password, $user['password'])){

//     }
//     header("Location: index.php");
// }
// else{
//     header("Location: index.php");
// }