<?php

namespace uranium\core;

class encryptionEngine{
    public static function generateHash($value){
        $options = [
            "salt" => $_ENV['PWD_SALT'],
            "cost" => $_ENV['PWD_COST']
        ];
        return password_hash($value, PASSWORD_BCRYPT, $options);
    }
    public static function verifyHash($value, $hash){
        return password_verify($value, $hash);
    }
}
