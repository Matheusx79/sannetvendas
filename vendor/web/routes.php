<?php
namespace vendor\web;
use vendor\graphs\error;
use vendor\web\http\request;
use vendor\web\http\response;

class routes {
    
     //)))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))
    //array principal
       
    private $params = array();
    private $active_group = false;
    private $list_group = array();
    public  $nameroutes = array();
    
    
    //funcão que procurar um um texto no array de rotas
    function find($find,$type){
       
      
        foreach ($this->params as $value => $key){
        
        
            if(strpos(  $key[$type][0],$find) === 0){
                 
            return true;                
            } 
                
               
            
              
        }
        return false;   
    }
    //)))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))
    //função que procurar uma rota registada com o explode do url  digitado no navegador 
    function check_array($url,$t){
        $url_route = '';
        $params = [];
        $params_real = array();
        $i=0;
        foreach ($url as $key => $values){
            
          
            if($this->find( $url_route.'/'.$url[$key],$t)){
                
                $url_route .='/'.$url[$key]; 
                
            }else{
                
                $params[$i] =  $url[$key];
                    
                
            }
             $i += 1;
            
        }
    
       
     
        if (isset($this->params[$url_route][$t])) {
        $route_array = $this->params[$url_route][$t];
            $params_diff = array_diff_key ($params,$route_array[2] );
            $params_diff_2 = array_diff_key ($route_array[2] ,$params);
            $force = false;
            foreach ($params_diff_2 as $key => $value){

                if(!$route_array[4][$key]){
                    $force =  false;
                    
                    break;
                    
                }
                
                $params[$key]= null; 
                $force = true;
               
            }
            
 
            if(((is_array($params_diff) &&    $params_diff !=array()) || (is_array($params_diff_2) &&    $params_diff_2 !=array() )) && (!$force)  ){
                
            $error = new error;
            $error->setTitle('Error parametro invalida.');
            $error->setMsg('Lamentamos o problema enfretado tente volta para a pagina inicia e continue curtindo nosso site.');
            $error->load();
            exit;
                
            } else {
  
                $params_real = array_combine($route_array[2], $params);
                $route_array[2] = $params_real;
                return  $route_array;
           }
                
        } else  {
            $error = new error;
            $error->setTitle('Error rota invalida.');
            $error->setMsg('Lamentamos o problema enfretado tente volta para a pagina inicia e continue curtindo nosso site.');
            $error->load();
            exit;
            
        }
        
            
    }
     
     //)))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))
    //funcão que registras rotas gets
    function get($route,$function){
        $route_finaly = '';
        $param_finaly = array() ;
        $param_force = array();
        $retorno = false;
        if(isset($route) && $route != null && isset($function) && $function != null ){
          
        
            $route_explode = explode("/", $route);
            array_shift($route_explode);
                $route == '/'?$route_explode == array('index'):false;    
            foreach ($route_explode as $key => $values){
                if(strstr($route_explode[$key] , '{' )){
                    
                    $route_key=$route_explode[$key];
                   
                        if (strpos($route_explode[$key],'?') > 0){
                           $retorno = true ;
                             $txt_param = substr($route_key,1,-2)  ; 
                            }  else {
                             $txt_param = substr($route_key,1,-1);
                            $retorno = false;
                        }
                    $param_finaly[$key]=$txt_param;
                    $param_force[$key]= $retorno;
                    
                } else {
                    $route_key = $route_explode[$key];
                    $route_finaly .=  '/'.$route_key;
                }
            }
            if($route != '/') {
            } else {
                $route_finaly = '/index';
                
               
            }
        $group = $this->active_group;       
        $type = 0;
        if(is_object($function)){
            $this->params[$route_finaly][$type] = array($route_finaly,$type,'f'=>$function,$param_finaly,$group,$param_force);  
           
        } else {
            $controls = explode('@',$function);
            $this->params[$route_finaly][$type] = array($route_finaly,$type,'c'=>array('control'=>$controls[0],'action'=>$controls[1]),$param_finaly,$group,$param_force);  
            
        }
        return $this;   
        }
        
        
        
     }
    //)))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))
    //funcão que registra grupos
    function group($name,$function){
        $this->active_group = $name;
        $function($this);
        $this->list_group[$name] =  array($name);
        $this->active_group = '';
        return $this;
          
    }
    //função registra middleware
    function middleware($name){
        $array = explode('@',$name);
        $method = $array[0];
        $action = $array[1];
        $t= 0;
        $last_group = array_pop($this->params);
        
        isset($last_group[0])? $t= 0 :  $t= 1;
        $last_group = $last_group[$t];
      
          if($last_group[3] == '' ){
                array_push($last_group,array('middle' => array($method,$action))) ;
                $this->params[$last_group[0]] = [$t  => $last_group];
          } else {
              $last_group = array_pop($this->group);
              $this->list_group[$last_group[0]] = array('middle' => array($method,$action));
          } 
        
        
    }
    
