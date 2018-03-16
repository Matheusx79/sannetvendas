<?php


require_once('app/config.php');
require_once('vendor/kernel.php');
require_once('app/database/config.php');
require_once('vendor/autoload.php');


$app = new vendor\web\app;

require_once(di.'/routes/web.php');

$app->run();
 


    


