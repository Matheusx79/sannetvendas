<?php
namespace vendor\facades;
use vendor\db\table;

class db{
       public static function __callStatic($method, $arguments)
    {
       return call_user_func_array(array(new table, $method), $arguments);
    }
       
    
}