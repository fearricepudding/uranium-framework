<?php

use dark\bootstrap\pageHandler;
use dark\bootstrap\userHandler;

class exampleController{
	public static function examplepage(){
		pageHandler::view("example");
	}
	public static function userexample(){
		var_dump(userHandler::loginUser("jordan", "password"));
	}
}	
