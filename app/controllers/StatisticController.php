<?php

class StatisticController extends ControllerBase 
{
    public function getdataAction($type)
    {
        try {
            $statistic = new \Sigmamovil\Misc\StatisticWrapper();
            $statistic->setAccount($this->user->account);
            $statistic->processData($type);
            $data = $statistic->getModelData();

    //        $this->logger->log("Data " . print_r($data, true));

            return $this->set_json_response(array($data), 200);
        } 
        catch (Exception $ex) {
            $this->logger->log("Exception while getting statistic data: {$ex->getMessage()}");
            return $this->set_json_response(array("Ocurrió un error, por favor contacte al administrador"), 500);
        }
    }
}