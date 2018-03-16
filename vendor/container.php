<?php 
namespace vendor;
class container 
{
    public $container = array();
    function __construct(){
    $this->loadContainer();
        
    }
    function loadContainer(){
        $this->container['view'] = new \vendor\graphs\url;
        $this->container['path'] = new \vendor\helpers\path;
        $this->container['url'] = new \vendor\helpers\response;
        
        
    }
}