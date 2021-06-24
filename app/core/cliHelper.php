<?php

namespace uranium\cli;

use uranium\cli\cliColour;

class cliHelper{
	public static function confirmation(String $warning, Bool $continueByDefault = false){
		echo cliColour::YELLOW;
    	echo PHP_EOL;
		echo $warning.PHP_EOL;
		echo "Are you sure you want to continue? ";
		echo $continueByDefault?"[Y/n] ":"[y/N] ";
		$confirmation = fread(STDIN, 5);
		$confirmation = trim($confirmation);
		if($confirmation !== "y" && $confirmation !== "Y"){
			if($confirmation === "" && !$continueByDefault){
				echo cliColour::RED;
				echo "[*] Aborting".PHP_EOL;
				echo cliColour::RESET;
				exit();
			};
		};
		echo PHP_EOL;
		echo cliColour::RESET;
    }
}