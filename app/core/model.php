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
	public const VARCHAR = ["ID" => "VARCHAR", "MAX" => 255];
	public const INTEGER = ["ID" => "INT", "MAX" => 100];
	public const BOOLEAN = ["ID" => "BOOL", "MAX" => 1];
	public const TEXT    = ["ID" => "TEXT", "MAX" => 2000];
}

class model extends databaseDataTypes{

	public $cols = array();  	// Array of data columns
	public $rows = array(); 	// Array of data from database
	protected $tableName;	
	protected $pkn;				// Primary key name
	private	$query = ["selectors" => []];	// Build a query

	/**
	 * Valid col options
	 */
	private $colOptions = [
		"name" => "",
		"type" => null,
		"length" => 10,
		"default" => "",
		"constraint" => null,
		"null" => true,
		"auto_increment" => false,
		"flag" => false
	];
	
	/**
	 * Adds an auto incrementing primary key
	 * 		with specified name
	 * 
	 * @param String - Name of the primary key
	 */
	protected function addPrimary(String $name){
		$this->pkn = $name;
		$this->cols[] = [
			"name"=> $name,
			"type"=> databaseDataTypes::INTEGER,
			"constraint" => "PK",
			"length" => 10,
			"flag" => "AUTO_INCREMENT",
			"null" => true,
			"default" => false
		];
	}
	
	/**
	 * Add a data column to the table
	 * 		values are specified by colOptions
	 * 
	 * @param String - name of col
	 * @param Array  - array of col options
	 */
	protected function addCol(string $name, array $options){
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
	 * 
	 * @return model object
	 */
	public function where(String $key, String $value){
		$this->query["selectors"][] = ["key" => $key, "value" => $value]; 
		return $this;
	}

	/**
	 * add a limit to ammount of rows to fetch
	 * 
	 * @param Int limit to rows
	 * 
	 * @return model object
	 */
	public function limit(Int $limit){
		$this->query["limit"] = $limit;
		return $this;
	}

	/**
	 * Fetch item in database
	 * 
	 * @return Bool status
	 */
	public function get(){
		$tableName = $this->tableName;
		$database = db::getInstance();
		$sql = "SELECT * FROM $tableName";
		if(count($this->query["selectors"]).length > 0){
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
				$this->rows[] = $row;
			}
			return true;
		}else{
			return false;
		};
	}
 
 	/**
 	 * Save new data in rows to database
 	 * 
 	 * @return Bool status
 	 */
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
	 * 
	 * @return Bool status
	 */
	public function create(){
		$tableName = $this->tableName;
		$template = "CREATE TABLE $tableName(";
		$pkName = "";
		foreach($this->cols as $col){
			if($col["constraint"] === "PK"){
				$pkName = $col["name"];
			}
			$null = $col["null"]?"":"NOT NULL";
			$name = $col["name"];
			$type = $col["type"]["ID"];
			$length = $col["length"];
			$default = $col["default"]?$col["default"]:"";
			$flag = $col["flag"]?$col["flag"]:"";

			$template .= "$name $type($length) $null";
			if(strlen($default) > 0){
				$template .= " default '$default' ";
			}
			if(strlen($flag) > 0){
				$template .= " $flag ";
			}
			$template .= ',';
		}
		$template .= "PRIMARY KEY(".$pkName.")";
		$template .= ")";
		
		// echo $template;
		// exit;

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
		try{
			$query->execute();
			return true;
		}catch(Throwable $e){
			error_log("Failed to get table");
			return false;
		}
	}

	/**
	 * Drop table
	 * 
	 * @return Bool status
	 */
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
