<?php
namespace uranium\core;

class router extends \routes{

    public static function init(){
        $route = self::getRoute();
        if($route){
            self::loadRoute($route["route"], $route['variables']);
        }else{
            echo "404";
            error_log("[*] Router: Route not found");
        }
    }

    private static function getRouteList(){
    	if($_SERVER['REQUEST_METHOD'] === "GET"){
    		return self::$get_routes;
    	}else if($_SERVER['REQUEST_METHOD'] === "POST"){
    		return self::$post_routes;
    	};
    	return [];
    }

    private static function getRoute(){
		$URI = $_SERVER['REQUEST_URI'];
		$URIComps = explode("/", $URI);
		if($URIComps[0] == ""){
			$URIComps = array_slice($URIComps, 1); // Remove first empty item
		};
		$compSize = count($URIComps);
		$matches = self::getRouteList();
		$variables = [];
		$level = 0; 
		foreach($URIComps as $URIComp){
			if($level > 0 && $URIComp == ""){
				break;	
			}
			foreach(self::getRouteList() as $route=>$controller){
				$routeComps = explode("/", $route); 
				if($routeComps[0] == ""){
					$routeComps = array_slice($routeComps, 1); // Remove empty array item
				};
				$routeSize = count($routeComps);
				if($compSize != $routeSize){
					unset($matches[$route]);
				}else{
					// Check if variable
					$variableMatch = preg_match("/\{([a-zA-Z_]+)\}/", $routeComps[$level], $variableMatch);
					// Match
					if($routeComps[$level] != $URIComp){
						if(!$variableMatch){
							unset($matches[$route]);
						}else{
							$variableID = substr($routeComps[$level], 1, strlen($routeComps[$level]) -2); 
							$variables[$variableID] = $URIComp;
						};
					};
				};
			};
			$level += 1;
		};
		if(count($matches) > 0){
			return [
				"route" 	=> array_pop($matches),
				"variables" => $variables
			];
		}else{
			return false;
		};
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
