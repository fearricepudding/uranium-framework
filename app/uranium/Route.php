<?php

namespace uranium\core;

class Route {
    public $path = "";
    public $handler = "";
    public $method = "";
    public $middleware = [];
    
    public function __construct(String $method, String $path, String $handler){
        $this->method = $method;
        $this->path = $path;
        $this->handler = $handler;
    }

    public function getRoute(){
        return $this->path;
    }

    public function getMethod(){
        return $this->method;
    }

    public function middleware(string $middleware){
        $this->middleware[] = $middleware;
        return $this;
    }

    public function hasMiddleware(): Bool{
        return (count($this->middleware) > 0);
    }

    public function getHandler(){
        return $this->handler;
    }
}