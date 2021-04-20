<?php
namespace uranium\core;

use uranium\core\templateHandler;

class pageHandler{
    
    public static function raw($text){
        self::render($text);
    }

    public static function view($VIEWNAME, $VARIABLES=[]){
        if(templateHandler::getContent($VIEWNAME)){
            $content = templateHandler::getContent($VIEWNAME);
            $pageData = templateHandler::updateData($content, $VARIABLES);
            $inTemplate = templateHandler::renderTemplate($pageData);
            self::render($inTemplate); 
        }else{
            echo 'Template not found';
            error_log("Template not found");
        }
    }

	public static function redirect($location){
		header("Location: ".$location);
		exit();
	}
   
    public static function render($PAGEDATA){
        echo $PAGEDATA;
    }
}
