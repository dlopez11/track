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
    
    public function setVisits($visits)
    {
        $this->visits = $visits;
    }
    
    public function processData($type)
    {
        switch ($type) {
            case $value:


                break;

            default:
                break;
        }
    }
    
    private function modelData()
    {
        foreach ($this->modelData as $data) {
            
        }
    }
    
    public function getModelData()
    {
        return $this->modelData;
    }
}