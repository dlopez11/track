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
                $this->flashSession->success('La cuenta se creo exitosamente.');
                $this->trace("success","Se creo exitosamente una nueva cuenta con ID: {$account->idAccount}");
                return $this->response->redirect('account/index');
            } 
            catch (Exception $ex) {
                $this->flashSession->error($ex->getMessage());
                $this->trace("fail",$ex->getMessage());
            }
        }
    }
    
    protected function saveUser(Account $account, User $user, $userForm)
    {
        //Verificamos que las contraseñas coincidan
        if($this->request->getPost('pass') != $this->request->getPost('pass2')){
            throw new Exception('Las contraseñas no coinciden, por favor valide la información');
        }
        
        //Datos usuario
        $userForm->bind($this->request->getPost(), $user);
        $user->password = hash($this->request->getPost('pass'));
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
            $this->trace("fail","No se pudo guardar el usuario de una cuenta");
            $this->db->rollback();
            throw new Exception('No se pudo guardar el usuario de una cuenta');
        }
    }

    public function editAction($idAccount)
    {
        $editAccount = Account::findFirst(array(
            "conditions" => "idAccount = ?1",
            "bind" => array(1 => $idAccount)
        ));
        
        if(!$editAccount){
            $this->flashSession->error('La cuenta a la que intenta acceder no existe, por favor valide la información.');
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
                    $this->trace("fail","Error al editar una cuenta con ID: ".$editAccount->idAccount);
                    foreach ($editAccount->getMessages() as $msg) {
                        throw new Exception($msg);
                    }
                }
            } 
            catch (Exception $ex) {
                $this->flashSession->error($ex->getMessage());
                $this->trace("fail","Ha ocurrido un error: ".$ex->getMessage());
                throw new Exception('Ha ocurrido un error');
            }
            $this->trace("success","Se edito la cuenta con ID: ".$editAccount->idAccount);
            $this->flashSession->success('Se ha editado exitosamente la cuenta <strong>'.$editAccount->name.'</strong>');
            return $this->response->redirect('account/index');
        }
        
        $this->view->accountForm = $accountForm;
    }
    
    public function userlistAction($id)
    {
        $currentPage = $this->request->getQuery('page', null, 1);

        $paginator = new Phalcon\Paginator\Adapter\Model(array(
             "data" => User::find(array(
                            "conditions" => "idAccount = ?1",
                            "bind" => array(1 => $id)                    
                        )),
             "limit"=> 15,
             "page" => $currentPage
         ));

         $page = $paginator->getPaginate();

         $this->view->setVar("page", $page);
         $this->view->setVar("idAccount", $id);
    }
    
    public function newuserAction($idAccount)
    {
        $user = new User();
        $form = new UserForm($user, $this->user->role);
        
        $account = Account::findFirst(array(
            'conditions' => 'idAccount = ?1',
            'bind' => array(1 => $idAccount)
        ));
        
        if (!$account) {
            $this->flashSession->error("La cuenta enviada no existe, por favor verifique la información");
            return $this->response->redirect("account/index/{$this->user->account->idAccount}");
        }
        
        if($this->request->isPost()){
            
            $form->bind($this->request->getPost(), $user);
            
            $username = $form->getValue('userName');
            $pass = $form->getValue('pass');
            $pass2 = $form->getValue('pass2');
            
            $uservalidate = User::findFirst(array(
                "conditions" => "userName = ?1 AND idAccount = ?2" ,
                "bind" => array(1 => $username,
                                2 => $idAccount)
            ));
            
            if($uservalidate){
                $this->flashSession->error("El nombre de usuario ya existe, por favor valide la información");                
            }            
            else if($pass !== $pass2){
                $this->flashSession->error("Las contraseñas ingresas no coinciden, por favor intentelo nuevamente.");
            }
            else if(strlen($pass) < 8) {
                $this->flashSession->error("La contraseña es muy corta, debe tener minimo 8 caracteres.</div>");
            }
            else if(strlen($username) < 4){
                $this->flashSession->error("El nombre de usuario es muy corto, debe tener minimo 4 caracteres.");
            }
            else{
                
                $email = strtolower($form->getValue('email'));
                
                $user->name = $this->request->getPost('name_user');
                $user->address = $this->request->getPost('address_user');
                $user->state = $this->request->getPost('state_user');
                $user->city = $this->request->getPost('city_user');
                $user->phone = $this->request->getPost('phone_user');
                $user->idAccount = $account->idAccount;
                $user->email = $email;
                $user->password =  $this->security->hash($pass);
                $user->created = time();
                $user->updated = time();                
                
                if($user->save()){
                    $this->flashSession->success("Se ha creado el usuario exitosamente.");
                    $this->trace("success","Se creo un usuario con ID: {$user->idUser}");
                    return $this->response->redirect("account/userlist/{$account->idAccount}");
                }
                else{
                    foreach($user->getMessages() as $message){
                        $this->flashSession->error($message);
                    }
                    $this->trace("fail","No se creo el usuario");
                }
            }
        }
        $this->view->UserForm = $form;
        $this->view->setVar('account', $account);
    }
    
    public function edituserAction($id)
    {
        $userExist = User::findFirst(array(
            "conditions" => "idUser = ?1",
            "bind" => array(1 => $id)
        ));
        
        if(!$userExist){
            $this->flashSession->error("El usuario que intenta editar no existe, por favor verifique la información");
            return $this->response->redirect("account/userlist");
        }                
        
        $this->view->setVar("user", $userExist);
        
        $userExist->address_user = $userExist->address;
        $userExist->name_user = $userExist->name;
        $userExist->city_user = $userExist->city;
        $userExist->state_user = $userExist->state;
        $userExist->phone_user = $userExist->phone;
        
        $form = new UserForm($userExist, $this->user->role);
        
        if($this->request->isPost()){
            $form->bind($this->request->getPost(), $userExist);
            
            $userExist->updated = time();
            $email = strtolower($form->getValue('email'));
            $userExist->email = $email;
            $userExist->name = $this->request->getPost('name_user');
            $userExist->phone = $this->request->getPost('phone_user');
            $userExist->address = $this->request->getPost('address_user');
            $userExist->state = $this->request->getPost('state_user');
            $userExist->city = $this->request->getPost('city_user');
            
            if($userExist->save()){
                $this->flashSession->success('Se ha editado exitosamente el usuario <strong>' .$userExist->userName. '</strong>');
                $this->trace("success","Se edito un usuario con ID: {$userExist->idUser}");
                return $this->response->redirect("account/userlist/{$userExist->idAccount}");
            }
            else{
                foreach ($userExist->getMessages() as $message) {
                    $this->flashSession->error($message);
                }
                $this->trace("fail","No se edito el usuario con ID: {$userExist->idUser}");
            }
        }
        $this->view->setVar("user", $userExist);
        $this->view->UserForm = $form;
    }
    
    public function deleteuserAction($id)
    {
        $idUser = $this->session->get('idUser');
        
        try {
            if($id == $idUser){
                $this->trace('fail', "Se intento borrar un usuario en sesión: {$idUser}");
                throw new InvalidArgumentException("No se puede eliminar el usuario que esta actualmente en sesión, por favor verifique la información");
            }
            
            $user = User::findFirst(array(
                "conditions" => "idUser = ?1",
                "bind" => array(1 => $id)
            ));
            
            if(!$user){
                $this->trace('fail', "El usuario no existe: {$idUser}");
                throw new InvalidArgumentException("El usuario que ha intentado eliminar no existe, por favor verifique la información");
            }
            
            if(!$user->delete()){
                foreach ($user->getMessages() as $msg){
                    $this->logger->log("Error while deleting user {$msg}, user: {$user->idUser}/{$user->userName}");                
                }
                throw new InvalidArgumentException("Ha ocurrido un error, contacte al administrador");
            }
            
            
            $this->flashSession->warning("Se ha eliminado el usuario <strong>{$user->userName}</strong> exitosamente");
            $this->trace('success', "Se elimino el usuario: {$id}");            
            return $this->response->redirect("user/index");
        } 
        catch (InvalidArgumentException $ex) {
            $this->flashSession->error($ex->getMessage());
            return $this->response->redirect("user/index");
        }
        catch (Exception $ex) {
            $this->logger->log("Error while deleting user {$idUser}, {$ex->getMessage()}");    
            $this->flashSession->error("Ha ocurrido un error al eliminar el usuario, es posible que este usuario tenga visitas registradas, por favor contacte al administrador");
            return $this->response->redirect("user/index");
        }
    }

        public function passedituserAction($id)
    {                
        $editpassUser = User::findFirst(array(
            "conditions" => "idUser = ?1",
            "bind" => array(1 => $id)
        ));
        
        if(!$editpassUser){
            $this->flashSession->error("El usuario que intenta editar no existe, por favor verifique la información");
            return $this->response->redirect("account/userlist");
        }
        
        $this->view->setVar("user", $editpassUser);
        
        if($this->request->isPost()){
            
            $pass = $this->request->getPost('pass1');
            $pass2 = $this->request->getPost('pass2');
            
            if((empty($pass)||empty($pass2))){
                $this->flashSession->error('El campo Contraseña esta vacío, por favor valide la información');
            }
            else if(($pass != $pass2)){
                $this->flashSession->error('Las contraseñas no coinciden');
            }
            else if(strlen($pass) < 8){
                $this->flashSession->error('La contraseña es muy corta, debe tener como minimo 8 caracteres');
            }
            else{
                $editUser->password = $this->security->hash($pass);
                $editUser->updated = time();
                
                if(!$editpassUser->save()){
                    foreach ($editpassUser->getMessages() as $message) {
                        $this->flashSession->error($message);
                    }
                    $this->trace("fail","No se edito la contraseña del usuario con ID: {$editpassUser->idUser}");
                }
                else{
                    $this->flashSession->success('Se ha editado la contraseña exitosamente del usuario <strong>' .$editpassUser->userName. '</strong>');
                    $this->trace("sucess","Se edito la contraseña del usuario con ID: {$editpassUser->idUser}");
                    return $this->response->redirect("account/userlist/{$editpassUser->idAccount}");
                }
            }
        }
    }
}