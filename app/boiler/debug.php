<?php
namespace dark\boiler;

class dbg{
    public static function log($MESSAGE, $IDENTIFY = "unset"){
        error_log("[*] [$IDENTIFY] $MESSAGE");
    }
}