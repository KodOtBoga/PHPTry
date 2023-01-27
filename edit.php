<?php
    session_start();
    require_once('func.php');
    require_once('db.php');

    
function editUser(array $user, int $id)
{
    $query = "UPDATE users SET";

    $lastKey = array_key_last($user);
    foreach($user as $column => $value){
        $query .= "$column = '$value'";
        if($lastKey != $column){
            $query .= ', ';
        }
    }
    $query . "WHERE id = $id;";

    $statement = mysqli_prepare(
        getConnection(), 
        $query,
    );
    mysqli_stmt_execute($statement);
}