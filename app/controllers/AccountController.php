<?php

class AccountController extends ControllerBase
{
    public function indexAction()
    {
        $currentPage = $this->request->getQuery('page', null, 1);
        $builder = $this->modelsManager->createBuilder()
            ->from('Account')
            ->orderBy('Account.created');

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
//        $account = new Account();
        $accountForm = new AccountForm();
        $userForm = new UserForm();
        $this->view->setVar('accountForm', $accountForm);
        $this->view->setVar('userForm', $userForm);
    }
    
    public function editAction()
    {
        
    }
}