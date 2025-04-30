<?php

namespace uranium\core\cli;

use uranium\core\cli\CliColour;
use Throwable;

class CliCommandHandler{

    public $classList = array();
    private $commandDirectory = __DIR__."/../cli";
    private $argv;

    public function __construct($argv){
        $this->argv = $argv;
        if(!isset($argv[1])){
            self::commandNotFound();
        };
        $commandSplit = explode(".", $argv[1]);
        CliCommandHandler::runCommand($this->argv[1], $this->argv);
    }

    /**
     * Find and execute the command 
     * with the correct namespace
     * 
     * @param String - command from CLI
     */
    private static function runCommand($cmd, $args){
        if(empty($cmd)){
            return false;
        };
        echo CliColour::GREEN."[*] Running: ".$cmd.CliColour::RESET.PHP_EOL;
        echo PHP_EOL;
        $splitRoute = explode(".", $cmd);
        $class = "\uranium\cli\\".$splitRoute[0];
        if(class_exists($class)){
            $classObj = new $class();
            $method = $splitRoute[1];
            if(method_exists($classObj, $method)){
                try{
                    $class::$method($args);
                }catch(Throwable $e){
                    echo CliColour::RED;
                    echo "An error occured".PHP_EOL;
                    echo PHP_EOL;
                    echo $e;
                    echo PHP_EOL;
                    echo CliColour::RESET;
                };
            }else{
                self::commandNotFound();
            };
        }else{
            self::commandNotFound();
        };
    }

    /**
     * Default not found error
     * !Stops exec
     */
    private static function commandNotFound(){
        echo CliColour::RED;
        echo "[*] Command not found.".PHP_EOL;
        echo "[*] Usage: php uranium domain.command args".PHP_EOL;
        echo "[*] Use php uranium commands.list for a list of commands.".PHP_EOL;
        echo CliColour::RESET;
        exit;
    }
}
