<?php
namespace uranium;

class ScriptLoader{

    /**
     * Load single file
     * @param String file to load
     * @return bool if loaded
     */
    public static function file(String $FILEPATH = ""): Void{
        if(strlen($FILEPATH) <= 0){
            throw new \Exception("File path not defined");
        };
        if(file_exists($FILEPATH)){
            require_once($FILEPATH);
        };
    }

    /**
     * Load all php scripts in a folder
     * @param String path to load scripts from
     * @return bool if path found
     */
    public static function folder(String $FOLDERPATH = "", Bool $recursive = false): Void{
        if(strlen($FOLDERPATH) <= 0){
            throw new \Exception("Folder path not defined");
        };
        if(file_exists($FOLDERPATH)){
            $filesToInclude = scandir($FOLDERPATH);
            foreach($filesToInclude as $file){
                if(!is_dir($FOLDERPATH.'/'.$file)){
                    require_once($FOLDERPATH.'/'.$file);
                }else if(is_dir($FOLDERPATH.'/'.$file) && $recursive){
                    if($file != "." && $file != ".."){
                        self::folder($FOLDERPATH.'/'.$file, true);
                    };
                };
            };
        };
    }
}
