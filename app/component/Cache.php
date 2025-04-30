<?php

namespace uranium\component;

use \Memcache;

class Cache{
    private static $_instance;

    public static function getInstance(){
        if(!self::$_instance){
            self::$_instance = new Memcache();
            self::$_instance->connect('localhost', 112110) or die("could not connect to memcached");
        }
    }

    final public static function __callStatic( $charMethod, $arrArguments ){
        $instance = self::getInstance();
        return call_user_func_array(array($_instance, $charMethod), $arrArguments);
    }

    public static function get(String $key):String {
        $instance = self::getInstance();
        return $instance->get($key);
    }

    public static function set(String $key, Mixed $value):void {
        $instance = self::getInstance();
        $instance->set($key, $value, false, 100);
    }
}   
