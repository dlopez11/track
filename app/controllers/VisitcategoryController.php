<?php

class VisitcategoryController extends ControllerBase
{
    public function indexAction()
    {
        $currentPage = $this->request->getQuery('page', null, 1);
        $builder = $this->modelsManager->createBuilder()
            ->from('Visitcategory')
            ->where("idAccount = {$this->user->idAccount}")
            ->orderBy('Visitcategory.created');

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
        $vcat = new Visitcategory();
        $form = new VisitcategoryForm($vcat);
        $this->view->setVar('form', $form);
        
        if ($this->request->isPost()) {
            try {
                $form->bind($this->request->getPost(), $vcat);
                $name = trim($this->request->getPost("name"));
                if($name == ""){
                    $msg = "El nombre no puede estar vacio, por favor valide la información";
                    throw new Exception($msg);
                }
                $vcat->created = time();
                $vcat->updated = time();
                $vcat->idAccount = $this->user->idAccount;
                $description = trim($this->request->getPost("description"));
                if($description == ""){
                    $vcat->description = "Sin descripción";
                }
                
                if (!$vcat->save()) {
                    foreach ($vcat->getMessages() as $msg) {
                        throw new Exception($msg);
                    }
                }
                
                $this->flashSession->success('Se ha creado la categoría exitosamente');
                return $this->response->redirect('visitcategory');
            }
            catch (Exception $ex) {
                $this->flashSession->error($ex->getMessage());
            }
        }
    }
    
    public function editAction($idVisitcategory)
    {
        $vcat = Visitcategory::findFirst(array(
            'conditions' => 'idVisitcategory = ?1',
            'bind' => array(1 => $idVisitcategory),
        ));
        
        if (!$vcat) {
            $this->flashSession->error("No se encontró la categoria, por favor valide la información");
            return $this->response->redirect('visitcategory');
        }
        
        $form = new VisittypeForm($vcat);
        $this->view->setVar('form', $form);
        $this->view->setVar('vcat', $vcat);
        
        if ($this->request->isPost()) {
            try {
                $form->bind($this->request->getPost(), $vcat);
                $client->updated = time();
                $name = trim($this->request->getPost("name"));
                if($name == ""){
                    $msg = "El nombre no puede estar vacio, por favor valide la información";
                    throw new Exception($msg);
                }
                
                if (!$vcat->save()) {
                    foreach ($vcat->getMessages() as $msg) {
                        throw new Exception($msg);
                    }
                }
                
                $this->flashSession->notice("Se ha editado la categoria: <strong>{$vcat->name}</strong>,  exitosmante");
                return $this->response->redirect('visitcategory');
            } 
            catch (Exception $ex) {
                $this->flashSession->error($ex->getMessage());
            }
        }
    }
    
    public function removeAction($idVisitcategory)
    {
        $vcat = Visitcategory::findFirst(array(
            'conditions' => 'idVisitcategory = ?1 AND idAccount = ?2',
            'bind' => array(1 => $idVisitcategory,
                            2 => $this->user->idAccount),
        ));
        
        if (!$vcat) {
            $this->flashSession->error("No se encontró la categoria, por favor valide la información");
            return $this->response->redirect('visitcategory');
        }
        
        try {
            $vcat->delete();
            $this->flashSession->warning("Se ha eliminado la categoría exitosamente");
        } 
        catch (Exception $ex) {
            $this->logger->log("Exception: {$ex}");
            $this->flashSession->error("Ocurrió un error al eliminar esta Categoría, es posible que esté asociado a una visita, por favor contacte al administrador");
        }
        
        return $this->response->redirect('visitcategory');
    }
}
