<?php

namespace uranium\middleware;

use uranium\utils\Request;
use uranium\component\UserHandler;
use uranium\core\PageHandler;

class CheckAuthentication extends \uranium\core\Middleware{
    static function handle(){
        if(isset($_SESSION["token"])){
            $token = Request::sanitize($_SESSION["token"]);
            if(!UserHandler::authenticateToken($token)){
                session_unset();
                session_destroy();
            }else{
                PageHandler::redirect("/dashboard");
            }
        };
    }
}
