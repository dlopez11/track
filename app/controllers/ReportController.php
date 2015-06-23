<?php

class ReportController extends ControllerBase 
{
    public function createAction()
    {
        $data = $this->request->getPost('paginator');
        $reportCreator = new \Sigmamovil\Misc\ReportCreator();
        
        $this->logger->log(print_r($data, true));
        
        try {
            $reportCreator->setAccount($this->user->account);
            $reportCreator->setData($data);
            $reportCreator->process();
            $report = $reportCreator->getReport();
            
            return $this->setJsonResponse(array($report->idTmpreport), 200);  
        } 
        catch (Exception $ex) {
            $this->logger->log($ex->getMessage());
            return $this->set_json_response('ha ocurrido un error, por favor contacte al administrador', 500);
        }
    }  
    
    public function downloadAction($idReport)
    {
        $account = $this->user->account;
        $tmpreport = Tmpreport::findFirst(array(
                    'conditions' => 'idTmpreport = ?1 AND idAccount = ?2',
                    'bind' => array(1 => $idReport,
                        2 => $account->idAccount)
        ));

        if (!$tmpreport) {
            return $this->response->redirect('error');
        }

        $this->view->disable();

        header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename={$tmpreport->name}");
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Pragma: no-cache');

        $folder = "{$this->path->path}{$this->path->tmpfolder}{$this->user->account->idAccount}/{$tmpreport->name}";
        readfile($folder);
    }  
}
