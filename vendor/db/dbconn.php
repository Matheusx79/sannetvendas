<?php

namespace vendor\db;
use PDO;
class dbconn {
    
    function startConnect(){
        $conn = new PDO('mysql:host=localhost;dbname='.database_name,host_login, host_senha,array( 
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION 
        )); 
        return $conn ;
    }

}