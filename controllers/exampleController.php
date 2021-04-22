<?php

use uranium\core\pageHandler;
use uranium\core\userHandler;
use uranium\core\encryptionEngine;
use uranium\model\exampleModel;

class exampleController{
	
	public static function examplepage(){

		$test = new exampleModel();
		try{
		//	echo "Dropping table... <br />";
			//$test->drop();
		//	echo "Creating table... <br />";
			//$test->create();
		//	echo "Getting values... <br />";
		//	$test->get();
		//	//		var_dump($test->rows);
		//	foreach($test->rows as $value){
		//		var_dump($value);
		//		echo "<br />";
		//	}
		//	echo "<br />Setting new value... <br />";
		//	$newRow=[];
		//	$newRow["test"] = "example manya";
		//	$newRow["name"] = "Someone";

		//	$test->rows[] = $newRow; 
		//	$test->rows[1]["name"] = "Modified Name";
		//	$test->save();
		}catch(PDOException $e){
			echo $e;
			echo "Failure";
		}
		//pageHandler::view("example");
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
}	
