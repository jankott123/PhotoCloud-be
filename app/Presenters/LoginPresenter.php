<?php

namespace App\Presenters;

use Nette;
use App\Model\RenderModel;
use Nette\Security\Passwords;
use \Firebase\JWT\JWT;
use Nette\Utils\Json;
use \Exception;
use App\Model\Authorization;
use App\Model\EntityManager;

class LoginPresenter extends Nette\Application\UI\Presenter
{

    /**
     * passwords
     *
     * @var mixed
     */
    private $passwords;

    /**
     * authorization
     *
     * @var mixed
     */
    private $authorization;

    /**
     * manager
     *
     * @var mixed
     */
    private $manager;

    public function __construct(Passwords $passwords, Authorization $authorization, EntityManager $manager)
    {

        $this->passwords = $passwords;

        $this->authorization = $authorization;
        $this->manager = $manager;
    }




    public function actionLoginJwt(): void
    {


        header("Access-Control-Allow-Origin: http://localhost:3000");
        // header("Content-Type: application/json; charset=UTF-8");
        // header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        // header("Access-Control-Max-Age: 3600");
        header('Access-Control-Allow-Credentials: true');


        //header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

        $httpResponse = $this->getHttpResponse();
        $data = json_decode(file_get_contents("php://input"));
        $result = $this->manager->loginCheck($data->username);


        if ($result) {

            if (password_verify($data->password, $result['password']) == true) {

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
                        "id" => $result['id'],
                        "username" => $result['username'],
                    )
                );

                $token2 = array(
                    "iss" => "$issuer_claim",
                    "aud" => $audience_claim,
                    "iat" => $issuedat_claim,
                    "nbf" => $notbefore_claim,
                    "exp" => $expire_claim2,
                    "data" => array(
                        "id" => $result['id'],
                        "username" => $result['username'],
                    )
                );

                $refreshToken = JWT::encode($token, $secret_key);
                $accessToken = JWT::encode($token2, $secret_key2);

                $refresh = "refreshtoken";
                $access = "accesstoken";

                $cookie_options = array(
                    'expires' => time() + 60 * 60 * 24 * 30,
                    'path' => '/',
                    'domain' => '', // leading dot for compatibility or use subdomain
                    'secure' => true, // or false
                    'httponly' => true, // or false
                    'samesite' => 'None' // None || Lax || Strict
                );

                $cookie_options1 = array(
                    'expires' => time() + 11,
                    'path' => '/',
                    'domain' => '', // leading dot for compatibility or use subdomain
                    'secure' => true, // or false
                    'httponly' => false, // or false
                    'samesite' => 'None' // None || Lax || Strict
                );

                setcookie($refresh, $refreshToken, $cookie_options);
                setcookie($access, $accessToken, $cookie_options1);


                setcookie('username', $result['username'], time() + 1000000, $path = "/", $domain = "", $secure = false, $httponly = false);

                //setcookie($refresh, $refreshToken, time() + (90 * 24 * 60 * 60), "/", "", false, true);
                //setcookie($access, $accessToken, time() + 11, "/", "", false, false);
                // setcookie($refresh,           $access, ['samesite' => 'None','secure'=>'false']);
                //  $httpResponse = $this->getHttpResponse();
                //$httpResponse->setCookie('refreshtoken', $refreshToken, '100 days', $sameSite=null);
                //$httpResponse->setCookie('accesstoken',$accessToken, time() + 11, $sameSite=null, $httponly=false);
                //  setcookie('sds', $value, ['samesite' => 'None', 'secure' => false]);
                //  setcookie('accesstoken', $accessToken, time() + 11, $path = "/", $domain = "", $secure = false, $httponly = false, $sameSite="None");
                //     setcookie('username', $result[0]['username'], time() + 1000000, $path = "/", $domain = "", $secure = false, $httponly = false, $sameSite="None");

                $this->sendJson("prihlasen");
            }
            $httpResponse->setCode(Nette\Http\Response::S401_UNAUTHORIZED);
        } else {
            $httpResponse->setCode(Nette\Http\Response::S401_UNAUTHORIZED);
        }
    }

    public function actionRegister(): void
    {

        header("Access-Control-Allow-Origin: http://localhost:3000");
        header("Access-Control-Allow-Methods: POST");

        $username = $_POST['username'];
        $password = $_POST['password'];
        $email = $_POST['email'];


        if (!isset($_POST['username'], $_POST['password'], $_POST['email'])) {
            // Could not get the data that should have been sent.
            $this->sendJson('Please complete the registration form!');
        }
        // Make sure the submitted registration values are not empty.
        if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email'])) {
            // One or more values are empty.

            $this->sendJson('Please complete the registration form!');
        }


        if ($this->manager->checkUser($username)) {
            $this->sendJson("User is already exists");
        }


        if ($this->manager->checkEmail($email)) {
            $this->sendJson("Email is already exists");
        }


        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->sendJson("Email is not valid");
        }

        if (preg_match('/^[a-zA-Z0-9]{4,50}+$/', $username) == 0) {
            $this->sendJson("Username is not valid");
        }
        if (strlen($password) > 20 || strlen($password) < 8) {
            $this->sendJson('Password must be between 8 and 20 characters long!');
        }
        $password = password_hash($password, PASSWORD_BCRYPT);
        $uniqid = uniqid();
        $this->manager->registerUser($username, $password, $email, $uniqid);

        $this->sendJson("ok");
    }

    public function actionActivation(string $email, string $activation_code): void
    {

        $activated = "activated";

        $result = $this->manager->activation($email, $activation_code);

        if ($result) {

            $this->manager->activationFinal($email, $activation_code, $activated);
        }
    }


    public function actionRefresh(): void
    {

        header("Access-Control-Allow-Origin: http://localhost:3000");
        header('Access-Control-Allow-Credentials: true');

        $httpResponse = $this->getHttpResponse();

        $refreshOK = $this->authorization->refreshToken();

        if (!$refreshOK) {
            $httpResponse->setCode(Nette\Http\Response::S401_UNAUTHORIZED);
        }

        $this->sendJson("v poradku");
    }



    public function actionRenew(): void
    {

        header("Access-Control-Allow-Origin: *");
        $x =  json_decode(file_get_contents('php://input'));
        if (!$x) {
            $this->sendJson("neprijmul sem token");
        }


        try {
            $key = "refresh";
            $token = $x;
            $data = JWT::decode($token, $key, array('HS256'));
        } catch (Exception $e) { // Also tried JwtException
            $this->sendJson("Snazite se me podvest???");
        }
        $issuedat_claim = time();
        $payload = array(
            "iss" => "http://example.org",
            "aud" => "http://example.com",
            "iat" =>  $issuedat_claim,
            "nbf" =>  $issuedat_claim + 10,
            "exp" =>  $issuedat_claim + 40,
        );


        $accessToken = JWT::encode($payload, $_ENV['SECRET_KEY2']);

        $this->sendJson($accessToken);
    }

    public function actionLogOut(): void
    {   header("Access-Control-Allow-Origin: http://localhost:3000");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header('Access-Control-Allow-Credentials: true');
   


        $auth_id = $this->authorization->authorize();
        $httpResponse = $this->getHttpResponse();
        $null = "";

        if ($auth_id) {
            $httpResponse->setCookie('refreshtoken', $null, time() - 3600);
        }

        $this->sendJson("Cookie vymazan");
    }
}
