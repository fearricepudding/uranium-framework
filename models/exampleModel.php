<?php

namespace uranium\model;

use uranium\core\model;
use uranium\core\databaseDataTypes;

class exampleModel extends model{

	public function __construct(){
		$this->addPrimary("id");
		$this->addCol("test", databaseDataTypes::VARCHAR, 50);
	}

	protected $tableName = "exampleModel";
	
}
