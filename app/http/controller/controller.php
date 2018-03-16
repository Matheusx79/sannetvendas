<?php
namespace app\http\controller;
class controller {
    public $container= '';
    
    function __construct($a)
    {
       $this->container = $a;
    }
    
    function __get($name)  
    {
        return $this->container->$name;
    }
    
}