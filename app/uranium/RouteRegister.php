<?php

namespace uranium\core;
use uranium\core\Route;



class RouteRegister{
    protected $routes = [];

    protected function get(String $route, String $handler){
        $route = new Route("GET", $route, $handler);
        return $route;
    }

    protected function post(String $route, String $handler){
        $route = new Route("POST", $route, $handler);
        return $route;
    }

    protected function delete(){
        $route = new Route("DELETE", $route, $handler);
        return $route;
    }

    protected function put(){
        $route = new Route("PUT", $route, $handler);
        return $route;
    }

    protected function register( Route $route ){
        $this->routes[$route->getMethod()][$route->getRoute()] = $route;
    }

    protected function group(Array $routeGroup, Array $middleWare) {
        foreach($routeGroup as $route) {
            if ($middleWare != NULL) {
                foreach ($middleWare as $mw) {
                    $route->middleware($mw);
                }
            }
            $this->routes[$route->getMethod()][$route->getRoute()] = $route;
        }
    }

    public function getRegister(){
        return $this->routes;
    }
}
