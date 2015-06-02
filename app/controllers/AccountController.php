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
        
        //Cuenta
        $account = new Account();
        $accountForm = new AccountForm();
        $this->view->setVar('accountForm', $accountForm);
        
        //Usuario
        $user = new User();
        $userForm = new UserForm();
        $this->view->setVar('userForm', $userForm);
        
        //Guardar nueva cuenta y usuario administrador
        if($this->request->isPost()){
            
            //Verificamos contraseña
            if($this->request->getPost('pass') != $this->request->getPost('pass2')){
                $msg = "<div class='alert alert-warning'><span class='glyphicon glyphicon-remove'></span> Las contraseñas ingresas no coinciden, por favor intenrelo nuevamente.</div>";
            }
            
            //Datos cuenta
            $accountForm->bind($this->request->getPost(), $account);
            $account->created = time();
            $account->updated = time();
            $account->status = 1;
            
            //Datos usuario
            $userForm->bind($this->request->getPost(), $user);
            $user->idRole = 2;
            $user->created = time();
            $user->updated = time();
            $user->name = $this->request->getPost('name-user');
            $user->address = $this->request->getPost('address-user');
            $user->phone = $this->request->getPost('phone-user');
            $user->idAccount = $account->idAccount;
            
            // Guardar cuenta y usuario
            if($account->save() && $user->save()){
                $msg = "<div class='alert alert-warning'><span class='glyphicon glyphicon-remove'></span> Ocurrio un error al momento de guardar, por favor intenrelo nuevamente.</div>";
                return $this->response->redirect('account/index');
            }
            else {
                return $this->response->redirect('account/add');
            }
        }
    }
    
    public function editAction()
    {
        
    }
}