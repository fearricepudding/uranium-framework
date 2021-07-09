<?php

namespace uranium\model;

use uranium\core\Model;
use uranium\core\databaseDataTypes;

class ExampleModel extends Model{

	protected $tableName = "exampleModel";

	public function __construct(){

		$this->addPrimary("id"); 
		$this->addCol("name", [
			"type" 	 => databaseDataTypes::VARCHAR,
			"length" => 20,
			"null" => false
		]);
		$this->addCol("test", [
			"type" => databaseDataTypes::VARCHAR,
			"null" => false,
			"default" => "Something"
		]);
		$this->addCol("userid", [
			"type" => databaseDataTypes::INTEGER,
			"null" => false
		]);
	}	
}
