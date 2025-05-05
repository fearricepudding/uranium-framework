<?php

namespace uranium\utils;

class Response{

    public static function success(){
        return "{\"status\":\"OK\"}";
    }

    public static function error(String $code="", String $message=""):String{
        return "{\"status\":\"error\", \"code\":\"".$code."\",\"message\":\"".$message."\"}";
    }
}
