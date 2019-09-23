<?php

namespace dark\config;

use \PDO;

class db {
	private static $objInstance;
	private function __construct() {}
	private function __clone() {}
	public static function getInstance() {
		if(!self::$objInstance){
			$db_host = getenv("db_hostname");
			$db_name = getenv("db_name");	
			self::$objInstance = new PDO("mysql:host=$db_host;dbname=$db_name", getenv("db_username"), getenv("db_password"));
        		self::$objInstance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
        	return self::$objInstance;
	}
	final public static function __callStatic( $chrMethod, $arrArguments ) {
		$objInstance = self::getInstance();
		return call_user_func_array(array($objInstance, $chrMethod), $arrArguments);
	}
}
