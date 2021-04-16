<?php
namespace uranium\bootstrap;

class router extends \routes{

    public static function init(){
        $route = self::getRoute();
        if($route){
            self::loadRoute($route["route"], $route['variables']);
        }else{
            echo "404";
        }
    }

    private static function getRoute(){
		$URI = $_SERVER['REQUEST_URI'];
		$URIComps = explode("/", $URI);
		if($URIComps[0] == ""){
			$URIComps = array_slice($URIComps, 1); // Remove first empty item
		};
		$compSize = count($URIComps);
		if($URIComps[$compSize-1] == ""){
			unset($URIComps[$compSize-1]); // Remove last if last is empty
			$compSize -= 1;
		}
		$matches = self::$public_routes;
		$variables = [];
		$level = 0; 
		foreach($URIComps as $URIComp){
			foreach(self::$public_routes as $route=>$controller){
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
