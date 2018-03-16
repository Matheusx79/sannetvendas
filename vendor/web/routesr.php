<?php
namespace vendor\web;
use vendor\web\routes;

class routesr{
       public static function __callStatic($method, $arguments)
    {
       return call_user_func_array(array($routes, $method), $arguments);
    }
    
}