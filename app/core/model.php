<?php
/*
 * Get data
 * Save data
 * build database
 */

namespace uranium\core;

use uranium\database\db;
use \PDO;
use Throwable;

class databaseDataTypes{
	public const VARCHAR = ["ID" => "VARCHAR", "MAX" => 255];
	public const INTEGER = ["ID" => "INT", "MAX" => 100];
	public const BOOLEAN = ["ID" => "BOOL", "MAX" => 1];
	public const TEXT    = ["ID" => "TEXT", "MAX" => 2000];
}

class Model extends databaseDataTypes{

	public $cols = array();  	// Array of data columns
	public $rows = array(); 	// Array of data from database
	protected $tableName;	
	protected $pkn;				// Primary key name
	private $withProtected = false; 
	private	$query = ["selectors" => array(),
					  "relationships" => array()];		// Build a query

	public function __construct(){
		$this->setupQuery(); // Setup the default empty query
	}

	/**
	 * Valid col options
	 */
	private $colOptions = [
		"name" => "",
		"type" => NULL,
		"length" => 10,
		"default" => NULL,
		"key" => "",
		"null" => true,
		"auto_increment" => false,
		"extra" => false,
		"unique" => false,
		"protected" => false
	];
	
	/**
	 * Adds an auto incrementing primary key
	 * 		with specified name
	 * 
	 * @param String - Name of the primary key
	 */
	protected function addPrimary(String $name): void{
		$this->pkn = $name;
		$this->cols[] = [
			"name"=> $name,
			"type"=> databaseDataTypes::INTEGER,
			"key" => "PRI",
			"length" => 10,
			"extra" => "AUTO_INCREMENT",
			"null" => false,
			"default" => NULL,
			"unique" => true,
			"protected" => false
		];
	}

	/**
	 * setup the default empty query
	 * (put here in case of change later on)
	 */
	private function setupQuery(): void{
		$this->query = ["selectors" => array(),
					  "relationships" => array()];
	}
	
	/**
	 * Add a data column to the table
	 * 		values are specified by colOptions
	 * 
	 * @param String - name of col
	 * @param Array  - array of col options
	 */
	protected function addCol(string $name, array $options): void{
		$newcol = $this->colOptions;
		$newcol["name"] = $name;
		foreach($this->colOptions as $key=>$option){
			if(array_key_exists($key, $options)){
				$newcol[$key] = $options[$key];
			}
		}
		$this->cols[] = $newcol;
	}

	/**
	 * Add where selector to query
	 * 
	 * @param String - column name to filter
	 * @param String - value to filter
	 * @return model object
	 */
	public function where(String $key, String $value): Model{
		$this->query["selectors"][] = ["key" => $key, "value" => $value]; 
		return $this;
	}

	/**
	 * add a limit to ammount of rows to fetch
	 * 
	 * @param Int limit to rows
	 * @return model object
	 */
	public function limit(Int $limit): Model{
		$this->query["limit"] = $limit;
		return $this;
	}

	private function setupRelationship($className, $foreignKey, $localKey): array{
		$defaultForeignKey = $this->tableName."_id";
		$class = new $className();
		$newRelationship = [
			"class"			=> new $className(),
			"localKey" 		=> ($localKey!=null)?$localKey:$this->pkn,
			"foreignKey"	=> ($foreignKey!=null)?$foreignKey:$defaultForeignKey
		];
		return $newRelationship;
	}

	/**
	 * Add a one-to-one relationship
	 * @param string modelname
	 * @return Model - this
	 */
	public function hasOne(String $className, String $foreignKey=null, String $localKey=null): void{
		$newRelationship = $this->setupRelationship($className, $foreignKey, $localKey);
		$newRelationship["class"]->limit(1);
		$this->query["relationships"][] = $newRelationship; 
	}

	public function hasMany(String $className, String $foreignKey=null, String $localKey=null): void{
		$newRelationship = $this->setupRelationship($className, $foreignKey, $localKey);
		$this->query["relationships"][] = $newRelationship; 
	}

	public function with(String $relationshipName){
		$this->$relationshipName(); //?
		return $this;
	}

	public function withProtected(){
		$this->withProtected = true;
		return $this;
	}

