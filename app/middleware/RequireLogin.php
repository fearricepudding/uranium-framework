<?php

namespace uranium\middleware;

use uranium\utils\Request;
use uranium\component\UserHandler;
use uranium\core\PageHandler;

class RequireLogin extends \uranium\core\Middleware{
    static function handle(){
        if(isset($_SESSION["token"])){
            $token = Request::sanitize($_SESSION["token"]);
            if(!UserHandler::authenticateToken($token)){
                PageHandler::redirect("/login?redirect=".$_SERVER["REQUEST_URI"]);
            };
        }else{
            PageHandler::redirect("/login?redirect=".$_SERVER["REQUEST_URI"]);
        };
    }
};
