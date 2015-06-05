<?php

class VisitController extends ControllerBase
{
    public function indexAction()
    {
        
    }
    public function getrowsAction()
    {
        $post = $this->request->getPost('paginator');
//        $this->logger->log($post);
        $this->logger->log(print_r($post, true));
        
        
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
            $this->logger->log(print_r($rows, true));
            
            return $this->set_json_response(json_encode($rows), 200, 'success');
        } 
        catch (Exception $ex) {
            $this->logger->log($ex->getMessage());
        }
       
        
        
//        $pagination = array('pagination' => array(
//            'page' => $pager->getCurrentPage(),
//            'limit' => $pager->getRowsPerPage(),
//            'total' => $pager->getTotalRecords(),
//            'rows' => $pager->getRowsPerPage(),
//            'availablepages' => $pager->getTotalPages(),			
//            'data' => $finder->getRows(),			
//        ));
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
            return;
        }
        
        $user = User::findFirst(array(
            "conditions" => "idUser = ?1 AND idAccount = ?2",
            "bind" => array(1 => $visit->idUser,
                            2 => $this->user->idAccount)
        ));
        
        if (!$user) {
            return;
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
            return;
        }     
    }
    public function getmapAction($idUser)
    {
        $phql3 = 'SELECT visit.latitude,visit.longitude,visit.location FROM visit WHERE visit.idUser = ?0';
        $visits = $this->modelsManager->executeQuery($phql3, array(0 => "{$idUser}"));
        
        $objects = array();
        foreach ($visits as $visit) {
            $objects[] = array(
                'latitude' => $visit->latitude,
                'longitude' => $visit->longitude,
                'location' => $visit->location
            );
            
        }
        return $this->set_json_response($objects);
    }
}
