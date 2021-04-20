<?php
/*
 * Get data
 * Save data
 * build database
 */

namespace uranium\core;

use uranium\database\db;

class databaseDataTypes{
	public const VARCHAR = ["ID" => "VARCHAR"];
	public const INTEGER = ["ID" => "INT"];
}

class model extends databaseDataTypes{
	protected $cols = [];
	protected $tableName;
	
	protected function save(){
		// save model
	}	

	protected function addPrimary($name){
		$this->cols[] = [
			"name"=> $name,
			"type"=> databaseDataTypes::INTEGER,
			"constraint" => "PK",
			"length" => 10
		];
	}
	
	protected function addCol($name, $type, $length=10, $default=false){
		$this->cols[] = [
			"name"=> $name,
			"type"=> $type,
			"length"=> $length,
			"default" => $default?$default:"",
			"constraint" => false
		];
	}

	public function get(){
		$tableName = $this->tableName;
		$template = <<<END
			SELECT * FROM $tableName
END;
	}

	public function create(){
		$tableName = $this->tableName;
		$template = <<<EOD
			CREATE TABLE $tableName(
EOD;
		$pkName = "";
		foreach($this->cols as $col){
			if($col["constraint"] === "PK"){
				$pkName = $col["name"];
			}
			$name = $col["name"];
			$type = $col["type"]["ID"];
			$length = $col["length"];
			$template .= <<<EOD
				$name $type($length),
EOD;
	
		}
		$template .= "PRIMARY KEY(".$pkName.")";
		$template .= ")";
		// Check data valid

		echo $template;

		// create command

		// get db instance

		// execute command

		// rollback if error
	}
}