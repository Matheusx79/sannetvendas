<?php

class ClassAutoloader
{
    public function __construct()
    {
        spl_autoload_register(array(
            $this,
            'loader_class'
        ));
    }
    private function loader_class($className)
    {
       
         require_once(str_replace( '\\', DIRECTORY_SEPARATOR,di.'/'.$className . '.php'));
    }
    
}

$autoloader = new ClassAutoloader();