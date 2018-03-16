<?php
namespace vendor\helpers;

class response 
{
    function redirect($name,$response=null)
    {
       
        header('location:'.$name);
        
    }
    
    function changeCode($code)
    {
        http_response_code($code);
    }

    
}