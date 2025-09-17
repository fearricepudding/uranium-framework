<?php

namespace uranium\core;
use uranium\core\Route;



class RouteRegister{
    protected $routes = [];
    protected $prefix = "";

    protected function get(String $route, String $handler){
        $route = new Route("GET", $this->prefix.$route, $handler);
        return $route;
    }

    protected function post(String $route, String $handler){
        $route = new Route("POST", $this->prefix.$route, $handler);
        return $route;
    }

    protected function delete(){
        $route = new Route("DELETE", $this->prefix.$route, $handler);
        return $route;
    }

    protected function put(){
        $route = new Route("PUT", $this->prefix.$route, $handler);
        return $route;
    }

    protected function register( Route $route ){
        $this->routes[$route->getMethod()][$route->getRoute()] = $route;
    }

    protected function setPrefix(String $prefix) {
        $this->prefix = $prefix;
    }

    protected function endPrefix() {
        $this->prefix = "";
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

 //       var_dump($this->routes);
        return $this->routes;

    }
}
