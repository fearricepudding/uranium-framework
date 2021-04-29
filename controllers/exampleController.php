<?php

use uranium\core\pageHandler;
use uranium\core\userHandler;
use uranium\core\encryptionEngine;
use uranium\model\exampleModel;

class exampleController{
	
	public static function examplepage(){

		$test = new exampleModel();
		$test->drop();
//		$test->create();
		try{
			echo $test->exists();
			$test->get();
			var_dump($test->cols);
		}catch(PDOException $e){
			echo "Table doesnt exist... creating table";
			$test->create();
		}
	//	var_dump($test->cols);
		exit;

		try{
		//	echo "Dropping table... <br />";
		//$test->create();
		//	echo "Creating table... <br />";
		//	echo "Getting values... <br />";
//		$test->get();
		//	foreach($test->rows as $value){
		//		var_dump($value);
		//		echo "<br />";
		//	}
		//	echo "<br />Setting new value... <br />";
//			$newRow=[];
//			$newRow["test"] = "example manya";
//			$newRow["name"] = "Someone";
//
//	//		$test->rows[] = $newRow; 
//			$test->rows[0]["name"] = "Modified Name";
//			$test->rows[1]["name"] = "Row 2";
//			$test->rows[2]["name"] = "row 3";
//			$test->save();
		}catch(PDOException $e){
			echo $e;
			echo "Failure";
		}
		$testData = ["first" => "firstItem", "second"=>"secondItem"];
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
}	
