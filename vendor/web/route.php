<?php

/**
 * App
 *
 * @author      Gean pedro da silva
 * @copyright   2017 Gean Pedro
 * @link        geanpn@gmail.com
 * @day         05/01/2018
 *
 * MIT LICENSE
 */
namespace vendor\web;

class route
{
    private $type = array();
    private $rota = array();
    private $rota_not_param = array();
    private $params = array();
    private $array_route = '';
    private $params_real = array();
    private $params_real_op = array();
    private $route_real = '';
    private $control = array();
    private $group = '';
    private $middleware = '';
    
    
    function __construct($rota,$params,$type,$group,$group_route)
    {
        $this->rota = $group_route.$rota;
        $this->params = $params;
        $this->type = $type;
        $this->getRealParam();
        $this->getRealControl();
        $this->group = $group;
        return $this;
                
    }
    
    function getRealParam()
    {

        $this->getRouteArray();
        $route_explode = $this->array_route;
        $param_finaly = array();
        $param_op = array();
        $real_route = '';
        $real='';
        foreach($route_explode as $key => $values){
            
            if(strpos($values,'{') !== false){
                 $param_finaly[$key] = substr($values,1,-1);
                if(strpos($param_finaly[$key],'?') !== false){
                    $param_finaly[$key] = substr($values,1,-2); 
                    $param_op[$key] = '';
                   
                }
                 unset( $route_explode[$key] );
               
            } else {
                $real .='/'. $values;
            } 
        }
        $this->params_real = $param_finaly;
        $this->route_real =  $route_explode;
        $this->params_real_op = $param_op;
        $this->rota_not_param = $real;
        
        
    }
    function setMiddleware($name)
    {
        $date= explode('@',$name);
        $this->middleware = [$date[0],$date[1]];
    }
    function getMiddleware()
    {
       return $this->middleware; 
    }      
    function getRoute()
    {
        return $this->route_real;
    } 
    function getRouteReal()
    {
        return  $this->rota_not_param ;
    } 
    function getGroup()
    {
        return $this->group;
    }     
    function getRealControl()
    {
        $control = $this->params;
        if(is_callable($control)){
            $return = ['1',$control];
            
        } else {
            $return = ['0',explode('@',$this->params)];
        }
        
        $this->control = $return;
    }
    function checkParam($param)
    {
        $param_route = $this->params_real;
        $param_op = $this->params_real_op;
        
        $dif1 = array_diff_key($param_route,$param);
        $dif2 = array_diff_key($param,$param_route);
        $dif3 = array_diff_key($dif1,$param_op);
       
            if(($dif1 == array() && $dif2 == array()) || ($dif3 == array() && $dif2 == array()) ){
                return true;
                
            }
         return false;
    } 
    
    function getRouteArray()
    {
        $rotas = $this->rota;
        $array_explode = explode('/',$rotas);
        array_shift($array_explode);
        $this->array_route = $array_explode;
        
    }
    
    function getFunction()
    {
        return $this->control ;
    }
    
    function getParams($param)
    {
        foreach($this->params_real_op as $key => $values){
            !isset($param[$key])?$param[$key]='':false;
            
            
        }
          
       return array_combine($this->params_real,$param);

    }
    
    function getParamsRedirect()
    {
         return $this->params_real;
    }
    
  

}