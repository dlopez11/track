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
        $accountForm = new AccountForm($account);
        $this->view->setVar('accountForm', $accountForm);
        
        //Usuario}
        $user = new User();
        $userForm = new UserForm($user);
        $this->view->setVar('userForm', $userForm);
        
        //Guardar nueva cuenta y usuario administrador
        if($this->request->isPost()){
            try {
                $accountForm->bind($this->request->getPost(), $account);
                $account->created = time();
                $account->updated = time();
                if(!$this->request->getPost('status')){
                    $account->status = 0;
                }

                $this->db->begin();

                if (!$account->save()) {
                    foreach ($account->getMessages() as $msg) {
                        throw new Exception($msg);
                    }
                    $this->db->rollback();
                }
            
                $this->saveUser($account, $user, $userForm);            
           
                $this->db->commit();
                return $this->response->redirect('account/index');
            } 
            catch (Exception $ex) {
                $this->flashSession->error($ex->getMessage());
            }
        }
    }
    
    protected function saveUser(Account $account, User $user, $userForm)
    {
        //Verificamos que las contraseñas coincidan
        if($this->request->getPost('pass') != $this->request->getPost('pass2')){
            throw new Exception('Las contraseñas no coinciden, por favor valide la información');
        }
        if(strlen($this->request->getPost('pass') < 8)){
            throw new Exception('La contraseña es demasiado corta, recuerde que debe tener mínimo 8 caracteres, por favor valide la información');
        }
        
        //Datos usuario
        $userForm->bind($this->request->getPost(), $user);
        $user->account = $account;
        $user->idRole = 2;
        $user->created = time();
        $user->updated = time();
        $user->name = $this->request->getPost('name_user');
        $user->address = $this->request->getPost('address_user');
        $user->state = $this->request->getPost('state_user');
        $user->city = $this->request->getPost('city_user');
        $user->phone = $this->request->getPost('phone_user');
        $user->password = $this->security->hash($this->request->getPost('pass'));

        // Guardar usuario
        if(!$user->save()){
            foreach ($user->getMessages() as $msg) {
                throw new Exception($msg);
            }
            $this->db->rollback();
        }
    }

    public function editAction($idAccount)
    {
        $editAccount = Account::findFirst(array(
            "conditions" => "idAccount = ?1",
            "bind" => array(1 => $idAccount)
        ));
        
        if(!$editAccount){
            $this->logger->log('La cuenta a la que intenta acceder no existe');
            return $this->response->redirect('account/index');
        }
        
        $this->view->setVar('account_value', $editAccount);
        
        $editAccount->city = $editAccount->city;
        $editAccount->state = $editAccount->state;
        
        $accountForm = new AccountForm($editAccount);
        
        if($this->request->isPost()){
            
            $accountForm->bind($this->request->getPost(), $editAccount);
            
            try {
                $editAccount->updated = time();
                
                if(!$this->request->getPost('status')){
                    $editAccount->status = 0;
                }

                if (!$editAccount->save()) {
                    foreach ($editAccount->getMessages() as $msg) {
                        throw new Exception($msg);
                    }
                }
            } 
            catch (Exception $ex) {
                $this->flashSession->error($ex->getMessage());
            }
            
            return $this->response->redirect('account/index');
        }
        
        $this->view->accountForm = $accountForm;
    }
}