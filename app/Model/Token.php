<?php

namespace App\Model;

use \Firebase\JWT\JWT;
use PDO;


class Token
{

    private  $secret_key;
    private  $secret_key2;
    private $issuer_claim;
    private $audience_claim;
    private $issuedat_claim;


    public function __construct()
    {
        $this->secret_key = $_ENV['SECRET_KEY'];
        $this->secret_key2 = $_ENV['SECRET_KEY2'];
        $this->issuer_claim =  "THE_ISSUER";
        $this->audience_claim = "THE_AUDIENCE";
        $this->issuedat_claim = time();
    }

    public function generateTokens($id, $username)
    {

        $refreshToken = $this->createToken($id, $username, 25000000, $this->secret_key);
        $accessToken = $this->createToken($id, $username, 11, $this->secret_key2);


        return array('refresh' => $refreshToken, 'access' => $accessToken);
    }

    public function createToken($id, $username, $exp_time, $secret_key)
    {

        $token = array(
            "iss" => $this->issuer_claim,
            "aud" => $this->audience_claim,
            "iat" => $this->issuedat_claim,
            "nbf" => $this->issuedat_claim,
            "exp" => $this->issuedat_claim + $exp_time,
            "data" => array(
                "id" => $id,
                "username" => $username,
            )
        );

        return JWT::encode($token, $secret_key);
    }
}
