<?php

namespace uranium\cli;

class cliHelper{
	public static function confirmation(String $warning, Bool $continueByDefault = false){
    	echo PHP_EOL;
		echo $warning.PHP_EOL;
		echo "Are you sure you want to continue?".PHP_EOL;
		echo "Continue? ";
		echo $continueByDefault?"[Y/n] ":"[y/N] ";
		$confirmation = fread(STDIN, 5);
		$confirmation = trim($confirmation);
		if($confirmation !== "y" && $confirmation !== "Y"){
			if($confirmation === "" && !$continueByDefault){
				echo "[*] Aborting".PHP_EOL;
				exit();
			};
		};
		echo PHP_EOL;
    }
}