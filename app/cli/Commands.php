<?php

namespace uranium\cli;

use uranium\core\ClassHelper;
use uranium\core\cli\CliColour;

class Commands{
    public static function list(){
        $classes = ClassHelper::getClassNamesFromDir(__DIR__);
        foreach($classes as $class){
            
            $classNameComps = explode("\\", $class);
            $length = sizeof($classNameComps)-1;
            $className = $classNameComps[$length];

            echo CliColour::BLUE.$className.PHP_EOL;
            echo CliColour::RESET;
            $methods = get_class_methods($class);
            foreach($methods as $method){
                echo "> ".$method.PHP_EOL;
            }
            echo PHP_EOL;
        }
    }
}
