<?php

namespace uranium\model;

use uranium\core\model;
use uranium\core\databaseDataTypes;

class userModel extends model{

	protected $tableName = "users";

	public function __construct(){
		$this->addPrimary("id");
		$this->addCol("username", databaseDataTypes::VARCHAR, 20);
		$this->addCol("email", databaseDataTypes::VARCHAR, 50);
		$this->addCol("password", databaseDataTypes::VARCHAR, 72); //bcrypt max 64char password length
	}	
}
