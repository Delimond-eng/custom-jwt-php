<?php

/*Database instance*/

use classes\JToken;

$db = new Database();

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $data =json_decode(file_get_contents('php://input', true));
    $user_email = htmlentities($data->user_email);
    $user_pass = htmlentities($data->user_pass);

    $db->select("users", "*", null, "user_email='{$user_email}'", null, null);
    $rows= $db->getResult();

    if(empty($rows)){
        echo json_encode(
            ['status'=> 'failed', 'message'=>'Invalid user credential ' ]
        );
        return false;
    }

    foreach ($rows as $row){
        $id = $row['id'];
        $email = $row['user_email'];
        $name = $row['user_name'];
        if(!password_verify($user_pass, $row['user_pass'])){
            echo json_encode(
                ['status'=> 'failed', 'message'=>'Invalid user credential ' ]
            );
            return false;
        }
        else{
           $data = JToken::parseCredential(["id"=>$id, "user_email"=>$email, "user_name"=>$name]);
           echo $data;
        }

    }
}

