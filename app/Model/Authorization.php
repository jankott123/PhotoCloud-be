<?php

namespace App\Model;

use Nette;
use \Firebase\JWT\JWT;
use Nette\Utils\Json;
use \Exception;
use App\Model\Token;

class Authorization
{


    private $manager;

    private $token;

    public function __construct(EntityManager $manager, Token $token)
    {

        $this->manager = $manager;
        $this->token = $token;
    }

    public function authorize(): int
    {

        $valid = true;
        if (!isset($_COOKIE["accesstoken"])) {
            return $valid = false;
        }

        try {
            $key = $_ENV['SECRET_KEY2'];
            $token = $_COOKIE["accesstoken"];
            $data = JWT::decode($token, $key, array('HS256'));
            $valid = $data->data->id;
            return $valid;
        } catch (Exception $e) { // Also tried JwtException

            return $valid = false;
        }
    }


    public function refreshToken()
    {

        $valid = true;
        if (!isset($_COOKIE["refreshtoken"])) {
            return $valid = false;
        }


        try {
            $key = $_ENV['SECRET_KEY'];
            $token = $_COOKIE["refreshtoken"];
            $data = JWT::decode($token, $key, array('HS256'));
            $valid = true;
        } catch (Exception $e) { // Also tried JwtException

            $valid = false;
        }

        if ($valid) {
            $result = $this->manager->checkId($data->data->id);

            $secret_key2 = $_ENV['SECRET_KEY2'];
            $token2 = $this->token->generateAccessToken($result['id'], $result['username']);

            $accessToken = JWT::encode($token2, $secret_key2);

            $cookie_options1 = array(
                'expires' => time() + 11,
                'path' => '/',
                'domain' => '', // leading dot for compatibility or use subdomain
                'secure' => true, // or false
                'httponly' => false, // or false
                'samesite' => 'None' // None || Lax || Strict
            );

            setcookie('accesstoken', $accessToken, $cookie_options1);
            return $valid;
        }
    }
}
