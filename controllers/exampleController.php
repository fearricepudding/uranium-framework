<?php

use uranium\core\pageHandler;
use uranium\core\userHandler;
use uranium\core\encryptionEngine;
use uranium\model\exampleModel;
use uranium\model\userModel;

class exampleController{
	
	public static function examplepage(){
		pageHandler::view("example", ["test"=>"example", "data" => $testData]);
	}

	public static function userexample(){
		$user = new userHandler();
		if($user::status()){
			echo "<ul>
			<li>UID: $user->uid </li>
			<li>Username: $user->username </li>
			<li>Email: $user->email </li>
			</ul>";
		}else{
			echo 'No login';
		}
	}

	public static function variableExample($variables){
		echo "One Variable";
		var_dump($variables);
	}
	
	public static function variableExtension($variable){
		echo "Variable Extension";
		var_dump($variable);
	}
	
	public static function twoVariables($variables){
		echo "Two variables <br />";
		var_dump($variables);
	}

	public static function modelExample(){
		pageHandler::view("modelForm");
	}

	public static function postExample(){
		echo json_encode($_POST);
	}
}	
