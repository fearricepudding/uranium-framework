<?php
namespace uranium\core;

use uranium\core\TemplateHandler;

class PageHandler{
    
    public static function raw($text){
        self::render($text);
    }

    public static function view($VIEWNAME, $VARIABLES=[]){
        $fullViewName = $VIEWNAME.".view.php";
        $content = templateHandler::getContent($fullViewName, $VARIABLES);
        if($content){
            $pageData = templateHandler::updateData($content, $VARIABLES);
            $inTemplate = templateHandler::renderTemplate($pageData, $VARIABLES);
            self::render($inTemplate); 
        }else{
            echo "Template ".$VIEWNAME." not found";
            error_log("[*] PageHandler: Template not found");
        }
    }

    public static function redirect($location){
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: ".$location);
        exit();
    }
   
    public static function render($PAGEDATA){
        echo $PAGEDATA;
    }
}
