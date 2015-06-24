<?php
/**
 * Description of PaginationDecorator
 *
 * @author Will
 */
namespace Sigmamovil\Misc;

class StatisticWrapper
{
    private $account;
    private $logger;
    private $visits = array();
    private $modelData = array();
    
    public function __construct() 
    {
        $this->logger = \Phalcon\DI::getDefault()->get('logger');
    }
    
    public function setAccount(\Account $account)
    {
        $this->account = $account;
    }
    
    
    public function processData($type)
    {
        $this->findVisits();
        
        switch ($type) {
            case "pie":
                $this->modelPieData();
                break;
            
            case "line":
                $this->modelLineData();
                break;
            
            case "column":
                $this->modelColumnData();
                break;
            
            default:
                break;
        }
    }
    
    private function findVisits()
    {
        $today = date("Y-m-d");
        $first_day = strtotime("-29 days", $today);
        $tomorrow = strtotime("Tomorrow");
        
        $query_visits = \Phalcon\DI::getDefault()->get('modelsManager')->createQuery("SELECT Visit.idVisit, Visit.idVisittype, Visit.idUser, Visit.date, User.name, Visittype.name AS vname FROM Visit JOIN Visittype JOIN User WHERE Visittype.idAccount = {$this->account->idAccount} AND Visit.date >= {$first_day} and Visit.date < {$tomorrow}");
        $this->visits = $query_visits->execute();
    }


    private function modelPieData()
    {
        $data = array();
        $names = array();
        
        foreach ($this->visits as $v) {
            if (isset($data[$v->idVisittype])) {
                $data[$v->idVisittype] += 1;
            }
            else {
                $data[$v->idVisittype] = 1;
            }
            
            $names[$v->idVisittype] = $v->name;
        }
        
        $total = array_sum($data);
        
        foreach ($data as $key => $value) {
            $array = array($names[$key], $total);
            $this->modelData[] = $array;
        }
    }
    
    private function modelLineData()
    {        
        $time = array();
        $visits = array(0, 0);
        $date = strtotime(date("Y-m-d"), time());
        $today = strtotime("+1 days", $date);
        $first_day = strtotime("-29 days", $today);
        
        $time[] = $first_day;
        $j = 0;
        for ($i = 1; $i < 29; $i++) {
            $visits[] = 0;
            $time[] = strtotime("+1 days", $time[$j]);
            $j++;
        }
        
        $time[] = $today;
        $total = array();
        $vt = array();
        foreach ($this->visits as $visit){
            if(!in_array($visit->vname, $vt)){
                $obj = new \stdClass();
                $obj->name = $visit->vname;
                $obj->data = $visits;
                $vt[] = $visit->vname;
                $total[] = $obj;
            }
            foreach($time AS $key => $v) {
                if ($visit->date >= $v AND $visit->date < $time[$key+1]) {
                    $obj->data[$key] += 1;
                }
            }
            
        }
        
        $tm = array();
        foreach ($time as $t) {
            $tm[] = date("d/M/Y", $t);
        }
        
        $this->modelData = array(
            'categories' => $tm,
            'data' => $total
        );
    }
    
    private function modelColumnData()
    {
        $us = \User::findByIdAccount($this->account->idAccount);
        
        $time = array();
        $visits = array(0, 0);
        
        $today = strtotime("tomorrow");
        $first_day = strtotime("-29 days", $today);
        
        $time[] = $first_day;
        $j = 0;
        
        for ($i = 1; $i < 29; $i++) {
            $visits[] = 0;
            $time[] = strtotime("+1 days", $time[$j]);
            $j++;
        }
        
        $time[] = $today;
        
        $users = array();
        foreach ($us as $user) {
            $obj = new \stdClass();
            $obj->idUser = $user->idUser;
            $obj->name = $user->name;
            $obj->data = $visits;
            $users[] = $obj;
        }
        
        foreach ($this->visits as $visit){
            foreach ($users as $user) {
                if ($visit->idUser == $user->idUser) {
                    foreach($time AS $key => $v) {
                        if ($visit->date >= $v AND $visit->date < $time[$key+1]) {
                            $user->data[$key] += 1;
                        }
                    }
                }
            }
        }
        
        $tm = array();
        foreach ($time as $t) {
            $tm[] = date("d/M/Y", $t);
        }
        
        $this->modelData = array(
            'time' => $tm,
            'data' => $users
        );
    }

    public function getModelData()
    {
        return $this->modelData;
    }
}