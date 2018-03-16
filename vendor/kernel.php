<?php 
use vendor\graphs\error;
use vendor\web\routes;


function url($documents){
    return base_url.'/public/'.$documents ;
}   
function pathFor($documents){
    return base_url.$documents ;
} 

