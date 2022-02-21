<?php

namespace uranium\model;

use uranium\core\Model;
use uranium\core\databaseDataTypes as type;
use uranium\model\UserDetailsModel;

class UserModel extends Model{

    protected $tableName = "user";

    public function __construct(){

        $this->addPrimary("id");
        $this->addCol("username",[
            "type" 	 => type::VARCHAR,
            "length" => 21,
            "null"   => false,
            "unique" => true
        ]);
        $this->addCol("email", [
            "type" 	 => type::VARCHAR,
            "length" => 50,
            "null"	 => false,
            "unique" => true
        ]);
        $this->addCol("password", [
            "type"	 => type::VARCHAR, 
            "length" => 72,
            "null"   => false,
            "protected" => true
        ]); 
    }	
    public function userDetails(){
        $this->hasOne(UserDetailsModel::class);
    }
}
