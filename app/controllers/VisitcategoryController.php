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
                $vcat->created = time();
                $vcat->updated = time();
                $vcat->idAccount = $this->user->idAccount;
                if($this->request->getPost("description" == "")){
                    $vcat->description = "Sin descripción";
                }
                
                if (!$vcat->save()) {
                    foreach ($vcat->getMessages() as $msg) {
                        throw new Exception($msg);
                    }
                }
                
                $this->flashSession->success('Se ha creado la categoría exitosmante');
                return $this->response->redirect('visittype');
            } 
            catch (Exception $ex) {
                $this->flashSession->error($ex->getMessage());
            }
        }
    }
}
