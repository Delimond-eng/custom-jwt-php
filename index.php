<?php

    header("Access-Control-Allow-Origin:*");
    header("Access-Control-Allow-Method:POST, GET, DELETE, PUT");

    $method = $_SERVER["REQUEST_METHOD"];
    $request_uri = $_SERVER["REQUEST_URI"];

    $request_time = $_SERVER["REQUEST_TIME"];
    $url = rtrim($request_uri, '/');
    $url = filter_var($url, FILTER_SANITIZE_URL);
    $url = explode('/', $url);
    $tablename = !empty($url[2]) ? (string) $url[2] : null;
    $id = !empty($url[3]) ? (int) $url[3]: null;
    $tables = glob("api/*.php");
    $tablefile = "api/$tablename.php";
    if(in_array($tablefile, $tables)){
        require_once 'vendor/autoload.php';
        require_once 'classes/JToken.php';
        require_once 'classes/Database.php';
        require_once "$tablefile";
    }
    else{
        echo json_encode([
            "status"=> "failed",
            "message"=>"table doesn't exist"
        ]);
    }
