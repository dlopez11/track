<?php

class ClientController extends ControllerBase
{ 
    public function indexAction()
    {
        $currentPage = $this->request->getQuery('page', null, 1);
        $builder = $this->modelsManager->createBuilder()
            ->from('Client')
            ->where("idAccount = {$this->user->idAccount}")
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
            'conditions' => 'idClient = ?1 AND idAccount = ?2',
            'bind' => array(1 => $idClient,
                            2 => $this->user->idAccount),
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
            $this->flashSession->error("Ocurrió un error al eliminar este registro de cliente, es posible que esté asociado a una visita, por favor contacte al administrador");
//            return $this->response->redirect('client');
        }
        
        return $this->response->redirect('client');
    }
    
    public function importAction()
    {
        try {
            if ($_FILES['csv']['size'] > 1048576){
                return $this->set_json_response(array('El archivo CSV no puede ser mayor a 1 MB de peso'), 403);
            }
            
            if ($_FILES['csv']['size'] > 0) {

                $fileinfo = pathinfo($_FILES['csv']['name']);
                
                if(strtolower(trim($fileinfo["extension"])) != "csv")
                {
                    return $this->set_json_response(array('Por favor seleccione un archivo de tipo CSV'), 403);
                }
                   
                $csv = $_FILES['csv']['tmp_name'];
                $handle = fopen($csv,'r');
                
                $values = array();
                $objects = array();
                $client = Client::findByIdAccount($this->user->idAccount);
                
                foreach($client as $c){
                    $objects[] = $c->name;
                }
                
                while($data = fgetcsv($handle,1000,";","'")){
                    if($data[0]){
                        if (!in_array($data[0], $objects)) {
                            $values[] = "(null,{$this->user->idAccount}," . time() . "," . time() . ",'$data[0]','$data[1]',$data[2],'$data[3]',$data[4],'$data[5]','$data[6]')";
                        }
                    }                    
                }
                                
                $this->logger->log(print_r($objects, true));
                $this->logger->log(print_r($values, true));
                
                if (count($values) > 0) {
                    $text = implode(", ", $values); 
                    $sql = "INSERT INTO client (idClient, idAccount, created, updated, name, description, nit, address, phone, city, state) VALUES {$text}";
                    $result = $this->db->execute($sql); 
                    
                    return $this->set_json_response(array('El archivo se importo exitosamente'), 200);
                }
                
                return $this->set_json_response(array('Los clientes que intenta importar ya existen en la plataforma'), 403);
            }
        }
        catch(Exception $e) {
            return $this->set_json_response(array($e->getMessage()), 403);            
        }
    }
}

