<?php

namespace uranium\model;

use uranium\core\model;
use uranium\core\databaseDataTypes;

class exampleModel extends model{

	protected $tableName = "exampleModel";

	public function __construct(){
		$this->addPrimary("id");
		$this->addCol("name", databaseDataTypes::VARCHAR, 20);
		$this->addCol("test", databaseDataTypes::VARCHAR, 50);
	}	
}
