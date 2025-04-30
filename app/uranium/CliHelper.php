<?php

namespace uranium\core\cli;

use uranium\core\cli\CliColour;

class CliHelper{
    public static function confirmation(String $warning, Bool $continueByDefault = false){
        echo CliColour::YELLOW;
        echo PHP_EOL;
        echo $warning.PHP_EOL;
        echo "Are you sure you want to continue? ";
        echo $continueByDefault?"[Y/n] ":"[y/N] ";
        $confirmation = fread(STDIN, 5);
        $confirmation = trim($confirmation);
        if($confirmation !== "y" && $confirmation !== "Y"){
            if($confirmation === "" && !$continueByDefault){
                echo CliColour::RED;
                echo "[*] Aborting".PHP_EOL;
                echo CliColour::RESET;
                exit();
            };
        };
        echo PHP_EOL;
        echo CliColour::RESET;
    }
}