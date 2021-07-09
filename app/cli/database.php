<?php

namespace uranium\cli;

use uranium\core\classHelper;
use uranium\cli\cliHelper;
use uranium\cli\cliColour;

class database{
	/**
	 * Delete and recreate the tables from the models
	 */
	public static function recreate(){
		echo "[*] === Recreating Databases ===".PHP_EOL;
		echo "[*] Reading models...".PHP_EOL;
		$modelList = classHelper::getClassNamesFromDir(__DIR__."/../../models");
		echo PHP_EOL;
		foreach($modelList as $model){
			echo $model.PHP_EOL;
		}
		cliHelper::confirmation("Recreating the database will wipe all data.", false);
		echo "[*] Starting migration".PHP_EOL;
		foreach($modelList as $model){
			$modelObj = new $model();
			$modelObj->drop();
			$modelObj->create();
			unset($modelObj); // Destroy finished object
		}
		echo cliColour::GREEN."[*] Completed without error".cliColour::RESET.PHP_EOL;
	}

	/**
	 * Check the integrity of the database compaired to the models
	 * 
	 * @param Boolean - output styled report or array of results
	 * @return Mixed
	 */
	public static function check($cliReport = true){
		echo "[*] === Verifying database ===".PHP_EOL;
		echo "[*] Reading models...".PHP_EOL;
		echo PHP_EOL;
		$modelList = classHelper::getClassNamesFromDir(__DIR__."/../../models");
		$tableResult = [];
		foreach($modelList as $model){
			$modelObj = new $model();
			$databaseFields = $modelObj->getExistingColumns();
			$tableResult[$model] = [];
			foreach($modelObj->cols as $a){
				$report = [];
				$report["exists"] = false;
				foreach($databaseFields as $b){
					if($a["name"] == $b["Field"]){
						$report["exists"] = true;
						$modelTypeString = $a["type"]["ID"]."(".$a["length"].")";
						$report["type"] = (strtoupper($b["Type"]) === strtoupper($modelTypeString));
						$report["null"] = ($a["null"] === false && $b["Null"] === "NO" || 
											$a["null"] === true && $b["Null"] === "YES");
						$report["default"] = ($a["default"] === $b["Default"] || 
											$a["default"] === "" && $b["Default"] === NULL);
						$report["key"] = ($a["key"] === $b["Key"]);
						$report["extra"] = (strtoupper($a["extra"]) === strtoupper($b["Extra"]));
					}
				}
				$tableResult[$model][$a["name"]] = $report;
			}
		}
		if($cliReport){
			$errors = 0;
			foreach($tableResult as $table=>$report){
				echo $table.PHP_EOL;
				foreach($report as $fieldName=>$result){
					echo " ".$fieldName.PHP_EOL;
					foreach($result as $checkKey=>$checkResult){
						if(!$checkResult)
							$errors++;
						echo " - ".$checkKey." ";
						$resultText = $checkResult?"OK":"FAIL";
						$spaceBetween = 20-(strlen($checkKey));
						for($i = 0; $i < $spaceBetween; $i++){
							echo " ";
						}
						echo $checkResult?cliColour::GREEN:cliColour::RED;
						echo " ".$resultText;
						echo cliColour::RESET;
						echo PHP_EOL;
					}
					echo PHP_EOL;
				}
			}
			if($errors > 0){
				echo cliColour::RED;
			}else{
				echo cliColour::GREEN;
			}
			echo "Test finished with $errors error(s)".PHP_EOL;
			echo cliColour::RESET;
			echo PHP_EOL;
		}else{
			var_dump($tableResult);
		};
	}
}