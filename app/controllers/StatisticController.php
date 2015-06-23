<?php

class StatisticController extends ControllerBase 
{
    public function getdataAction($type)
    {
        $statistic = new \Sigmamovil\Misc\StatisticWrapper();
        $statistic->setAccount($this->user->account);
        $statistic->processData($type);
        $data = $statistic->getModelData();
        
//        $this->logger->log("Data " . print_r($data, true));
        
        return $this->set_json_response(array($data), 200);
    }
}