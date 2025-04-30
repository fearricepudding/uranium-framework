<?php
namespace uranium\middleware;

use uranium\utils\Request;
use uranium\component\UserHandler;
use uranium\core\PageHandler;

class CheckCSRF extends \uranium\core\Middleware{
    static function handle(){
        if(!isset($_POST['csrf-token']) 
                || !Request::checkCSRF($_POST['csrf-token'])){
            error_log("[*] CSRF token error");
            echo "CSRF token error";
            exit();
        };
        
    }
};