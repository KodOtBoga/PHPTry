<?php

$_connection = null;

function getConnection(): bool|mysqli|null
{
    global $_connection;

    if (!$_connection) {
        $_connection = mysqli_connect('localhost', 'root');
        mysqli_select_db($_connection, 'stepphpdb');
    }

    return $_connection;
}

function register(string $username, string $password): void
{
    $statement = mysqli_prepare(
        getConnection(),
        "INSERT INTO users (username, password) VALUES (?, ?);",
    );
    $password = crypt($password, 'randomSalt');
    mysqli_stmt_bind_param($statement, 'ss', $username, $password);
    mysqli_stmt_execute($statement);
}

function registerJSon(string $username, string $password): void
{
    $statement = mysqli_prepare(
        getConnection(),
        "INSERT INTO users (username, password) VALUES (?, ?);",
    );
    mysqli_stmt_bind_param($statement, 'ss', $username, $password);
    mysqli_stmt_execute($statement);
}

function checkUser(string $username): bool|array|null
{
    $statement = mysqli_prepare(
        getConnection(),
        "SELECT username FROM users WHERE username = ?",
    );
    mysqli_stmt_bind_param($statement, 's', $username);
    mysqli_stmt_execute($statement);
    return mysqli_fetch_assoc(mysqli_stmt_get_result($statement));
}

function findUser(string $username, bool $isFull = false): bool|array|null
{
    $columns = $isFull ? '*' : 'id, username, password';
    $statement = mysqli_prepare(
        getConnection(),
        "SELECT $columns FROM users WHERE username = ?",
    );
    mysqli_stmt_bind_param($statement, 's', $username);
    mysqli_stmt_execute($statement);
    return mysqli_fetch_assoc(mysqli_stmt_get_result($statement));
}

function deleteUser(User $user): void
{
    $query = "DELETE FROM users WHERE id = $user->id;";
    mysqli_query(getConnection(), $query);
}

function deleteUserById(int $id): void
{
    $query = "DELETE from users where id = $id";
    mysqli_query(getConnection(), $query);
}

function updateUser(User $user): void
{
    $query = "UPDATE users SET ";

    foreach($user as $column => $value){
        if($column === 'id'){
            continue;
        }
        $query .= "$column = '$value', ";
    }
    $query = trim($query, ', ');
    $query .= "WHERE id = $user->id;";

    mysqli_query(getConnection(), $query);
}

function updateUserJson(array $user): void
{
    $query = "UPDATE users SET ";

    $lastKey = array_key_last($user);
    foreach($user as $column => $value){
        $query .= "$column = '$value'";
        if($lastKey != $column){
            $query .= ', ';
        }
    }
    $query .= "WHERE username = " . $user['username'];

    $statement = mysqli_prepare(
        getConnection(), 
        $query,
    );

    mysqli_stmt_execute($statement);
}

function getAllUsers()
{
    $result = mysqli_query(getConnection(), 'SELECT * FROM users');
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function findUsersByLocation(string $location): array 
{
    $result = mysqli_query(getConnection(), "SELECT * FROM users where location = '$location';" );
    return mysqli_fetch_all($result,MYSQLI_ASSOC);
}

function findUsers(int $page, int $pageSize): array
{
    $temp = ($page - 1) * $pageSize;
    $result = mysqli_query(getConnection(), "SELECT * FROM users ORDER BY id LIMIT $temp;" );
    return mysqli_fetch_all($result,MYSQLI_ASSOC);
}

function batchInsert(array $users)
{
    $query = "INSERT INTO users (";
    $userKeys = current($users);
    $lastKey = array_key_last($userKeys);
    $lastUser = end($users);
    unset($userKeys['id']);
    $query .= implode(', ', array_keys($userKeys));
    $query .= ') VALUES (';

    foreach($users as $user){
        foreach(array_keys($userKeys) as $key){
            $query .= "'$user[$key]'";
            if($lastKey != $key){
                $query .= ', ';
            }
        }

        $query .= ')';
        if($lastUser != $user){
            $query .= ', (';
        }
        else{
            $query .= ';';
        }
    }

    mysqli_query(getConnection(), $query);
}