<?php

namespace uranium\core;

class ConfigHandler{

    private $_PATH = "";
    private $_CONFIG = [];
    
    private static $instance = null;

    private function __construct(){
        $configPath = $_SERVER['URANIUM_ENV_CONFIG_PATH'];
        $this->loadConfig($configPath);
    }

    public function getValue($key): string|null{
        if(array_key_exists($key, $this->_CONFIG)){
            return $this->_CONFIG[$key];
        }
        return null;
    }

    private function loadConfig($path): void{
        $content = $this->getFileContent($path);
        $configLines = preg_split("/\r\n|\n|\r/", $content); 
        foreach($configLines as $line){
            $splitComment = preg_split("/#/", $line);
            $notComment = $splitComment[0];
            if(!empty($notComment)){
                $splitItem = explode("=", $notComment);
                $this->_CONFIG[stripslashes(trim($splitItem[0]))] = trim($splitItem[1]);
            };
        };
    }

    private function getFileContent($path): string{
        if(!file_exists($path)){
            throw new \Exception(".env does not exist");
        }
        $content = file_get_contents($path);
        if($content === false){
            return "";
        };
        return $content;
    }

    public static function getInstance(): ConfigHandler{
        if(self::$instance == null){
            self::$instance = new ConfigHandler();
        };
        return self::$instance;
    }
}
