<?php
namespace vendor\web\http;
class request
{
    public $gets = array();
    public $posts = array();
    public $requests = array();
    public $serve = array();
    public $uris = array();
    function __construct()
    {
        $this->setPost($_POST);
        $this->setGet($_GET);
        $this->setRequest($_REQUEST);
        $this->setUri($_SERVER);
    }
    function setGet($array = array())
    {
        $this->gets = $array;
        return;
    }
    function setUri($array = array())
    {
        $this->serve = $array;
        return;
    }
    
    function setPost($array = array())
    {
        $this->posts = $array;
        return;
    }
    function setRequest($array = array())
    {
        $this->requests = $array;
        return;
    }    
    function getQueryParams()
    {
        return $this->gets;
    }
    function getParamGet($a)
    {
        return $this->gets[$a];
    } 
    function getParam($a)
    {
        return $this->requests[$a];
    }    
    function getParamPost($a)
    {
        return $this->posts[$a];
    }    
    function getPosts()
    {
        return $this->posts;
    }
    function getRequest()
    {
        return $this->requests;
    }
    function getServerDate()
    {
        return $this->serve;
    } 
}