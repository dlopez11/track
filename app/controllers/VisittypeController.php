<?php

class VisittypeController extends ControllerBase
{ 
    public function indexAction()
    {
        $currentPage = $this->request->getQuery('page', null, 1);
        $builder = $this->modelsManager->createBuilder()
            ->from('Visittype')
            ->leftJoin('Visitcategory')    
            ->where("Visittype.idAccount = {$this->user->idAccount}")
            ->orderBy('Visittype.created');

        $paginator = new Phalcon\Paginator\Adapter\QueryBuilder(array(
            "builder" => $builder,
            "limit"=> 15,
            "page" => $currentPage
        ));
        
        $page = $paginator->getPaginate();
        $this->view->setVar("page", $page);
    }
    
    public function addAction()
    {
        $vcat = Visitcategory::find();
        $this->view->setVar("vcat", $vcat);
        
        $vtype = new Visittype();
        $form = new VisittypeForm($vtype);
        $this->view->setVar('form', $form);
        
        if ($this->request->isPost()) {
            try {
                $form->bind($this->request->getPost(), $vtype);
                $name = trim($this->request->getPost("name"));
                if($name == ""){
                    $msg = "El nombre no puede estar vacio, por favor valide la información";
                    throw new Exception($msg);
                }
                $vtype->idVisitcategory = $this->request->getPost("category");
                $vtype->created = time();
                $vtype->updated = time();
                $vtype->idAccount = $this->user->idAccount;
                
                if (!$vtype->save()) {
                    foreach ($vtype->getMessages() as $msg) {
                        throw new Exception($msg);
                    }
                }
                
                $this->flashSession->success('Se ha creado el tipo de visita exitosmante');
                return $this->response->redirect('visittype');
            } 
            catch (Exception $ex) {
                $this->flashSession->error($ex->getMessage());
            }
        }
    }
    
    public function editAction($idVisittype)
    {        
        
        $vtype = Visittype::findFirst(array(
            'conditions' => 'idVisittype = ?1',
            'bind' => array(1 => $idVisittype),
        ));
        
        $cat = Visitcategory::find();
        $this->view->setVar("cat", $cat);
        
        $vcat = Visitcategory::findFirst(array(
            'conditions' => 'idVisitcategory = ?1',
            'bind' => array(1 => $vtype->idVisitcategory),
        ));
        
        $this->view->setVar("vcat", $vcat);
        
        if (!$vtype) {
            $this->flashSession->error("No se encontró el tipo de visita, por favor valide la información");
            return $this->response->redirect('visittype');
        }
        
        $form = new VisittypeForm($vtype);
        $this->view->setVar('form', $form);
        $this->view->setVar('vtype', $vtype);
        
        if ($this->request->isPost()) {
            try {
                $form->bind($this->request->getPost(), $vtype);
                $name = trim($this->request->getPost("name"));
                if($name == ""){
                    $msg = "El nombre no puede estar vacio, por favor valide la información";
                    throw new Exception($msg);
                }
                $client->updated = time();
                $vtype->idVisitcategory = $this->request->getPost("category");
                
                if (!$vtype->save()) {
                    foreach ($vtype->getMessages() as $msg) {
                        throw new Exception($msg);
                    }
                }
                
                $this->flashSession->notice("Se ha editado el tipo de visita: <strong>{$vtype->name}</strong>,  exitosmante");
                return $this->response->redirect('visittype');
            } 
            catch (Exception $ex) {
                $this->flashSession->error($ex->getMessage());
            }
        }
    }
    
    public function removeAction($idVisittype)
    {
        $vtype = Visittype::findFirst(array(
            'conditions' => 'idVisittype = ?1 AND idAccount = ?2',
            'bind' => array(1 => $idVisittype,
                            2 => $this->user->idAccount),
        ));
        
        if (!$vtype) {
            $this->flashSession->error("No se encontró el tipo de visita, por favor valide la información");
            return $this->response->redirect('visittype');
        }
        
        try {
            $vtype->delete();
            $this->flashSession->warning("Se ha eliminado el cliente exitosamente");
//            return $this->response->redirect('client');
        } 
        catch (Exception $ex) {
            $this->logger->log("Exception: {$ex}");
            $this->flashSession->error("Ocurrió un error al eliminar este registro de tipo de visita, es posible que esté asociado a una visita, por favor contacte al administrador");
//            return $this->response->redirect('client');
        }
        
        return $this->response->redirect('visittype');
    }
}

