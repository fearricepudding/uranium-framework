<?php

use uranium\core\pageHandler;
use uranium\core\encryptionEngine;
use uranium\model\UserModel;

class userController{
	public static function searchUser(){
		$model = new UserModel();
		$result = array();
		if($model->exists()){
			$result = $model->where("username", $_POST['search'])
							->with("userDetails")
						//	->withProtected()
							->get();
		}
		echo json_encode($result);
	}

	public static function createUser(){
		$model = new UserModel();
		$newUser = [
			"username"	=> $_POST['username'],
			"email"		=> $_POST['email'],
			"password"	=> $_POST['password']
		];
		$model->rows[] = $newUser;
		try{
			$result= $model->save();
			echo "{result:true}";
		}catch(PDOException $e){
			echo "{result:false}";
		}
	}
}