<?php
namespace uranium\core;

class ClassHelper{

    // Get class list for given directory
    public static function getClassNamesFromDir($dir){
        $classList = array();
        $fileList = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir));
        $phpFiles = new \RegexIterator($fileList, '/\.php$/');
        foreach($phpFiles as $phpFile){
            $content = file_get_contents($phpFile->getRealPath());
            $tokens = token_get_all($content);
            $namespace = "";
            for($index = 0; isset($tokens[$index]); $index++){
                if(!isset($tokens[$index][0])){
                    continue;
                };
                if(T_NAMESPACE === $tokens[$index][0]){
                    $index += 2;
                    while(isset($tokens[$index]) && is_array($tokens[$index])){
                        $namespace .= $tokens[$index++][1];
                    };
                };
                if(T_CLASS === $tokens[$index][0] 
                    && T_WHITESPACE === $tokens[$index+1][0] 
                    && T_STRING === $tokens[$index+2][0]){
                    $index += 2;
                    $classList[] = $namespace."\\".$tokens[$index][1];
                    break;
                };
            };
        };
        return $classList;
    }
}