    function setName($name){
        $last_group = array_pop($this->params);
        isset($last_group[0])? $t= 0 :  $t= 1;
        $this->nameroutes[$name] = $last_group[$t][0];
        $last_group = $last_group[$t];
        $this->params[$last_group[0]] = [$t  => $last_group];
        return $this;
        
    }
    function getNames(){
        return $this->nameroutes ;
    }
    
     //)))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))
    //funcão que registra rotas posts
    
    function post($route,$function){
              $route_finaly = '';
        $param_finaly = array() ;
        $param_force = array();
        $retorno = false;
        if(isset($route) && $route != null && isset($function) && $function != null ){
          
        
            $route_explode = explode("/", $route);
            array_shift($route_explode);
                $route == '/'?$route_explode == array('index'):false;    
            foreach ($route_explode as $key => $values){
                if(strstr($route_explode[$key] , '{' )){
                    
                    $route_key=$route_explode[$key];
                   
                        if (strpos($route_explode[$key],'?') > 0){
                           $retorno = true ;
                             $txt_param = substr($route_key,1,-2)  ; 
                            }  else {
                             $txt_param = substr($route_key,1,-1);
                            $retorno = false;
                        }
                    $param_finaly[$key]=$txt_param;
                    $param_force[$key]= $retorno;
                    
                } else {
                    $route_key = $route_explode[$key];
                    $route_finaly .=  '/'.$route_key;
                }
            }
            if($route != '/') {
            } else {
                $route_finaly = '/index';
                
               
            }
        $group = $this->active_group;       
        $type = 1;
        if(is_object($function)){
            $this->params[$route_finaly][$type] = array($route_finaly,$type,'f'=>$function,$param_finaly,$group,$param_force);  
           
        } else {
            $controls = explode('@',$function);
            $this->params[$route_finaly][$type] = array($route_finaly,$type,'c'=>array('control'=>$controls[0],'action'=>$controls[1]),$param_finaly,$group,$param_force);  
            
        }
        return $this;   
        }
        
     }
    
    //)))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))
    //funcão que adiciona as rotas registradas no array principal de rotas
    
    function addParams($route,$type,$function,$param,$group){
        $type_number = false;
        $type == 'get'? $type_number = 0: $$type_number = 1;
        if(is_object($function)){
            $this->params[$route][$type_number] = array($route,$type,'f'=>$function,$param,$group);  
            return;
        } else {
            $controls = explode('@',$function);
            $this->params[$route][$type_number] = array($route,$type,'c'=>array('control'=>$controls[0],'action'=>$controls[1]),$param,$group);  
            return;
        }
       
       
    }
    

    
     //)))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))))
    //inicia o sistema de rotas
    
    function load(){
        
        
        $_SERVER['REQUEST_METHOD'] == 'GET'?$type=0:$type=1;
        isset($_GET['url'])?$url=$_GET['url']:$url = 'index/' ;
        if(substr($url,-2,1) != '' && substr($url,-1,1) == '/'   ){
            $url = substr($url,0,-1);
        }
        
        $a = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
         if ($url == '' && file_exists(di.'/resource/views/'.substr($a,-9,9))) {
                        $error = new error;
                        $error->setTitle('Error metodo invalido.');
                        $error->setMsg('Lamentamos o problema enfretado tente volta para a pagina inicia e continue curtindo nosso site.');
                        $error->load();
             return;
        }
       
        $url_array=  explode('/',$url);
         
        $route = $this->check_array($url_array,$type);
      
            if(is_array($route)){
                
            if(  isset($this->list_group[$route[3]])  && $route[3] != ''  ){
                
                $exec = $this->list_group[$route[3]]['middle'][0];
                $exec2 = $this->list_group[$route[3]]['middle'][1];
                $midle = new $exec();
                 $midle->$exec2();
                
            } else if (isset($route[5]))  {
                $exec = $route[5]['middle'][0];
                $exec2 = $route[5]['middle'][1];
                $midle = new $exec();
                $midle->$exec2();
                
                
            }
                
                
                if(array_key_exists('c',$route)){

                     $control_name = $route['c']['control'].'controller' ;
                     $action_name = $route['c']['action'];
                    if(new  $control_name){
                        $control = new  $control_name;
                        if(method_exists($control,$action_name)){
                            $request = new request();
                            $response = new response($this->getNames());
                            $args=$route[2];
                            $control->$action_name($request,$args,$response);
                        }else{
                            $error = new error;
                            $error->setTitle('Error metodo invalido.');
                            $error->setMsg('Lamentamos o problema enfretado tente volta para a pagina inicia e continue curtindo nosso site.');
                            $error->load();
                exit;
                        }
                    } else{
                        $error = new error;
                        $error->setTitle('Error controller invalido.');
                        $error->setMsg('Lamentamos o problema enfretado tente volta para a pagina inicia e continue curtindo nosso site.');
                        $error->load();
                    }

                } else {
                    $route['f']($route[2]);

                }
            
            }else{
                print_r($route);
            }
    }
    
    
    
    
    
    
}

?>