<?php
namespace vendor\facades;
use vendor\helpers\pagination;

class pag{
       public static function __callStatic($method, $arguments)
    {
       return call_user_func_array(array(new pagination, $method), $arguments);
    }
       
    
}