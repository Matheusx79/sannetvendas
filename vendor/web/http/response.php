<?php
namespace vendor\web\http;
class response {
    public $rotas = array();
    function __construct($array){
        $this->rotas=$array;
        
    }
    function redirect($path){
        header('location:'.$path);
        
    }
    function pathName($documents){
    

   return base_url.$this->rotas[$documents];
    } 
    
    
    
    
}