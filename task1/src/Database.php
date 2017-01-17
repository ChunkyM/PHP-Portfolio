<?php
include __DIR__. '/../cfg/cfg.php';
/**
 * Created by PhpStorm.
 * User: m026540e
 * Date: 14/11/2016
 * Time: 09:44
 */
class Database
{
    private $dbConn;

    public  function  __construct()
    {
        global $cfg;
        

        try {
            $this->dbConn = new mysqli(
                $cfg['db']['host'],
                $cfg['db']['user'],
                $cfg['db']['pass'],
                $cfg['db']['db']
            );
        }catch (Exception $e){
            throw new Exception("connection error");
        }
    }
    


    public function Whereclause($parameters){
        $sql="";
        if (isset($parameters['conditions'])){
            foreach($parameters['conditions'] as $field=>$value){
                $my_string = '`' . $field . '`=';
                if (!is_numeric($value)){
                    $my_string .= '\'' . $value . '\'';
                }
                else{
                    $my_string .= $value;
                }
                $conditions[] = $my_string;
            }
            $sql .= ' WHERE '. join(' AND ',$conditions );
        }
        return $sql;
    }
    public function Likeclause($parameters){
        $sql="";
        if (isset($parameters['like'])){
            foreach($parameters['like'] as $field=>$value){
                if ($parameters['LogicCondition'] == 'LIKE'){
                    $my_string = '`' . $field . '` LIKE ';
                }
                elseif($parameters['LogicCondition'] == '<'){
                    $my_string = '`' . $field . '` < ';
                }
                elseif($parameters['LogicCondition'] == '>'){
                    $my_string = '`' . $field . '` > ';
                }
                if (!is_numeric($value)){
                    $my_string .= '\'' . $value . '\'';
                }
                else{
                    $my_string .= $value;
                }
                $conditions[] = $my_string;
            }
            $sql .= ' WHERE '. join(' AND ',$conditions );
        }
        return $sql;
    }

public function select($parameters){
    if ($parameters['fields'][0] != '*'){
        for ($i=0; $i < count($parameters['fields']);$i++){
            $parameters['fields'][$i] = '`' . $parameters['fields'][$i] . '`';
        }
    }
    $sql =  'SELECT '.join(',',$parameters['fields']) . ' FROM'. ' `'. $parameters['table'] .'`' ;
    $sql .= $this->Whereclause($parameters);
    $sql .= $this->Likeclause($parameters);
    $this->dbConn->query($sql);
    return$sql;
    
    }
    
    public function insert($parameters){
    if ($parameters['fields'][0] != '*'){
        for ($i=0; $i < count($parameters['fields']);$i++){
            $parameters['fields'][$i] = '`' . $parameters['fields'][$i] . '`';
        }
    }

    $sql =  'INSERT INTO' .  ' `'. $parameters['table'] .'` ' .  '('.join(',',$parameters['fields']). ') ' . 'VALUES ' ;
    if (isset($parameters['values'])) {
        foreach ($parameters['values'] as $field => $value) {
            $i;
            $i++;
            $even = (($i % 2));

            if( $even != 0 ){

                $my_string = '(\'' . $value . '\'';
                $Value[] = $my_string;
            }
            else{
                $my_string = '\'' . $value . '\')';
                $Value[] = $my_string;
            }




        }
    }

    $sql .=  join(', ',$Value );
        
        $this->dbConn->query($sql);
        return $this->dbConn->affected_rows;

}

    public function update($parameters){
        if (isset($parameters['values'])) {
            foreach ($parameters['values'] as $field => $value) {


                $my_string = '\'' . $value . '\'';


                $Value[] = $my_string;
            }
        }
        if ($parameters['fields'][0] != '*'){
            for ($i=0; $i < count($parameters['fields']);$i++){
                $parameters['fields'][$i] = '`' . $parameters['fields'][$i] . '`= ' . $Value[$i] ;
            }
        }

        $sql =  'UPDATE' . ' `'. $parameters['table'] .'` ' . 'SET '. join(',' ,$parameters['fields']);
        $sql .= $this->Whereclause($parameters);
        $this->dbConn->query($sql);
        return$this->dbConn->affected_rows;
    }

    public function delete($parameters){
        if ($parameters['fields'][0] != '*'){
            for ($i=0; $i < count($parameters['fields']);$i++){
                $parameters['fields'][$i] = '`' . $parameters['fields'][$i] . '`';
            }
        }
        $sql =  'DELETE ' . 'FROM'. ' `'. $parameters['table'] .'`' ;
        $sql .= $this->Likeclause($parameters);
        $sql .= $this->Whereclause($parameters);
        $this->dbConn->query($sql);
        return$this->dbConn->affected_rows;
        
    }


}