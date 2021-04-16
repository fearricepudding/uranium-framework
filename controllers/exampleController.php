<?php

use uranium\bootstrap\pageHandler;
use uranium\bootstrap\userHandler;


class exampleController{
	public static function examplepage(){
		pageHandler::view("example");
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
		
		//var_dump(userHandler::logoutUser());
		//var_dump(userHandler::loginUser("jordan", "password"));

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
}	
