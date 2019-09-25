<?php

namespace uranium\bootstrap;

use uranium\config\db;
use EmailValidation\EmailValidatorFactory as emailValidator;
use uranium\boiler\enc;
use \PDO;

class userHandler{

	public $uid = "";
	public $username = "";
	public $email = "";
	private static $session = false;
	private static $USERNAME_MIN_LENGTH = 4;
	private static $PASSWORD_MIN_LENGTH = 6;
	public $message = "";

	function __construct($uid=NULL){
		@session_start();
		if($uid !== NULL){
			$this->uid = $uid;
			$this->getUserData();
		}else{
			if(isset($_SESSION['uid'])){
				self::$session = true;
				$this->uid = $_SESSION['uid'];
				$this->getUserData();
			};
		}
	}
		
	public static function status(){
		@session_start();
		if(isset($_SESSION['uid'])){
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
		};
		if(!self::validateEmail($email)['valid_format']){
			self::$message = "email invalid";
			return false;	
		};
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
		};
	}

	public static function loginUser($username="", $password=""){
		if(!self::status()){
			$conn = db::getInstance();
			$query = $conn->query("SELECT id, username, password, active FROM `users` WHERE `username`='$username' OR `email`='$username'", PDO::FETCH_ASSOC);
			$user = $query->fetch();
			if(password_verify($password, $user['password'])){
				if(getenv("require_activation") && ($user['active'] == false)){
					return false;
				};
				$_SESSION['uid']=$user['id'];
				return true;
			};
			return false;
		};
		return true;
	}

	public static function logoutUser(){
		if(self::status()){
			$_SESSION['uid'] = "";
			session_destroy();
		}
		return true;
	}

	private function getUserData(){
		$conn = db::getInstance();
		$uid = $this->uid;
		try{
			$query = $conn->query("SELECT id, email, username, active FROM `users` WHERE `id`='$uid'", PDO::FETCH_ASSOC);
			$user = $query->fetch();
			$this->username = $user['username'];
			$this->email = $user['email'];
		}catch(PDOException $e){
			error_log($e);
			return false;
		}
	}

	public static function validateEmail($email){
		$validator = emailValidator::create($email);
		return $validator->getValidationResults()->asArray();
	}	
}
