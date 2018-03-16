<?php

/**
 * App
 *
 * @author      Gean pedro da silva
 * @copyright   2017 Gean Pedro
 * @link        geanpn@gmail.com
 * @version     0.0.1
 *
 * MIT LICENSE
 */
namespace vendor\web;

class app
{
    /**
    * Variavel Responsavel por armazena a classe de rota
    * var $rota = '' / class
    */
    public $rota;
    private $last_module = '';
    private $container = '';

    /**
    * Inicia a classe de rotas url
    */
    public function __construct()
    {
        $this->rota = new url;
        $this->last_module = '';
        $container = new \vendor\container;
        $container = (object)$container->container;
        $this->container= $container;
    }

    public function setContainer ($name)
    {
        $this->container = $name;
    }
    public function getContainer(){

        return $this->container;
    }

    /**
    * Responsavel por tratar as call
    * GET,POST,PUT,DELETE
    */
    public function __call($name, $arguments)
    {
        $this->define_last_module('route');
        $arguments[] = $name;
        $url = $this->rota ;
        call_user_func_array(array($url,'addRoute'), $arguments);
        return $this;

    }
    function define_last_module($name)
    {
        $this->last_module = $name;
    }

    /**
    * Inicia a classe de rotas que trabalha com o url recebido do .htacess
    * A função Execute() Recebe dois parametros
    * 1 - A url do .htacess
    * 2 - Tipo da requisição(GET,POST,PUT,DELETE,ETC....)
    */
    public function run()
    {
        isset($_GET['url'])?$url = $_GET['url']: $url ='' ;
        $type = strtolower($_SERVER['REQUEST_METHOD']);
        $this->rota->execute($url,$type,$this->container);
    }

    public function map($type,$nome,$rotas)
    {
        $this->define_last_module('map');
        $url = $this->rota;

        foreach ($type as $key => $values ){
            $arguments=[$nome,$rotas,$values];
            call_user_func_array(array($url,'addRoute'), $arguments);
        }
        return $this;

    }

    public function group($name,$rota='',$func)
    {
        $url=$this->rota;
        $this->define_last_module('group');
        $url->create_group($name);
        $url->define_active_group($name);
        $url->define_group_route($rota);
        $cl = $func;
        $cl = $cl->bindTo($this);
        $cl();
        $url->define_active_group('');
        $url->define_group_route('');
        return $this;

    }

    public function setMiddleware($control){
        if( $this->last_module == 'map' ){
            echo 'error classe mapa nao pode ser utilizada no group mode';
            exit;
        }
        $this->define_last_module('middle');
        $url=$this->rota;
        $url->define_middle($control);
        return $this;

    }
    public function setName($name)
    {
        if( $this->last_module == 'group' || $this->last_module == 'map' ){
            echo 'error classe mapa nao pode ser utilizada no group mode';
            exit;
        }
        $this->define_last_module('name');
        $rotas = $this->rota;
        $rotas->defineNome($name);
        return $this;

    }



}
