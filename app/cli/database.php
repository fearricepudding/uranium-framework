<?php

namespace uranium\cli;

use uranium\core\classHelper;
use uranium\cli\cliHelper;

class database{
	public static function recreate(){
		echo "[*] === Database Migration ===".PHP_EOL;
		echo "[*] Readaing models...".PHP_EOL;
		$modelList = classHelper::getClassNamesFromDir(__DIR__."/../../models");
		echo "[*] Models found".PHP_EOL;
		echo "---------------------".PHP_EOL;
		foreach($modelList as $model){
			echo $model.PHP_EOL;
		}
		cliHelper::confirmation("Recreating the database will wipe all data.", false);
		echo "--------------------".PHP_EOL;
		echo "[*] Starting migration".PHP_EOL;
		foreach($modelList as $model){
			$modelObj = new $model();
			$modelObj->drop();
			$modelObj->create();
			unset($modelObj); // Destroy finished object
		}
	}
}