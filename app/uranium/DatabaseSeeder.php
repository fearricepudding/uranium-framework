<?php

namespace uranium\core;

class DatabaseSeeder{
    public static function seedTable($seeder){
        try{
            "\uranium\seeder".$seeder::run();
        }catch(PDOException $e){
            echo "SKIP".PHP_EOL;
        };
    }

    public static function getSeeders(){
        $classes = ClassHelper::getClassnamesFromDir(__DIR__."/../seeder");
        return $classes;
    }
}
