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
use App\Model\Token;

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

    private $token;

    public function __construct(Passwords $passwords, Authorization $authorization, EntityManager $manager, Token $token)
    {

        $this->passwords = $passwords;
        $this->authorization = $authorization;
        $this->manager = $manager;
        $this->token = $token;
    }




    public function actionLoginJwt(): void
    {


        header("Access-Control-Allow-Origin: http://localhost:3000");
        header('Access-Control-Allow-Credentials: true');

        $httpResponse = $this->getHttpResponse();
        $data = json_decode(file_get_contents("php://input"));
        $result = $this->manager->loginCheck($data->username);


        if ($result) {

            if (password_verify($data->password, $result['password']) == true) {


                $tokens = $this->token->generateTokens($result['id'], $result['username']);

                $access = "accesstoken";
                $refresh = "refreshtoken";

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

                setcookie($refresh, $tokens['refresh'], $cookie_options);
                setcookie($access, $tokens['acess'], $cookie_options1);
                setcookie('username', $result['username'], time() + 1000000, $path = "/", $domain = "", $secure = false, $httponly = false);



                $this->sendJson("prihlasen");
            }
            $httpResponse->setCode(Nette\Http\Response::S401_UNAUTHORIZED);
        } else {
            $httpResponse->setCode(Nette\Http\Response::S401_UNAUTHORIZED);
        }
    }



    public function actionLogOut(): void
    {
        header("Access-Control-Allow-Origin: http://localhost:3000");
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
