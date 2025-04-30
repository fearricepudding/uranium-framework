<?php
namespace uranium\cli;

use uranium\core\cli\CliColour;

class Testing{
    public static function echo(){
        echo CliColour::BLUE."Merry Christmas!".PHP_EOL;
        echo CliColour::YELLOW."Testing".PHP_EOL;
        echo CliColour::RESET;
        echo "Test".PHP_EOL;
    }
}