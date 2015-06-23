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
                $this->modelColumndata();
                break;
            
            default:
                break;
        }
    }
    
    private function findVisits()
    {
        $query_visits = \Phalcon\DI::getDefault()->get('modelsManager')->createQuery("SELECT Visit.idVisit, Visit.idVisittype, Visit.idUser, Visittype.name FROM Visit JOIN Visittype WHERE Visittype.idAccount = {$this->account->idAccount}");
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
        
    }
    
    public function modelColumnData()
    {
        $data = array();
        
        $today = date("Y-m-d");
        
        $first_day = strtotime("-29 days");
        $date = date("Y-m-d" , $first_day);
        
        
    }

        public function getModelData()
    {
        return $this->modelData;
    }
}