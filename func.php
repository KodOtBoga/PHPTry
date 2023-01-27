<?php
use LDAP\Result;
function getMax($a, $b){
	return max($a, $b);
}
function random_color_part() {
    return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
}

function random_color($a) {
    return random_color_part() . random_color_part() . $a;
}

function find(string $key): mixed
{
    if (isset($_POST[$key])) {
        return $_POST[$key];
    }
    if (isset($_GET[$key])) {
        return $_GET[$key];
    }
    
    return null;
}


function findAndDelete(string $key): mixed
{
    if (!isset($_SESSION[$key])) {
        return null;
    }
    
    $result = $_SESSION[$key];
    unset($_SESSION[$key]);
    return $result;
}

function findInSession(string $key): mixed{
    if(!isset($_SESSION[$key])){
        return null;
    }
    return $_SESSION[$key];
}

function export(string $filename, array $data, string $format = 'csv'): void
{
    $file = fopen("img/$filename.$format", 'w+');

    if($format == 'csv'){
        $keys = '"' . implode('","', array_keys(current($data))) . '",' . PHP_EOL;
        fwrite($file, $keys);
        foreach($data as $row)
        {
            fwrite($file, '"' . implode('","', $row) . '",' . PHP_EOL);
        }
    }
    else{
        fwrite($file, json_encode($data));
    }


    fclose($file);
}

function importJson(string $filename): void
{
    $content = json_decode(file_get_contents($filename), true);
    batchInsert($content);
}
