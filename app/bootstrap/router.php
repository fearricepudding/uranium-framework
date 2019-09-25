<?php
namespace uranium\bootstrap;

class router extends \routes{

    private static $URI = "";

    public static function init(){
        self::$URI = $_SERVER['REQUEST_URI'];
        $route = self::getRoute(self::$URI);
        if($route){
            self::loadRoute($route["route"], $route['variables']);
        }else{
            echo "404";
        }
    }

    private static function getRoute($URI){
        if(array_key_exists($URI, self::$routes)){
            return ["route"=>self::$routes[$URI], "variables"=>[]];
            return true;
        }else{
            $splitURI = explode("/", $URI);
            $level = 1;
            $variables = [];
            $catches = [];
            foreach(self::$routes as $route=>$item){
                $splitRoute = explode("/",$route);
                $check = $splitURI[$level];
                if(array_key_exists($level, $splitRoute)){
                    if(preg_match("/\{[a-zA-Z]+\}/",$splitRoute[$level])){
                        $variables[] = $check;
                        $catches[] = $route;
                    }else{
                        if($check === $splitRoute[$level]){
                            $catches[] = $route;
                        }
                        
                    }
                }
            }
            $level++;
            for($i=2;$i < count($splitURI);$i++){
                $previousCatches = $catches;
                $catches = [];
                foreach($previousCatches as $route){
                    $splitRoute = explode("/",$route);
                    if(array_key_exists($level, $splitURI)){
                        $check = $splitURI[$level];
                        if(array_key_exists($level, $splitRoute)){
                            if(preg_match("/\{[a-zA-Z]+\}/",$splitRoute[$level])){
                                $variables[$route][substr($splitRoute[$level], 1, (strlen($splitRoute[$level])-2))] = $check;
                                $catches[] = $route;
                            }else{
                                if($check === $splitRoute[$level]){
                                    $catches[] = $route;
                                }
                            }
                        
                        }
                    }
                }
                $level++;
            }
            if(count($catches) !== 1){
                return false;
            }else{
                return ["route"=>self::$routes[$catches[0]], "variables"=>$variables[$route]];
            }
        }
    }

    private static function loadRoute($routeID, $variables=[]){
        if(empty($routeID)){
            return false;
        }
        $splitRoute = explode("@", $routeID);
        $class = new $splitRoute[0];
        $function = $splitRoute[1];
        $class::$function($variables);
    }
}