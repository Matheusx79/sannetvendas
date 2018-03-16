<?php
namespace vendor\helpers;

class path 
{
    protected $rotas = array();
    
    function setRoute($name)
    {
        $this->rotas = $name;
        
    }
    function routeName($name)
    {
        if(isset($this->rotas[$name][0])) {
            return base_url . $this->rotas[$name][0];   
        } else {
            return false;
        };
        
    }
    function route($name)
    {
        if($name != '') {
            return base_url .$name;   
        } else {
            return false;
        };        
    }
    
}