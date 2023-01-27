<?php


if($_POST['var1'] != null and $_POST['var2'] != null){
    $username = $_POST['var1'];
    $password = $_POST['var2'];
    // echo $username . "\n" . $password . "\n";
    logEq($username, $password);
}

function connect(){


    registerUser("Sabit", "228");

    // $servername = "localhost";

    // $registerUserSql = "INSERT INTO users (username, password) values(";
    // $registerUserSql .= "'serezha', '12321');";

    // $conn = mysqli_connect($servername, 'root');
    // mysqli_select_db($conn, 'stepphpdb');
    // $query = mysqli_query($conn, 'SELECT * FROM users');
    // var_dump($query);

    //'SELECT * FROM users'
    // Check connection
    // if ($conn->connect_error) {
    //   die("Connection failed: " . $conn->connect_error);
    // }
}

function registerUser($username, $password){
    $servername = "localhost";

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $registerUserSql = "INSERT INTO users (username, password) values(";
    $registerUserSql .= "'$username', '$hashed_password');";

    $conn = mysqli_connect($servername, 'root');
    mysqli_select_db($conn, 'stepphpdb');
    
    $query = mysqli_query($conn, $registerUserSql);
    echo "Done";
}

function logEq($username, $password){
    $servername = "localhost";

    $registerUserSql = "SELECT username, password FROM users WHERE";
    $registerUserSql .= " username = '$username';";

    $conn = mysqli_connect($servername, 'root');
    mysqli_select_db($conn, 'stepphpdb');
    
    $query = mysqli_query($conn, $registerUserSql);
    $res = mysqli_fetch_array($query, MYSQLI_ASSOC);
    // var_dump($res);
    if(password_verify($password, $res['password'])){
        if($username == $res['username']){
            echo "Everything is OK " . $username . " " . $password;
        }
    }
    else{
        echo "Everything is not OK ";
    }
}



