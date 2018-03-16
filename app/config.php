<?php
define('pasta',substr($_SERVER['PHP_SELF'],0,-10) );
define('di',$_SERVER['DOCUMENT_ROOT'].pasta);
define('base_url', "http://" .  $_SERVER['SERVER_NAME'] .substr($_SERVER['PHP_SELF'],0,-10));