	/**
	 * Fetch item in database
	 * 
	 * @return mixed
	 */
	public function get(): array{
		$tableName = $this->tableName;
		$database = db::getInstance();
		$sql = "SELECT ";
		$cols = $this->getFieldNames();
		foreach($cols as $key=>$col){
			$sql .= $col;
			if(($key+1) != count($cols)){
				$sql.=", ";
			};
		};
		$sql .= " FROM $tableName";
		if(count($this->query["selectors"]) > 0){
			$sql .= " WHERE ";
			foreach($this->query["selectors"] as $key=>$selector){
				if($key >= 1)
					$sql .= " AND ";
				$sql .= "`".$selector["key"]."`='".$selector["value"]."'";
			}
		}
		if(key_exists("limit", $this->query)){
			$sql .= " LIMIT ".$this->query["limit"];
		}
		$query = $database->prepare($sql);
		if($query->execute()){
			while($row = $query->fetch(PDO::FETCH_ASSOC)){
				// Get relationships
				if(count($this->query["relationships"]) > 0){
					foreach($this->query["relationships"] as $r){
						$relationshipModel = $r["class"];
						$results = $relationshipModel->where($r["foreignKey"], $row[$r["localKey"]])->get();
						$row[$relationshipModel->tableName] = $results;
					}
				}
				$this->rows[] = $row;
			}
			return $this->rows;
		}else{
			return [];
		};
	}

	private function getFieldNames(){
		$cols = array();
		foreach($this->cols as $col){
			if($this->withProtected || !$col["protected"]){
				$cols[] = $col["name"];
			}
		}
		return $cols;
	}
 
 	/**
 	 * Save new data in rows to database
 	 * 
 	 * @return Bool status
 	 */
	public function save(): bool{
		if(count($this->rows) <= 0){
			return true;
		};
		$pkn = $this->pkn;
		$tableName = $this->tableName;
		$database = db::getInstance();
		$database->beginTransaction();
		foreach($this->rows as $row){
			$template = "";
			if(array_key_exists($pkn, $row)){
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
					if($col["key"] != "PRI"){
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
			$database->rollBack(); // We messed up, go back.
			return false;
		}
	}
	
	/**
	 * Crete the table in the database
	 * 
	 * @return Bool status
	 */
	public function create(): bool{
		$tableName = $this->tableName;
		$template = "CREATE TABLE $tableName(";
		$pkName = "";
		foreach($this->cols as $col){
			if($col["key"] === "PRI"){
				$pkName = $col["name"];
			}
			$null = $col["null"]?"":"NOT NULL";
			$name = $col["name"];
			$type = $col["type"]["ID"];
			$length = $col["length"];
			$default = $col["default"];
			$extra = $col["extra"]?$col["extra"]:"";

			$template .= "$name $type($length) $null";
			if(strlen($default) > 0){
				$template .= " default '$default' ";
			}
			if(strlen($extra) > 0){
				$template .= " $extra ";
			}
			if($col["unique"]){
				$template .= " UNIQUE "; 
			}
			$template .= ',';
		}
		$template .= "PRIMARY KEY(".$pkName.")";
		$template .= ")";
		$database = db::getInstance();
		$query = $database->prepare($template);
		if($query->execute()){
			return true;
		}else{
			return false;
		}
	}

	/**
	 * Checks if database table exists
	 * 
	 * @return Boolean 
	 */
	public function exists(): bool{
		$database = db::getInstance();
		$query = $database->prepare("DESCRIBE ".$this->tableName);
		try{
			$query->execute();
			return true;
		}catch(Throwable $e){
			// Error, table probably doesnt exist.
			return false;
		}
	}

	/**
	 * Drop table
	 * 
	 * @return Boolean
	 */
	public function drop(): bool{
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

	/**
	 * Get tables fields from the database
	 *
	 * @return Mixed
	 */
	public function getFields(): mixed{
		$database = db::getInstance();
		$tableName = $this->tableName;
		$query = $database->prepare("SHOW COLUMNS FROM $tableName;");
		$rows = [];
		if($query->execute()){
			while($row = $query->fetch(PDO::FETCH_ASSOC)){
				$rows[] = $row;
			}
			return $rows;
		}else{
			return false;
		};
	}
}
