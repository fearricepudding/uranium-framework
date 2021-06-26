<?php

namespace uranium\model;

use uranium\core\model;
use uranium\core\databaseDataTypes;

class userModel extends model{

	protected $tableName = "users";

	public function __construct(){

		$this->addPrimary("id");
		$this->addCol("username",[
			"type" 	 => databaseDataTypes::VARCHAR,
			"length" => 21,
			"null"   => false
		]);
		$this->addCol("email", [
			"type" 	 => databaseDataTypes::VARCHAR,
			"length" => 50,
			"null"	 => false
		]);
		$this->addCol("password", [
			"type"	 => databaseDataTypes::VARCHAR, 
			"length" => 72,
			"null"   => false
		]); 


		$this->addRelationship("tableName", "localID", "foreignID")
	}	
}
