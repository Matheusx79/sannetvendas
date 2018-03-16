<?php
namespace vendor\db;
use vendor\db\dbconn;
use pdo;

class table
{
    private $select = '*';
    private $table = '';
    private $where = '';
    private $joinwhere = '';
    private $like = '';
    private $joins = '';
    private $group = '';
    private $values= array();
    private $limit = '';
    function table($table)
    {
        
        $this->table = $table;
        return $this;
        
    }
    function get($param = array())
    {
        
        $conn = new dbconn;
        $conn = $conn->startConnect();
        
        $query = 'select ' . $this->select . ' from ' . $this->table . $this->joins . $this->joinwhere . $this->where . $this->like . $this->group.$this->limit;
        
        
        
        $exec = $conn->prepare($query);
        
        foreach ($this->values as $key => $values) {
            $exec->bindValue($key,$values);
            
        }
        if ($exec->execute()) {
            return $exec->fetchAll(PDO::FETCH_ASSOC);
            
        }
        
        
    }
    function where($val1, $in, $val2 = '')
    {
        if ($this->where != '') {
            echo 'Para multiplos where utilize a funlçao bla bla';
            exit;
        }
        if ($val2 == ''){
            $retorno = ' where ' . $val1 . ' = :val2' ;
            $this->values[':val2'] = $in;
        } else {
            $retorno = ' where ' . $val1 .' '.$in. ' :val2' ;
            $this->values[':val2'] = $val2;
            
        }
       
        
        $this->where .= $retorno;
        return $this;
        
    }
    function whereList($param = array())
    {
        $i = 0;
        $value = '';
        foreach ($param as $key => $values) {
            $i += 1;
            if (is_string($values)) {
                $value = substr($value, 0, -4) . ' ' . $values . ' ';
                
            } else {
                $value .= $values[0] . ' ' . $values[1] . ' :val' . $i . ' and ';
                $this->values[':val' . $i ] = $values[2];    
            }
            
        }
        $this->where = ' where ' . substr($value, 0, -4);
        return $this;
    }
    function like($param = array())
    {
        $value = '';
        foreach ($param as $key => $values) {
            
            $value .= $key . ' like ' . "'" . $values . "'" . ' and ';
            
        }
        $this->like = ' where ' . substr($value, 0, -4);
        return $this;
        
    }
    function group($txt)
    {
        $this->group = ' group by ' . $txt;
        return $this;
    }
    
    function join($table, $val1,$int,$val2='')
    {

        if($val2 == '' ){
            $value = $val1 . ' and ' . $int;
        } else {
            $value = $val1 .' '. $int .' '. $val2;
        }
        $this->joins = ' join ' . $table . ' on ' . $value;
        return $this;
        
    }
    
    function select($param = array())
    {
        $values = '';
        if (is_array($param) && $param != array()) {
            foreach ($param as $key => $value) {
                $values .= $value . ' , ';
            }
            $this->select = substr($values, 0, -2);
            
            
            
        }
        return $this;
        
    }
    
    function limit($val1,$val2=false){
        if( $val2 != false){
            $value ='limit '.$val1 .','.$val2;
        } else{
            $value = 'limit '.$val1;
        }
        $this->limit = $value;
        return $this;
       
    }
    
    function limitPag($number,$pag){
        $pag == null ?$pag=1:false;
        print_r( $pag);
        $start = ($pag - 1) * $number;
        $value =' limit '.$start .' , '.$number;
        $this->limit = $value;
        return $this;
       
    }
    
        function getPagNumber($numb)
    {
        
        $conn = new dbconn;
        $conn = $conn->startConnect();
        
        $query = 'select ' . $this->select . ' from ' . $this->table . $this->joins . $this->joinwhere . $this->where . $this->like . $this->group.$this->limit;
        
        
        
        $exec = $conn->prepare($query);
        
        foreach ($this->values as $key => $values) {
            $exec->bindValue($key,$values);
            
        }
        if ($exec->execute()) {
            return ceil($exec->rowCount() / $numb);
            
        }
        
        
    }
    
    
}

