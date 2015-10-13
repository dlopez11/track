<?php

class VisitController extends ControllerBase
{
    public function indexAction()
    {
        $users = User::find(array(
            'conditions' => 'idAccount = ?1',
            'bind' => array(1 => $this->user->idAccount)
        ));
        
        $tvisits = Visittype::find(array(
            'conditions' => 'idAccount = ?1',
            'bind' => array(1 => $this->user->idAccount)
        ));
        
        $clients = Client::find(array(
            'conditions' => 'idAccount = ?1',
            'bind' => array(1 => $this->user->idAccount)
        ));
        
        $this->view->setVar('users', $users);
        $this->view->setVar('tvisits', $tvisits);
        $this->view->setVar('clients', $clients);
    }
    public function getrowsAction()
    {
        $post = $this->request->getPost('paginator');
//        $this->logger->log(print_r($post, true));
        
        $limit = $post['limit'];
        $page = $post['page'];
        
        $filter = new \stdClass();
        $filter->user = $post['user'];
        $filter->visitType = $post['visit'];
        $filter->client = $post['client'];
        $filter->start = $post['start'];
        $filter->end = $post['end'];
        
        $pager = new \Sigmamovil\Misc\PaginationDecorator();
        
        $pager->setRowsPerPage($limit);
        $pager->setCurrentPage($page);
        
        $finder = new \Sigmamovil\Misc\VisitFinder();
        
        try {
            $finder->setPaginator($pager);
            $finder->setAccount($this->user->account);
            $finder->setFilter($filter);
            $finder->load();
            $rows = $finder->getRows();
//            $this->logger->log(print_r($rows, true));
            
            return $this->set_json_response(json_encode($rows), 200, 'success');
        } 
        catch (Exception $ex) {
            $this->logger->log($ex->getMessage());
            return $this->set_json_response('Ha ocurrido un error, por favor contacte al administrador', 500);
        }
    }
    
    public function maphistoryAction($idUser)
    {  
        $visits = Visit::findFirst(array(
            "conditions" => "idUser = ?1",
            "bind" => array(1 => $idUser)
        ));

        $user = User::findFirst(array(
            "conditions" => "idUser = ?1",
            "bind" => array(1 => $idUser)
        ));

        if(!$visits){
            $this->flashSession->error('Ocurrio un error procesando su solicitud, por favor intentelo nuevamente.');
            return $this->response->redirect('visit/index');
        }

        $this->view->setVar('visits', $visits);
        $this->view->setVar('user', $user);
    }        
    
    public function mapAction($idVisit)
    {
        $visit = Visit::findFirst(array(
            "conditions" => "idVisit = ?1",
            "bind" => array(1 => $idVisit)
        ));
        
        if (!$visit) {
            $this->flashSession->error("Ocurrio un error procesando su solicitud, por favor intentelo nuevamente.");
            return $this->response->redirect('index');
        }
        
        $user = User::findFirst(array(
            "conditions" => "idUser = ?1 AND idAccount = ?2",
            "bind" => array(1 => $visit->idUser,
                            2 => $this->user->idAccount)
        ));
        
        if (!$user) {
            $this->flashSession->error("Ocurrio un error procesando su solicitud, por favor intentelo nuevamente.");
            return $this->response->redirect('visit/index');
        }
        
        try {
            $sql_rows = "SELECT v.*, u.idUser AS idUser,  u.name AS name, u.lastName AS lastname, vt.name AS visit, c.name AS client "
                    . "FROM Visit AS v "
                    . " JOIN User AS u ON (u.idUser = v.idUser) "
                    . " JOIN Visittype AS vt ON (vt.idVisittype = v.idVisittype) "
                    . " JOIN Client AS c ON (c.idClient = v.idClient) "
                    . " WHERE v.idVisit = {$idVisit}";
                    
            $this->logger->log($sql_rows);

            $modelsManager = \Phalcon\DI::getDefault()->get('modelsManager');      
            $rows = $modelsManager->executeQuery($sql_rows);
            
            $data = $rows->getFirst();

            $this->view->setVar('visit', $rows->getFirst());
            $this->view->setVar('user', $user);
        }
        catch (Exception $e) {
            $this->flashSession->error("Ocurrio un error mientras se seleccionaban los datos de la visita, por favor contacte al administrador");
            $this->logger->log("Exception while selecting data for visit map: {$e->getMessage()} ");
            $this->trace("fail", "Error while selecting data for visit map");
            return $this->response->redirect('visit/index');            
        }     
    }
    
