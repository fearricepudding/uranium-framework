<?php

namespace uranium\core;

class TemplateHandler{

    private static $viewpath = __DIR__."/../../views/";

    public static function getContent($VIEWNAME, $VARIABLES){
        $viewpath = self::$viewpath;
        $file = $viewpath.$VIEWNAME;
        if(!file_exists($file)){
            return false;
        }
        $config = ConfigHandler::getInstance();
        $VARIABLES["sitename"] = $config->getValue("sitename");
        $data = [
            "page" => $VARIABLES
        ];
        extract($data);
        ob_start();
        include($file);
        $viewContent=ob_get_contents(); 
        ob_end_clean();
        return $viewContent;
    }

    public static function updateData($VIEWDATA, $VARIABLES){
        $match = "/\{\{\S+\}\}/";
        preg_match_all($match, $VIEWDATA, $matches);
        $update = $matches[0];
        $values = [];
        // add site meta from config
        $config = ConfigHandler::getInstance();
        $VARIABLES["sitename"] = $config->getValue("sitename");
        $data = [
            "page" => $VARIABLES
        ];
        extract($data);
        foreach($update as $change){
            $command = substr($change, 2, strlen($change)-4);
            $output = "";
            eval('$output = '.$command.';');
            $values[$change] = $output;
        }
        return str_replace(array_keys($values), array_values($values), $VIEWDATA);
    }

    public static function renderTemplate($newData, $VARIABLES){
        $templateEx = "/\@[template]+\([\"\'][a-zA-Z0-9\/\_\.\-]+[\"\']\)/";
        preg_match($templateEx, $newData, $match);
        if(count($match) > 0){
            $template = $match[0];
            preg_match("/[\"\'][a-zA-Z0-9\/\-\_\.]+[\"\']/", $template, $templateFile);
			if(count($templateFile) <= 0){
				throw new ErrorException("Template path \"".$template."\" not found");
			};
            $filename =  substr($templateFile[0], 1, strlen($templateFile[0])-2);
            $fileLoc = self::$viewpath.$filename;
            $templaterawcontent = self::getContent($filename, $VARIABLES);
            // Insert includes for page
            $newData = self::insertIncludes($newData, $VARIABLES);
            $readyForData = str_replace($template, "", $newData);
            // Insert includes for template
            $templateContent = self::insertIncludes($templaterawcontent, $VARIABLES);
            $new_file = str_replace("@content@", $readyForData, $templateContent);
            return $new_file;
        }else{
            return $newData;
        }
    }

    private static function insertIncludes($CONTENT, $VARIABLES){
        $insertEx = "/\@[include]+\([\"\'][a-zA-Z0-9\-\_\/\.]+[\"\']\)/";
        preg_match_all($insertEx, $CONTENT, $inclusions);
        foreach($inclusions[0] as $include){
            preg_match("/[\"\'][a-zA-Z0-9\/\_\-\.]+[\"\']/", $include, $includeFile);
            $fileRelPath = substr($includeFile[0], 1, strlen($includeFile[0])-2);
            $fileContent = self::getContent($fileRelPath, $VARIABLES);
            $includeIncludes = self::insertIncludes($fileContent, $VARIABLES);
            $CONTENT = str_replace($include, $includeIncludes, $CONTENT);
        }
        return $CONTENT;
    }
}
