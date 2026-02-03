<?php

namespace uranium\controller;

use uranium\core\PageHandler;

class PageController{
    public static function index(){
        PageHandler::view("example");
    }
}
