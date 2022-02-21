<?php
namespace uranium\cli;

use uranium\cli\cliColour;

class testing{
    public static function echo(){
        echo cliColour::BLUE."Merry Christmas!".PHP_EOL;
        echo cliColour::YELLOW."Testing".PHP_EOL;
        echo cliColour::RESET;
        echo "Test".PHP_EOL;
    }
}