    public function mapfinallocationAction($idVisit)
    {
        $visit = Visit::findFirst(array(
            "conditions" => "idVisit = ?1",
            "bind" => array(1 => $idVisit)
        ));
        
        if (!$visit) {
            $this->flashSession->error("Ocurrio un error procesando su solicitud, por favor intentelo nuevamente.");
            return $this->response->redirect('index');
        }
        
        $user = User::findFirst(array(
            "conditions" => "idUser = ?1 AND idAccount = ?2",
            "bind" => array(1 => $visit->idUser,
                            2 => $this->user->idAccount)
        ));
        
        if (!$user) {
            $this->flashSession->error("Ocurrio un error procesando su solicitud, por favor intentelo nuevamente.");
            return $this->response->redirect('visit/index');
        }
        
        try {
            $sql_rows = "SELECT v.*, u.name AS name, u.lastName AS lastname, vt.name AS visit, c.name AS client "
                    . "FROM Visit AS v "
                    . " JOIN User AS u ON (u.idUser = v.idUser) "
                    . " JOIN Visittype AS vt ON (vt.idVisittype = v.idVisittype) "
                    . " JOIN Client AS c ON (c.idClient = v.idClient) "
                    . " WHERE v.idVisit = {$idVisit}";
                    

            $modelsManager = \Phalcon\DI::getDefault()->get('modelsManager');      
            $rows = $modelsManager->executeQuery($sql_rows);

            $this->view->setVar('visit', $rows->getFirst());
            $this->view->setVar('user', $user);
        }
        catch (Exception $e) {
            $this->flashSession->error($e->getMessage());
            $this->trace("fail",$e->getMessage());
            return $this->response->redirect('visit/index');            
        }     
    }

    public function getmapAction($idUser)
    {                
        try {
            $day = strtotime(date("Y-m-d"));                
            $thirty_days_ago = strtotime("-29 days", $day);
            
            $phqlvisits = 'SELECT Visit.* FROM Visit JOIN User JOIN Visittype JOIN Client WHERE User.idUser = Visit.idUser AND Visit.idClient = Client.idClient AND '
                            . 'Visit.idVisittype = Visittype.idVisittype AND Visit.idUser = ?0 AND Visit.start >= ?1';
            $visits = $this->modelsManager->executeQuery($phqlvisits, array(0 => "{$idUser}",
                                                                            1 => "{$thirty_days_ago}"));
            
            return $this->recorrerResultados($visits);
        } 
        catch (Exception $ex) {
            $this->logger->log("Exception while getting map data: {$ex->getMessage()}");
            return $this->set_json_response(array("Ocurrió un error, por favor contacte al administrador"), 500);
        }
    }
    
    public function getmapbyrangedateAction($idUser){
        try {            
            $firstdate = $this->request->getPost("start");
            $lastdate = $this->request->getPost("end");
                        
            $phqlvisits = 'SELECT Visit.* FROM Visit JOIN User JOIN Visittype JOIN Client WHERE User.idUser = Visit.idUser AND Visit.idClient = Client.idClient AND '
                            . 'Visit.idVisittype = Visittype.idVisittype AND Visit.idUser = ?0 AND FROM_UNIXTIME(Visit.start) BETWEEN FROM_UNIXTIME(?1) AND FROM_UNIXTIME(?2)';
                              
            $visits = $this->modelsManager->executeQuery($phqlvisits, array(0 => "{$idUser}", 1 => "{$firstdate}", 2 => "{$lastdate}"));
            
            return $this->recorrerResultados($visits);
        } 
        catch (Exception $ex) {
            $this->logger->log("Exception while getting map data: {$ex->getMessage()}");
            return $this->set_json_response(array("Ocurrió un error, por favor contacte al administrador"), 500);
        }
    }
    
    /**
     * @author Dorian Lopez - Sigma Movil
     * Metodo encargado de organizar la informacion de las visitas y retornarlos a la API de Google Maps
     * @param $visits
     * @return Json
     */
    public function recorrerResultados($visits){
        $objects = array();
        foreach ($visits as $visit) {
            $clientData = "<div style='width: 250px;'>";
            $clientData .= "<strong><span style='font-size: 17px;'>" . $visit->client->name . "</span></strong>";
            $clientData .= "<br />";
            $clientData .= "<strong>Dirección</strong>: " . $visit->client->address;
            $clientData .= "<br />";
            $clientData .= "<strong>Fecha de visita</strong>: ". date('d/M/Y H:i',$visit->start) . " <strong>a</strong> " . date('d/M/Y H:i',$visit->end);
            $clientData .= "<br />";
            $clientData .= "<strong>Tipo</strong>: ".$visit->visittype->name;
            $objects[] = array(
                'latitude' => $visit->latitude,
                'longitude' => $visit->longitude,
                'location' => $visit->location,
                'client' => $clientData,
            );
        }

        return $this->set_json_response($objects);
    }
}
