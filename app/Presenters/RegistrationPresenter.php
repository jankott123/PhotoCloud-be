<?php

namespace App\Presenters;

use Nette;


class RegistrationPresenter extends Nette\Application\UI\Presenter
{


    public function actionRegisterUser(){

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

}