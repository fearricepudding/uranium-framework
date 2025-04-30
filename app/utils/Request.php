<?php
namespace uranium\utils;

class Request{
    public static function sanitize(String $input): String{
        return stripslashes(htmlspecialchars($input));
    }

    public static function generateCSRF(): String{
        $passwordGen = password_hash(session_id(), PASSWORD_DEFAULT);
        return base64_encode($passwordGen);
    }

    public static function checkCSRF(String $hash): bool{
        $password = base64_decode($hash);
        return password_verify(session_id(), $password);
    }

    public static function getPost(String $key): String{
        return array_key_exists($key, $_POST)?self::sanitize($_POST[$key]):"";
    }

    public static function getGet(String $key): String{
        return array_key_exists($key, $_GET)?self::sanitize($_GET[$key]):"";
    }
}
