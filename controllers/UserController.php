<?php

use uranium\core\PageHandler;
use uranium\core\EncryptionHelper;
use uranium\model\UserModel;
use uranium\model\UserDetailsModel;
use uranium\model\Session;
use uranium\utils\Request;

class userController{
    public static function loginUser(){
        $model = new UserModel();
        $result = array();
        if($model->exists()){
            $username = Request::sanitize($_POST["username"]);
            $result = $model->where("username", $username)
                        ->withProtected()
                        ->get()
                        ->getResults();
            if(count($result) > 0){
                $user = array_pop($result);
                $passwordTC = $_POST["password"];
                if(password_verify($passwordTC, $user["password"])){
                    $token = uniqid("session_");
                    $session = new Session();
                    $userAgent = substr($_SERVER['HTTP_USER_AGENT'], 0, 100);
                    $session->rows[] = [
                        "token" => $token,
                        "userAgent" => $userAgent,
                        "userId" => $user["id"]
                    ];
                    $session->save();
                    $_SESSION["token"] = $token;
                    PageHandler::redirect("/dashboard");
                };
            };
        };
        PageHandler::view("login", ["loginerror" => true, "message" => "Invalid credentials"]);
    }

    public static function destroySession(){
        if(session_id() === "") session_start();
        if(isset($_SESSION["token"])){
            $sessionModel = new Session();
            $sessionQuery = $sessionModel->where("token", $_SESSION["token"])->get();
            $sessions = $sessionQuery->getResults();
            if(count($sessions) > 0){
                $sessionQuery->delete();
            }
            unset($_SESSION["token"]);
        };
        session_unset();
        PageHandler::view("loggedout");
    }

    public static function createUser(){

        $model = new UserModel();
        $password = Request::sanitize($_POST['password']);
        
        $newUser = [
            "username"	=> Request::sanitize($_POST['username']),
            "email"		=> Request::sanitize($_POST['email']),
            "password"  => EncryptionHelper::generateHash($password)
        ];

        $errors = [];
        $error = false;

        // Check passwords match
        $confirmPassword = Request::sanitize($_POST['confirm-password']);
        if ($confirmPassword != $password){
            $errors[] = "Passwords do not match";
            $error = true;
        };

        // Check if username already exists
        $checkUserResult = $model->where("username", $newUser["username"])
            ->withProtected()
            ->get()
            ->getResults();
        if(count($checkUserResult) > 0){
            $errors[] = "Username already exists";
            $error = true;
        };
        
        // Check password strength
        if(!self::checkPasswordStrength($password)){
            $errors[] = "Password is not complex enough";
            $error = true;
        };

        // Check valid email
        if(!filter_var($newUser["email"], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Email address is invalid";
            $error = true;
        };

        if($error){
            $errorsString = "<ul>";
            foreach($errors as $errorItem){
                $errorsString .= "<li>".$errorItem."</li>";
            };
            $errorsString .= "</ul>";
            PageHandler::view("register", ["error" => true, "message" => $errorsString]);
            exit();
        };

        $model->rows[] = $newUser;
        try{
            $result = $model->save();
            PageHandler::view("registerSuccess");
        }catch(PDOException $e){
            error_log($e);
            echo "{\"result\":false}";
        }
    }

    public static function checkPasswordStrength(String $password): bool {
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number    = preg_match('@[0-9]@', $password);
        $specialChars = preg_match('@[^\w]@', $password);
        if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 10) {
            return false;
        };
        return true;
    }

    public static function updateUser(){
        $model = new UserDetailsModel();
        $newDetails = [
            "user_id"    => $_POST["uid"],
            "first_name" => $_POST["firstName"],
            "last_name"  => $_POST["lastName"]
        ];
        $model->rows[] = $newDetails;
        echo $model->save();

    }

}
