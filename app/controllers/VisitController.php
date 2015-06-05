<?php

class VisitController extends ControllerBase
{
    public function indexAction()
    {
        
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
        $filter->date = $post['date'];
        
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
            return $this->set_json_response('ha ocurrido un error, por favor contacte al administrador', 500);
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
            return $this->response->redirect('visit/index');
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
            $sql_rows = "SELECT v.idVisit AS idUser, v.date AS date, u.name AS name, u.lastName AS lastname, vt.name AS visit, c.name AS client, v.battery AS battery, v.latitude AS latitude, v.longitude AS longitude, v.location AS location "
                    . "FROM Visit AS v "
                    . " JOIN User AS u ON (u.idUser = v.idUser) "
                    . " JOIN Visittype AS vt ON (vt.idVisittype = v.idVisittype) "
                    . " JOIN Client AS c ON (c.idClient = v.idClient) "
                    . " WHERE v.idVisit = {$idVisit}";
                    
            $this->logger->log($sql_rows);

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
        if(isset($_GET['date1'])){
            $date1 = $_GET['date1'];
            $date1 = time($date1);
            $date2 = $_GET['date1'];
            $date2 = time($date2);
            $phqlvisits = 'SELECT visit.date, visit.latitude,visit.longitude,visit.location,visit.idClient,visit.idVisittype FROM visit WHERE visit.idUser = ?0 and visit.date = ?1 and visit.date = ?2';
            $visits = $this->modelsManager->executeQuery(
                $phqlvisits, array(
                    0 => "{$idUser}",
                    1 => "{$date1}",
                    2 => "{$date2}"
                )
            );
        }
        else{
            $phqlvisits = 'SELECT visit.latitude,visit.longitude,visit.location,visit.idClient,visit.idVisittype FROM visit WHERE visit.idUser = ?0';
            $visits = $this->modelsManager->executeQuery($phqlvisits, array(0 => "{$idUser}"));
        }
        $objects = array();
        foreach ($visits as $visit) {
            $phqlclients = 'SELECT client.name,client.address,visit.idClient FROM client INNER JOIN visit ON visit.idClient = client.idClient WHERE client.idClient = ?0';
            $clients = $this->modelsManager->executeQuery($phqlclients, array(0 => "{$visit['idClient']}"));
            foreach ($clients as $client) {
                $phqlvisittype = 'SELECT visittype.name FROM visittype INNER JOIN visit ON visit.idVisittype = visittype.idVisittype WHERE visit.idVisittype = ?0';
                $visitstype = $this->modelsManager->executeQuery($phqlvisittype, array(0 => "{$visit['idVisittype']}"));
                foreach ($visitstype as $visittype){
                    $visitData = "<strong>Tipo</strong>: ".$visittype['name'];
                }
                $clientData = "<div style='width: 250px;'>";
                $clientData .= "<strong><span style='font-size: 17px;'>".$client['name']."</span></strong>";
                $clientData .= "<br />";
                $clientData .= "<strong>Dirección</strong>: ".$client['address'];
                $clientData .= "<br />";
            }
            $objects[] = array(
                'latitude' => $visit->latitude,
                'longitude' => $visit->longitude,
                'location' => $visit->location,
                'client' => $clientData.$visitData,
            );
        }
        return $this->set_json_response($objects);
    }
}
