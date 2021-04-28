<?php

namespace uranium\cli;

class cliCommandHandler{

	public $classList = array();
	private $commandDirectory = __DIR__."/../cli";
	private $argv;

	public function __construct($argv){
		$this->argv = $argv;
		if(!isset($argv[1])){
			error_log("Usage: php uranium domain.command args");
			exit;
		};
		$commandSplit = explode(".", $argv[1]);

		cliCommandHandler::runCommand($this->argv[1]);

	}

	// TODO: Not catching errors
	private static function runCommand($cmd){
        if(empty($cmd)){
            return false;
        };
        $splitRoute = explode(".", $cmd);
//        $class = new $splitRoute[0];
        $function = $splitRoute[1];
        try{
        	//$class::$function($variables);
		testing::echo();
		}catch(Error $e){
			echo 'Very nice way to catch Exception and Error exceptions';
		}
    }

	private function findCommandFile($ctf){
		if(strlen($this->commandDirectory) <= 0){
			return false;
		};
		if(file_exists($this->commandDirectory)){
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
					$item = [
						"class" 	=> $class, 
						"namespace" => $namespace
					];
					$this->classList[] = $item;
				};
			};
			return false;
		};
	}
}
