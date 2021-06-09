<?php

namespace uranium\cli;

class cliCommandHandler{

	public $classList = array();
	private $commandDirectory = __DIR__."/../cli";
	private $argv;

	public function __construct($argv){
		$this->argv = $argv;
		if(!isset($argv[1])){
			self::commandNotFound();
		};
		$commandSplit = explode(".", $argv[1]);
		cliCommandHandler::runCommand($this->argv[1]);

	}

	// TODO: Not catching errors
	private static function runCommand($cmd){
        if(empty($cmd)){
            return false;
        };
        echo "[*] Running: ".$cmd.PHP_EOL;
        echo "----".PHP_EOL;
        $splitRoute = explode(".", $cmd);
        $class = "\uranium\cli\\".$splitRoute[0];
        if(class_exists($class)){
        	$classObj = new $class();
        	$method = $splitRoute[1];
	        if(method_exists($classObj, $method)){
		        $class::$method();
		    }else{
		    	self::commandNotFound();
		    };
		}else{
			self::commandNotFound();
		};
    }

    private static function commandNotFound(){
    	error_log("[*] Command not found.");
    	error_log("[*] Usage: php uranium domain.command args");
    	error_log("[*] Use php uranium commands.list for a list of commands.");
    	exit;
    }

}
