<?php

namespace App\Model;

use \Firebase\JWT\JWT;
use PDO;

class Token
{

    public function generateTokens($id, $username){

        $secret_key = $_ENV['SECRET_KEY'];
        $secret_key2 = $_ENV['SECRET_KEY2'];
        $issuer_claim = "THE_ISSUER"; // this can be the servername
        $audience_claim = "THE_AUDIENCE";
        $issuedat_claim = time(); // issued at
        $notbefore_claim = $issuedat_claim; //not before in seconds
        $expire_claim = $issuedat_claim + 25000000; // expire time in seconds
        $expire_claim2 = $issuedat_claim + 11;
        $token = array(
            "iss" => "$issuer_claim",
            "aud" => $audience_claim,
            "iat" => $issuedat_claim,
            "nbf" => $notbefore_claim,
            "exp" => $expire_claim,
            "data" => array(
                "id" => $id,
                "username" => $username,
            )
        );

        $token2 = array(
            "iss" => "$issuer_claim",
            "aud" => $audience_claim,
            "iat" => $issuedat_claim,
            "nbf" => $notbefore_claim,
            "exp" => $expire_claim2,
            "data" => array(
                "id" => $id,
                "username" => $username,
            )
        );

        $refreshToken = JWT::encode($token, $secret_key);
        $accessToken = JWT::encode($token2, $secret_key2);


        return array('refresh'=>$refreshToken, 'access'=>$accessToken);

    }

    public function generateAccessToken($id, $username){


        
        $issuer_claim = "THE_ISSUER"; // this can be the servername
        $audience_claim = "THE_AUDIENCE";
        $issuedat_claim = time(); // issued at
        $notbefore_claim = $issuedat_claim; //not before in seconds
        $expire_claim = $issuedat_claim + 25000000; // expire time in seconds
        $expire_claim2 = $issuedat_claim + 11;

        $token2 = array(
            "iss" => "$issuer_claim",
            "aud" => $audience_claim,
            "iat" => $issuedat_claim,
            "nbf" => $notbefore_claim,
            "exp" => $expire_claim2,
            "data" => array(
                "id" => $id,
                "username" => $username,
            )
        );
        
        return $token2;
    }

}