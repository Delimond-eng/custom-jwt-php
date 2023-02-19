<?php

use classes\JToken;

$db = new Database();
    $method = $_SERVER["REQUEST_METHOD"];
    if($method == "GET"){
        if(JToken::checkCredential()){
            $db->select("posts", "*",  null, null, "id DESC", null);
            $data = $db->getResult();
            echo json_encode([
                "status"=>"success",
                "data" =>$data
            ]);
        }

    }

    if($method == "POST"){
        if(JToken::checkCredential()){
            $data =json_decode(file_get_contents('php://input', true));
            $title =htmlentities($data->title);
            $body =htmlentities($data->body);

            $params = [
                "body" => $body,
                "title" => $title,
                "create_At"=>date("Y-m-d")
            ];
            $db->insert("posts", $params);
            $result = $db->getResult();
            if($result[0] == 1){
                echo json_encode(
                    ['status'=> 'success', 'message'=>'Post created succefuly' ]
                );
            }
            else{
                echo json_encode(
                    ['status'=> 'failed', 'message'=>'Post not created' ]
                );
            }
        }
    }
