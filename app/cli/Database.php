<?php

namespace uranium\cli;

use uranium\core\ClassHelper;
use uranium\core\cli\CliHelper;
use uranium\core\cli\CliColour;
use uranium\core\DatabaseSeeder;

class Database{
    /**
     * Delete and recreate the tables from the models
     * @return void
     */
    public static function recreate(): void{
        echo "[*] === Recreating Databases ===".PHP_EOL;
        echo "[*] Reading models...".PHP_EOL;
        $modelList = classHelper::getClassNamesFromDir(__DIR__."/../../models");
        echo PHP_EOL;
        foreach($modelList as $model){
            echo "[*] Found: ".$model.PHP_EOL;
        }
        cliHelper::confirmation("Recreating the database will wipe all data.", false);
        echo "[*] Starting migration".PHP_EOL;
        foreach($modelList as $model){
            $modelObj = new $model();
            $modelObj->drop();
            $modelObj->create();
            unset($modelObj); // Destroy finished object
        }
        echo CliColour::GREEN."[*] Completed without error".CliColour::RESET.PHP_EOL;
    }

    /**
     * Check the integrity of the database compaired to the models
     * @param Boolean - output styled report or array of results
     * @return void
     */
    public static function check(): void{
        $cliReport = true;
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
                        error_log(strtoupper($b["Type"])." - ".strtoupper($modelTypeString));
                        $report["type"] = (strtoupper($b["Type"]) === strtoupper($modelTypeString));
                        $report["null"] = ($a["null"] === false && $b["Null"] === "NO" || 
                                            $a["null"] === true && $b["Null"] === "YES");
                        $report["default"] = ($a["default"] === $b["Default"] || 
                                            $a["default"] === "" && $b["Default"] === NULL);
                        error_log($a["key"]." - ".$b["Key"]);
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
                        echo $checkResult?CliColour::GREEN:CliColour::RED;
                        echo " ".$resultText;
                        echo CliColour::RESET;
                        echo PHP_EOL;
                    }
                    echo PHP_EOL;
                }
            }
            if($errors > 0){
                echo CliColour::RED;
            }else{
                echo CliColour::GREEN;
            }
            echo "Test finished with $errors error(s)".PHP_EOL;
            echo CliColour::RESET;
            echo PHP_EOL;
        }else{
            echo $tableResult;
        };
    }

    public static function seed($args){
        $seeders = DatabaseSeeder::getSeeders();
        foreach($seeders as $seeder){
            DatabaseSeeder::seedTable($seeder);
        };
    }

}
