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
}	