<?php
namespace vendor\graphs;
class error{
    protected $title;
    protected $msg;
    function setTitle($txt){
        $this->title = $txt;
    }
    function setMsg($txt){
        $this->msg = $txt;
    }
    function load(){
        $params= [$this->title,$this->msg];
        require_once (di.'/vendor/graphs/view/error.php');
    }
    function loadCustom($pag){
        view($pag,[$this->title,$this->msg]);
    }
    
}