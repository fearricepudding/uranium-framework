<?php

namespace uranium\utils;

class Response{

    public static function OK(){
        return "{\"status\":\"OK\"}";
    }

    //TODO: Make JSON builder
    public static function ERR(String $code="", String $message=""):String{
        return "{\"status\":\"error\", \"code\":\"".$code."\",\"message\":\"".$message."\"}";
    }
}