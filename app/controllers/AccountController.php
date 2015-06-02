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
        $userForm = new UserForm();
        $this->view->setVar('userForm', $userForm);
        
        //Guardar nueva cuenta y usuario administrador
        if($this->request->isPost()){
            
            //Convertimos a mayusculas y quitamos los espacios al nombre de la cuenta
            $accountName = trim($this->request->getPost('name'));
            $acName = strtolower($accountName);
            
            //Verificamos que el campo nombre no este vacio
            if (empty($acName)) {
                $this->logger->log('El campo nombre esta vacio');
//                return $this->response->redirect('account/add');
            }
            
            //Buscamos si existe una cuenta con el mismo nombre
//            $objects = array();
//            $accounts = Action::findByIdAccount($this->user->idAccount);
//            foreach($accounts as $account){
//                $objects[] = $account->name;
//            }
//            if(in_array($acName, $objects)){
//               $this->logger->log('Ya existe una cuenta con este nombre');
//            }
            
            //Datos cuenta
            $accountForm->bind($this->request->getPost(), $account);
            $account->created = time();
            $account->updated = time();
            $account->status = 1;
            
            $this->db->begin();

            if ($accountForm->isValid() && $account->save()) {
                $this->saveUser($account, $userForm);
                $this->db->commit();
                $this->logger->log('La cuenta fue creada exitasamente');
            }
            
            if (!$account->save()) {
                
            }
            
            return $this->response->redirect('account/index');
        }
    }
    
    protected function saveUser(Account $account, $userForm)
    {
        //Verificamos que las contraseñas coincidan
        if($this->request->getPost('pass') != $this->request->getPost('pass2')){
            $this->logger->log('Las contraseñas no coinciden');
        }
        if(count($this->request->getPost('pass') < 8)){
            $this->logger->log('La contraseña es demasiado corta');
        }
            
        //Datos usuario
        $user = new User();
        $userForm->bind($this->request->getPost(), $user);
        $user->idRole = 2;
        $user->created = time();
        $user->updated = time();
        $user->name = $this->request->getPost('name-user');
        $user->address = $this->request->getPost('address-user');
        $user->phone = $this->request->getPost('phone-user');
        $user->idAccount = $account;
        $user->password = $this->security->hash($this->request->getPost('pass'));

        // Guardar usuario
        if(!$user->save()){
            $this->logger->log('Ha ocurrido un error al guardar el usuario');
        }
    }

        public function editAction()
    {
        
    }
}