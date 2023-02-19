<?php

namespace classes;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JToken
{

    private static $secret_key = "RTG1234";
    private static $algo="HS256";
    /*
    * check authorization credential
    * */
    public static function checkCredential(){
        try {
            $headers = getallheaders();
            $authorization = isset($headers["Authorization"]) !== null && isset($headers["Authorization"]);
            if($authorization){
                $jwt =  $headers["Authorization"];
                $token =str_replace("Bearer ", "", $jwt);
                $decoded = JWT::decode($token , new Key(self::$secret_key, self::$algo));
                return true;
            }
            else{
                echo json_encode(
                    [
                        'status'=> 'failed',
                        'message'=>"token undefined"
                    ]
                );
            }
        }
        catch (Exception $e){
            echo json_encode(
                [
                    'status'=> 'failed',
                    'message'=>"Invalid token"
                ]
            );
            return false;
        }
    }

    /**
     * parse user data to credential token
     * @return json|false|string
     */
    public static function parseCredential($user){
        $payload = [
            "iss"=>"localhost",
            "aud"=>"localhost",
            "exp"=>time() + 10000,

            "data"=>$user
        ];

        $jwt = JWT::encode($payload,self::$secret_key, self::$algo );

        return json_encode(
            [
                'status'=> 'success',
                'message'=>'logged in successfully',
                'token'=>$jwt
            ]
        );
    }


}