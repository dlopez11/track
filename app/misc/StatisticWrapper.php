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

            default:
                break;
        }
    }
    
    private function findVisits()
    {
        $query = \Phalcon\DI::getDefault()->get('modelsManager')->createQuery("SELECT Visit.idVisit, Visit.idVisittype, Visit.idUser, Visittype.name FROM Visit JOIN Visittype WHERE Visittype.idAccount = {$this->account->idAccount}");
        $this->visits = $query->execute();
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
            $percentage = $value/$total*100;
            $array = array($names[$key], $percentage);
            $this->modelData[] = $array;
        }
    }
    
    public function getModelData()
    {
        return $this->modelData;
    }
}