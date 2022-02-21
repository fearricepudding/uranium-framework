<?php

namespace uranium\core;

use uranium\config\db;
use EmailValidation\EmailValidatorFactory as emailValidator;
use uranium\boiler\enc;
use \PDO;

class userHandler{
    public static function authenticate($username, $password){
        
    }

    public static function isAuthenticated(){
        if(isset($_SESSION['uid']) && !empty($_SESSION['uid'])){
            return true;
        }else{
            return false;
        };
    }

    public static function getAuthenticatedUser(){
        if(!self::isAuthenticated()){
            return false;
        }
        $uid = $_SESSION['uid'];
        return getUser($uid);
    }

    public static function getUser($uid){

    }
}
