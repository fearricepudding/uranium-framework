<?php
/*
 * Get data
 * Save data
 * build database
 */

namespace uranium\core;

use uranium\database\db;
use \PDO;

class databaseDataTypes{
	public const VARCHAR = ["ID" => "VARCHAR"];
	public const INTEGER = ["ID" => "INT"];
}

class model extends databaseDataTypes{
	protected $cols = [];
	public $rows = [];
	protected $tableName;
	protected $pkn;
	
	protected function addPrimary($name){
		$this->pkn = $name;
		$this->cols[] = [
			"name"=> $name,
			"type"=> databaseDataTypes::INTEGER,
			"constraint" => "PK",
			"length" => 10,
			"default" => "AUTO_INCREMENT",
			"null" => true
		];
	}
	
	protected function addCol($name, $type, $length=10, $default=false, $null=false){
		$this->cols[] = [
			"name"=> $name,
			"type"=> $type,
			"length"=> $length,
			"default" => $default?$default:"",
			"constraint" => false,
			"null" => $null
		];
	}

	/**
	 * Fetch item in database
	 * @param $iid - pk value of item to fetch
	 *
	 * TODO: Allow array of IDS to fetch
	 */
	public function get($iid=false){
		$tableName = $this->tableName;
		$database = db::getInstance();
		$sql = "SELECT * FROM $tableName";
		if($iid){
			$pkn = $this->pkn;
			$sql .= " WHERE `$pkn`='$iid'";
		}
		$query = $database->prepare($sql);
		if($query->execute()){
			while($row = $query->fetch(PDO::FETCH_ASSOC)){
				$this->rows[] = $row;
			}
			return true;
		}else{
			return false;
		};
	}
 
	public function save(){
		if(count($this->rows) <= 0){
			return;
		};
		$pkn = $this->pkn;
		$tableName = $this->tableName;
		$database = db::getInstance();
		$database->beginTransaction();
		foreach($this->rows as $row){
			$template = "";
			if(array_key_exists($pkn, $row)){
				// pk is set and exists in db
				$template = "UPDATE `$tableName` SET ";
				foreach($this->cols as $col){
					if(array_key_exists($col["name"], $row)){
						$currentKey = $col["name"];
						$currentValue = $row[$col["name"]];
						$template .= "`$currentKey`='$currentValue',";
					}
				}
				$template = substr($template, 0, -1);
				$currentPK = $row[$pkn];
				$template .= " WHERE `$pkn`='$currentPK'";
			}else{
				$values = "";
				$template = "INSERT INTO `$tableName` (";
				foreach($this->cols as $col){
					if($col["constraint"] != "PK"){
						if(array_Key_exists($col["name"], $row)){
							$currentKey = $col["name"];
							$currentValue = $row[$col["name"]];
							$template .= "`$currentKey`,";
							$values .= "'$currentValue',";
						}
					}
				}
				$template = substr($template, 0, -1); // Remove the final comma from keys
				$values = substr($values, 0, -1); // And on values
				$template .= ") VALUES (".$values.");";
			}
			$database->exec($template);
		}
		try{
			$database->commit();
			return true;
		}catch(PDOException $e){
			$database->rollBack();
			return false;
		}
	}
	
	/**
	 * Crete the table in the database
	 */
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
			$null = $col["null"]?"NOT NULL":"";
			$name = $col["name"];
			$type = $col["type"]["ID"];
			$length = $col["length"];
			$default = $col["default"];
			$template .= <<<EOD
				$name $type($length) $null $default,
EOD;
	
		}
		$template .= "PRIMARY KEY(".$pkName.")";
		$template .= ")";

		// get db instance
		$database = db::getInstance();
		// execute command
		$query = $database->prepare($template);
		// rollback if error
		if($query->execute()){
			return true;
		}else{
			return false;
		}
	}

	// TODO: Throws an error if the table doesnt exist, want to just return
	public function exists(){
		$database = db::getInstance();
		$query = $database->prepare("DESCRIBE ".$this->tableName);
		if(!$query->execute()){
			// There was an issue getting the table
			return false;
		};
		return true;
	}

	public function drop(){
		$database = db::getInstance();
		$tableName = $this->tableName;
		$sql = "DROP TABLE IF EXISTS `$tableName`;";
		$query = $database->prepare($sql);
		if($query->execute()){
			return true;
		}else{
			return false;
		};
	}
}
