<?php

namespace dark\bootstrap;

use dark\config\db;
use EmailValidation\EmailValidatorFactory as emailValidator;
use dark\boiler\enc;

class userHandler{

	public static $uid = "";
	public static $username = "";
	public static $session = false;
	public static $USERNAME_MIN_LENGTH = 4;
	public static $PASSWORD_MIN_LENGTH = 6;
	public static $message = "";

	function __construct(){
		@session_start();
		if(isset($_SESSION['uid'])){
			self::$session = true;
			self::$uid = $_SESSION['uid'];
		};
	}
		
	public static function status(){
		if(self::$session){
			return true;
		};
		return false;		
	}

	public static function createUser($username, $email, $password){
		if(strlen($username) < self::$USERNAME_MIN_LENGTH){
			self::$message = "username too short";
			return false;
		};
		if(strlen($password) < self::$PASSWORD_MIN_LENGTH){
			self::$message = "password too short";
			return false;
		}
		if(!self::checkEmail($email)['valid_format']){
			self::$message = "email invalid";
			return false;	
		}
		$conn = db::getInstance();
		$hashedPassword = password_hash($password, PASSWORD_DEFAULT, ['cost'=>getenv("password_cost")]);
		try{
			$conn->beginTransaction();
			$conn->exec("INSERT INTO `users` (username, email, password) VALUES ('$username', '$email', '$hashedPassword')");
			$conn->commit();
			return true;
		}catch(\PDOException $e){
			$conn->rollBack();
			return false;
		}
	}

	public static function loginUser($username="", $password=""){
		$conn = db::getInstance();
		$query = $conn->query("SELECT username, password, active FROM `users` WHERE `username`='$username' OR `email`='$username'", \PDO::FETCH_ASSOC);
		$user = $query->fetch();
		if(password_verify($password, $user['password'])){
			
		};
		return false;
	}

	public static function checkEmail($email){
		$validator = emailValidator::create($email);
		return $validator->getValidationResults()->asArray();
	}	
}
