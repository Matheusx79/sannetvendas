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



class url
{   
    
    /**
    * $rotas é o array responsavel por armazenar as classes de rotas, criadas pelo usuario. 
    * os dados estao no formato $rota[tipo da requisição][indice da classe]
    */  
    private $rotas = array();
    
    /**
    * $active_control é o array responsavel por armazenar o controller e a action da url atual. 
    * os dados estao no formato $active_control['controller','action']
    */     
    private $active_function = array();
    
    private $active_middle = array();
    
    /**
    * $param é o array responsavel por armazenar os passados pela url 
    * Os dados estao no formato $param[nome definido no arquivo de rotas] = (valor passado pelo usuario)
    * Esse array é passado para o control
    */  
    private $param = array();
    
    
    private $group_active = '';
    private $groups= array();
    private $names_routes= array();
    private $rota_active = '';
    private $last_rota = '';
    
    function create_group($name)
    {
 
        $this->groups[$name]=['name'=>$name,'middle'=>''];
    }
    function define_active_group($name)
    {
        $this->group_active=$name;
    }
    
    function define_group_route($name)
    {
        $this->rota_active = $name;
    }  
    function define_middle($control)
    {
        $number = $this->last_rota;
        $obj = $this->rotas[$number[1]][$number[0]];
        $obj_group = $obj->getGroup();
    
        if($obj_group == ''){
            $obj->setMiddleware($control);
        }else{
            $date = explode('@',$control);
            $this->groups[$obj_group]['middle'] = [$date[0],$date[1]];
        }
    }
    

    
    /**
    * Esssa funçao adiciona no array de rotas uma nova classe com os parametros passados no arquivo de rotas
    * 1 - $rota = rota definida pelo usuario
    * 2 - $params = controller e action definidos pelo usuario no formato de 'controller@action'
    * 3 - $type = para qual tipo de requisição(GET,POST,DELETE) essa rota se aplica
    */  
    function addRoute($rota,$params,$type)
    {
        
        $this->rotas[$type][] = new route($rota,$params,$type,$this->group_active,$this->rota_active);
        $this->last_rota =[count($this->rotas[$type]) - 1,$type];
    }
    
    /**
    * Essa funçao pecorre as rotas.
    * Executando uma funçao dentro da rota percorrida  que retorna a string $rota privada. 
    * Utilizando um strpos procura qualquer rota que se assemelhe a o valor recebido.
    * Encontrando retornara um true.
    * Ela é uma função de suporte para a findRoute logo abaixo
    */      
    private function find($keys,$values,$type)
    {
        foreach($this->rotas[$type] as $key => $value){
            $original_route=$value->getRoute(); 
            $diff = array_diff_key([$keys=>$values],$original_route);
           
            if($diff == array()){
             if($original_route[$keys] == $values){ return true;};
            }
            }
        return false;
    
        }
    
    /**
    * Essa função tem como objetivo descobrir uma rota no array $rotas, através da rota recebida da classe app. 
    * Ela transforma a url recebida em um array através do explode.
    * Pecorrendo o array criado ela procura   '/' + valor atual do foreach na função find.
    * Retornando um true ela armazena o valor atual do foreach, e passa para proximo indice procurando ele mais o valor anterior 
    * Retornando false ela define esse elemento do array como um parametro, armazendo em outra variavel e partindo para o proximo indice.(a rota final gerada e uma aproximação que sera conferida na função check route).
    */     
    private function findRoute($val,$type)
    {
        substr($val,-1) == '/'?$val=substr($val,0,-1):false;
        $route_eplode = explode('/',$val);
        $route_finaly = '';
        $param =array();
        $val == ''?$route_eplode=array() :false;
        foreach ($route_eplode as $key => $values){
            if($this->find($key,$values,$type)){
                $route_finaly .= '/'.$values;
            }else {
                unset($route_eplode[$key]); 
                $param[$key] = $values;
             }
        }
        return([$route_eplode,$param]);
    } 
    
    /**
    * Essa função tem como objetivo percorrer o array $route . 
    * Procurando uma rota que tenha o mesmo nome da aproximação gerada pelo findRoute();
    * Logo apos encontrar a rota, compara os parametros aproximados gerados pelo findRoute() com a rota encontrada
    * Tudo conferido ele seta as variaveis control($active_control) e parametros($param)
    */     
    private function checkRoute($route,$type,$param)
    {
        foreach ($this->rotas[$type] as $key => $values){
            if ($values->getRouteArray() == $route){
                if($values->checkParam($param)){
                  
                    $this->active_function = $values->getFunction();
                    $this->param = $values->getParams($param);
                    $this->active_middle = [$values->getGroup(),$values->getMiddleware()];
                  return true;  
                }
                
            }
        }
        return false;
       
        
    }
    
    public function defineNome($name)
    {
        $lat = $this->last_rota;
        $lat = $this->rotas[$lat[1]][$lat[0]];
        
        $this->names_routes[$name] = [$lat->getRouteReal(),$lat->getParamsRedirect()];
    }
    
    /**
    * Essa função executa o control($active_control) e passa nele os parametros($param)
    */    
    private function runControl($container)
    {
        $request = new http\request;
        $function = $this->active_function;
        $container->path->setRoute($this->names_routes);
        if($function[0] == 1 ){
            $d = $function[1];
            $cl = $d->bindTo($container);
            $cl();
            exit;
        } else if($function[0]==0){
            $control = 'app\http\controller\\'. $this->active_function[1][0]."Controller";
            $method = $this->active_function[1][1];
                if( $control = new $control($container) ){
                    $control->$method($request,$this->param);
                }
        }   
    }
    
    /**
    * Função principal executa todas as outras.
    * 1 - procura uma rota aproximada com find_Routes()
    * 2 - analisa se rota encontrada é valida com a check_Route()
    * 3 - executa o controller com o runControl()
    */ 
    
    function execute($route_user,$type,$container)
    {
        $rota = $this->findRoute($route_user,$type);
        
        if($this->checkRoute($rota[0],$type,$rota[1])){
            $midle = $this->active_middle;
            if($midle[0] == '' && $midle[1] != ''){
                $control = $midle[1][0];
                $action = $midle[1][1];
                $control = new $control;
                $control->$action();
                
            }else if(isset($this->groups[$midle[0]]['middle'][0])) {
                $midle2 = $this->groups[$midle[0]]['middle'];
                $control = $midle2[0];
                $action = $midle2[1]; 
                $control = new $control;
                $control->$action();
            }
            

            $this->runControl($container);
        }
        
    }
        
 

}