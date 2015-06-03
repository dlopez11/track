<?php

class ClientController extends ControllerBase
{ 
    public function indexAction()
    {
        $currentPage = $this->request->getQuery('page', null, 1);
        $builder = $this->modelsManager->createBuilder()
            ->from('Client')
//            ->where("idAccount = {$this->user->idAccount}")
            ->orderBy('Client.created');

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
        $client = new Client();
        $form = new ClientForm($client);
        $this->view->setVar('form', $form);
        
        if ($this->request->isPost()) {
            try {
                $form->bind($this->request->getPost(), $client);
                $client->created = time();
                $client->updated = time();
                $client->idAccount = $this->user->idAccount;
                
                if (!$client->save()) {
                    foreach ($client->getMessages() as $msg) {
                        throw new Exception($msg);
                    }
                }
                
                $this->flashSession->success('Se ha creado el cliente exitosmante');
                return $this->response->redirect('client');
            } 
            catch (Exception $ex) {
                $this->flashSession->error($ex->getMessage());
            }
        }
    }
    
    public function editAction($idClient)
    {
        $client = Client::findFirst(array(
            'conditions' => 'idClient = ?1',
            'bind' => array(1 => $idClient),
        ));
        
        if (!$client) {
            $this->flashSession->error("No se encontró el cliente, por favor valide la información");
            return $this->response->redirect('client');
        }
        
        $form = new ClientForm($client);
        $this->view->setVar('form', $form);
        $this->view->setVar('client', $client);
        
        if ($this->request->isPost()) {
            try {
                $form->bind($this->request->getPost(), $client);
                $client->updated = time();
                
                if (!$client->save()) {
                    foreach ($client->getMessages() as $msg) {
                        throw new Exception($msg);
                    }
                }
                
                $this->flashSession->notice("Se ha editado el cliente: <strong>{$client->name}</strong>,  exitosmante");
                return $this->response->redirect('client');
            } 
            catch (Exception $ex) {
                $this->flashSession->error($ex->getMessage());
            }
        }
    }
    
    public function removeAction($idClient)
    {
        $client = Client::findFirst(array(
            'conditions' => 'idClient = ?1',
            'bind' => array(1 => $idClient),
        ));
        
        if (!$client) {
            $this->flashSession->error("No se encontró el cliente, por favor valide la información");
            return $this->response->redirect('client');
        }
        
        try {
            $client->delete();
            $this->flashSession->warning("Se ha eliminado el cliente exitosamente");
//            return $this->response->redirect('client');
        } 
        catch (Exception $ex) {
            $this->logger->log("Exception: {$ex}");
            $this->flashSession->error("Ocurrió un error, por favor contacte al administrador");
//            return $this->response->redirect('client');
        }
        
        return $this->response->redirect('client');
    }
}

