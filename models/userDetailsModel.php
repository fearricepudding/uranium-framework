<?php

namespace uranium\model;

use uranium\core\Model;
use uranium\core\databaseDataTypes as type;
use uranium\model\ExampleModel;

class UserDetailsModel extends Model{
	protected $tableName = "userDetails";

	public function __construct(){
		$this->addPrimary('id');
		$this->addCol("user_id", [
			"type"	 => type::INTEGER,
			"length" => 10,
			"null"	 => false
		]);
		$this->addCol("first_name", [
			"type"	 => type::VARCHAR,
			"length" => 20,
			"null"	 => true
		]);
		$this->addCol("last_name", [
			"type"	 => type::VARCHAR,
			"length" => 20,
			"null"	 => true
		]);
	}
};