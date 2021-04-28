<?php

namespace uranium\cli;


class cliCommandHandler{

	public $classList = array();
	private $commandDirectory = __DIR__."/../cli";

	public function __construct($argv){
		$command = $argv[1];
		$args = [];
		for($i = 2; $i < count($argv)-2; $i++){
			$args[] = $argv[$i];
		};
		echo "Running command: ".$argv[1].PHP_EOL;
		$commandSplit = explode(".", $command);
		if($this->findCommandFile($commandSplit[0])){
			echo PHP_EOL.$commandSplit[0]."::".$commandSplit[1].PHP_EOL;
			$commandClass = new $commandSplit[0];
			$function = $commandSplit[1];
			$commandClass::$function($args);
		}else{
			error_log("Command not found.");
		}

	}

	private function findCommandFile($ctf){
		echo "loading files...".PHP_EOL;
		if(strlen($this->commandDirectory) <= 0){
			return false;
		};
		if(file_exists($this->commandDirectory)){
			echo $this->commandDirectory.PHP_EOL;
			$filesToInclude = scandir($this->commandDirectory);
			unset($filesToInclude[0]); // Remove . and ..
			unset($filesToInclude[1]); // from dir list
			foreach($filesToInclude as $file){
				if(!is_dir($this->commandDirectory.'/'.$file)){
					$file = $this->commandDirectory."/".$file;
					$fp = fopen($file, 'r');
					$class = $namespace = $buffer = '';
					$i = 0;
					while (!$class) {
					    if (feof($fp)) break;
					    $buffer .= fread($fp, 512);
					    $tokens = token_get_all($buffer);
					    if (strpos($buffer, '{') === false) continue;
					    for (;$i<count($tokens);$i++) {
					        if ($tokens[$i][0] === T_NAMESPACE) {
					            for ($j=$i+1;$j<count($tokens); $j++) {
					                if ($tokens[$j][0] === T_STRING) {
					                     $namespace .= '\\'.$tokens[$j][1];
					                } else if ($tokens[$j] === '{' || $tokens[$j] === ';') {
					                     break;
					                };
					            };
					        };
					        if ($tokens[$i][0] === T_CLASS) {
					            for ($j=$i+1;$j<count($tokens);$j++) {
					                if ($tokens[$j] === '{') {
					                    $class = $tokens[$i+2][1];
					                };
					            };
					        };
					    };
					};
					echo 
					include_once($file);
					$item = [
						"class" 	=> $class, 
						"namespace" => $namespace
					];
					$this->classList[] = $item;
					if($class == $ctf){
						return true;
						break;
					};
				};
			};
			return false;
		};
	}
}