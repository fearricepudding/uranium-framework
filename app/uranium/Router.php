<?php
namespace uranium\core;

use uranium\core\routes;
use uranium\controller;
use uranium\core\pageHandler;

class Router{

    private $routeList;
    private $routeRegister;

    public function init(){
        $this->routeRegister = new routes();
        $this->routeList = $this->routeRegister->getRegister();
        $route = $this->getRoute();
        if($route){
            $this->loadRoute($route["route"], $route['variables']);
        }else{
            error_log("[*] Router: Route not found");
            PageHandler::view("error_pages/404");
        }
    }

    private function getRouteList(){
        $routes = $this->routeList;
        $method = $_SERVER["REQUEST_METHOD"];
        if(array_key_exists($method, $routes)){
            return $routes[$method];
        }
        return [];
    }

    private function getRoute(){
        $URI = $_SERVER['REQUEST_URI'];
        $PATH = explode("?", $URI)[0];
        $URIComps = explode("/", $PATH);
        if($URIComps[0] == ""){
            $URIComps = array_slice($URIComps, 1); // Remove first empty item
        };
        $compSize = count($URIComps);
        $matches = $this->getRouteList();
        $variables = [];
        $level = 0; 
        foreach($URIComps as $URIComp){
            foreach($this->getRouteList() as $routePath=>$route){
                $routeComps = explode("/", $routePath); 
                if($routeComps[0] == ""){
                    $routeComps = array_slice($routeComps, 1); // Remove empty array item
                };
                $routeSize = count($routeComps);
                if($compSize != $routeSize){
                    unset($matches[$routePath]);
                }else{
                    $variableMatch = preg_match("/\{([a-zA-Z_]+)\}/", $routeComps[$level], $variableMatch);
                    if($routeComps[$level] != $URIComp){
                        if(!$variableMatch){
                            unset($matches[$routePath]);
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

    private function loadRoute($route, $variables=[]){
        if($route->hasMiddleware()){
            foreach($route->middleware as $middlewareClassString){
                $middlewareClass = new $middlewareClassString();
                $middlewareClass::handle();
            }
        };

        $routeID = $route->getHandler();
        $splitRoute = explode("@", $routeID);
        $controllerClassName = "uranium\\controller\\".$splitRoute[0];
        $class = new $controllerClassName;
        $function = $splitRoute[1];
        $class::$function($variables);
    }
}
