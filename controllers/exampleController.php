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
		$exampleModel = new exampleModel();
		$userModel = new userModel();
//		$test->drop();
//		$test->create();

		if($exampleModel->exists()){ // Check the table exists before query

			// Get with selectors and relationship
			$data = $exampleModel->where("test", "example")
			->get();

			// Idea 1 
			$data = $exampleModel->where("test", "example")
			->with(["username", "email"], "users")
			->get();

			// Idea 2
			$data = $exampleModel->where("test", "example")
			->join("relationshipName")
			->get();

			foreach($data as $row){
				foreach($row as $key=>$value)
				echo "$key: $value <br />";
				echo "<br />";
			}

			// $newExampleData=[
			// 	"test" => "example",
			// 	"name" => "Jordan",
			// 	"userid" => 1
			// ];
			// $exampleModel->rows[] = $newExampleData; 
			// $exampleModel->save();

			// $newUserData = [
			// 	"username" => "fearricepudding",
			// 	"email"	   => "jordanrandles@googlemail.com",
			// 	"password" => "SecretPassword"
			// ];
			// $userModel->rows[] = $newUserData;
			// $userModel->save();
		}

		
		echo PHP_EOL."DONE.".PHP_EOL;
	}
}	
