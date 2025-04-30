<?php

namespace uranium\core;

class EncryptionHelper{

    public static function generateHash(String $value): String{
        $config = ConfigHandler::getInstance();
        $options = [
            "cost" => $config->getValue("passwd_cost")
        ];
        return password_hash($value, PASSWORD_BCRYPT, $options);
    }

    public static function verifyHash($value, $hash){
        return password_verify($value, $hash);
    }

}
