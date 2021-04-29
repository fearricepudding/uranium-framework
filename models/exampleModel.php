<?php

namespace uranium\model;

use uranium\core\model;
use uranium\core\databaseDataTypes;

class exampleModel extends model{

	protected $tableName = "exampleModel";

	public function __construct(){
		$this->addPrimary("id");
		$this->addCol("name", [
			"type" 	 => databaseDataTypes::VARCHAR,
			"length" => 50,
			"null" => false
		]);
		$this->addCol("test", [
			"type" => databaseDataTypes::VARCHAR,
			"null" => false,
			"default" => "Something"
		]);
	}	
}
