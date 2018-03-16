<?php 
namespace vendor\graphs;
class url 
{

    protected $path =di.'/resource/views/';
    
    function setPath($name)
    {
        $path = str_replace('.','/',$name);
        $this->path =di.'/'.$path.'/' ;
    }
    function load($document,$params = array())
    {
        if(!is_array($params)){
            echo 'Parametros so são aceitos em formato de array';
            exit;
        }
       
        $path = $this->path;
        if(file_exists($path.$document)){
            $params = $params;   
            require_once($path.$document);
        } else{

        }
  
   
    }
}