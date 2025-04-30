<?php

namespace uranium\component;

use uranium\core\ConfigHandler;
use \PDO;

class Database {
    private static $objInstance;
    private function __construct() {}
    private function __clone() {}
    
    public static function getInstance() {
        if(!self::$objInstance){
            $config = ConfigHandler::getInstance();
            $db_host = $config->getValue("db_host");
            $db_name = $config->getValue("db_name");
            $db_port = $config->getValue("db_port");
            $dsn = "mysql:dbname=$db_name;host=$db_host;port=$db_port";
            try{
                self::$objInstance = new PDO($dsn, $config->getValue("db_user"), $config->getValue("db_pass"));
                self::$objInstance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }catch(PDOException $Exception){
                var_dump($Exception);
                exit();
            };
        }
        return self::$objInstance;
    }

    final public static function __callStatic( $chrMethod, $arrArguments ) {
        $objInstance = self::getInstance();
        return call_user_func_array(array($objInstance, $chrMethod), $arrArguments);
    }
}
