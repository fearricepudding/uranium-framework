<?php

namespace uranium\database;

use \PDO;

class db {
    private static $objInstance;
    private function __construct() {}
    private function __clone() {}
    
    public static function getInstance() {
        if(!self::$objInstance){
            $db_host = $_ENV["db_host"];
            $db_name = $_ENV["db_name"];	
            $dsn = "mysql:dbname=$db_name;host=$db_host";
            self::$objInstance = new PDO($dsn, $_ENV["db_user"], $_ENV["db_pass"]);
            self::$objInstance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return self::$objInstance;
    }

    final public static function __callStatic( $chrMethod, $arrArguments ) {
        $objInstance = self::getInstance();
        return call_user_func_array(array($objInstance, $chrMethod), $arrArguments);
    }
}
