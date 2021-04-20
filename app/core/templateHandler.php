<?php

namespace uranium\core;

class templateHandler{

    private static $viewpath = __DIR__."/../../views/";
    private static $templateDir = __DIR__."/../../views/templates/";

    public function getContent($VIEWNAME){
        $viewpath = self::$viewpath;
        $file = $viewpath.$VIEWNAME.'.view.php';
        if(!file_exists($file)){
            return false;
        }
        $viewContent = file_get_contents($file);
        return $viewContent;
    }

    public static function updateData($VIEWDATA, $VARIABLES){
        $match = "/\{\{\S+\}\}/";
        preg_match_all($match, $VIEWDATA, $matches);
        $update = $matches[0];
        $values = [];
        extract($VARIABLES);
        foreach($update as $change){
            $command = substr($change, 2, strlen($change)-4);
            $output = "";
            eval('$output = '.$command.';');
            $values[$change] = $output;
        }
        return str_replace(array_keys($values), array_values($values), $VIEWDATA);
    }

    public static function renderTemplate($newData){
        $templateDir = self::$templateDir;
        $templateEx = "/\@[template]+\([\"\'][a-zA-Z0-9\/]+[\"\']\)/";
        preg_match($templateEx, $newData, $match);
        if(count($match) > 0){
            $template = $match[0];
            preg_match("/[\"\'][a-zA-Z0-9\/]+[\"\']/", $template, $templateFile);
            $filename =  substr($templateFile[0], 1, strlen($templateFile[0])-2);
            $fileLoc = $templateDir.$filename.'.template.php';
            $templaterawcontent = file_get_contents($fileLoc);
            $templateContent = self::insertIncludes($templaterawcontent);
            $readyForData = str_replace($template, "", $newData);
            $new_file = str_replace("@content@", $readyForData, $templateContent);
            return $new_file;
        }else{
            return $newData;
        }
    }

    private static function insertIncludes($CONTENT){
        $insertEx = "/\@[include]+\([\"\'][a-zA-Z0-9\/]+[\"\']\)/";
        preg_match_all($insertEx, $CONTENT, $inclusions);
        foreach($inclusions[0] as $include){
            preg_match("/[\"\'][a-zA-Z0-9\/]+[\"\']/", $include, $includeFile);
            $fileRelPath = substr($includeFile[0], 1, strlen($includeFile[0])-2);
            $fileLoc = self::$viewpath.$fileRelPath.'.inc.php';
            $fileContent = file_get_contents($fileLoc);
            $includeIncludes = self::insertIncludes($fileContent);
            $CONTENT = str_replace($include, $includeIncludes, $CONTENT);
        }
        return $CONTENT;
    }
}