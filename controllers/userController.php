<?php

use uranium\core\pageHandler;
use uranium\core\encryptionEngine;
use uranium\model\UserModel;
use uranium\model\UserDetailsModel;

class userController{
    public static function searchUser(){
        $model = new UserModel();
        $result = array();
        if($model->exists()){
            if($_POST['includeDetails'] === "true"){
                $result = $model->where("username", $_POST['search'])
                            ->with("userDetails")
                            ->withProtected()
                            ->get();
            }else{
                $result = $model->where("username", $_POST['search'])
                            ->withProtected()
                            ->get();
            }
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
            echo "{\"result\":true}";
        }catch(PDOException $e){
            echo "{\"result\":false}";
        }
    }

    public static function updateUser(){
        $model = new UserDetailsModel();
        $newDetails = [
            "user_id"    => $_POST["uid"],
            "first_name" => $_POST["firstName"],
            "last_name"  => $_POST["lastName"]
        ];
        $model->rows[] = $newDetails;
        echo $model->save();

    }
}
