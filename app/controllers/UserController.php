<?php

class UserController extends ControllerBase
{
    public function indexAction()
    {
        $currentPage = $this->request->getQuery('page', null, 1);
        
        $builder = $this->modelsManager->createBuilder()
            ->from('User')
            ->where("User.idAccount = {$this->user->idAccount}")
            ->orderBy('User.created');

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
        $user = new User();        
        $form = new UserForm($user, $this->user->role);
        
        if($this->request->isPost()){
            
            $form->bind($this->request->getPost(), $user);
            
            $username = $form->getValue('userName');
            $pass = $form->getValue('pass');
            $pass2 = $form->getValue('pass2');
            
            if($pass !== $pass2){
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
                $user->idAccount = $this->user->idAccount;
                $user->email = $email;
                $user->password =  $this->security->hash($pass);
                $user->created = time();
                $user->updated = time();                
                
                if($user->save()){
                    $this->flashSession->success("Se ha creado el usuario exitosamente.");
                    $this->trace("success","Se creo un usuario con ID: {$user->idUser}");
                    return $this->response->redirect("user/index");
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
    }
    
    public function editAction($id)
    {
        $account = $this->user->account;
        
        $editUser = User::findFirst(array(
            "conditions" => "idUser = ?1 AND idAccount = ?2",
            "bind" => array(1 => $id,
                            2 => $account->idAccount)
        ));
        
        if(!$editUser){
            $this->flashSession->error("El usuario que intenta editar no existe, por favor verifique la información");
            return $this->response->redirect("user/index");
        }
                
        $this->view->setVar("user", $editUser);
        
        $editUser->address_user = $editUser->address;
        $editUser->name_user = $editUser->name;
        $editUser->city_user = $editUser->city;
        $editUser->state_user = $editUser->state;
        $editUser->phone_user = $editUser->phone;
        
        $form = new UserForm($editUser, $this->user->role);
        
        if($this->request->isPost()){
            $form->bind($this->request->getPost(), $editUser);
            
            $editUser->updated = time();
            $email = strtolower($form->getValue('email'));
            $editUser->email = $email;
            $editUser->name = $this->request->getPost('name_user');
            $editUser->phone = $this->request->getPost('phone_user');
            $editUser->address = $this->request->getPost('address_user');
            $editUser->state = $this->request->getPost('state_user');
            $editUser->city = $this->request->getPost('city_user');
            
            if($editUser->save()){
                $this->flashSession->success('Se ha editado exitosamente el usuario <strong>' .$editUser->userName. '</strong>');
                $this->trace("success","Se edito un usuario con ID: {$editUser->idUser}");
                return $this->response->redirect("user/index");
            }
            else{
                foreach ($editUser->getMessages() as $message) {
                    $this->flashSession->error($message);
                }
                $this->trace("fail","No se edito el usuario con ID: {$editUser->idUser}");
            }
        }
        $this->view->setVar("user", $editUser);
        $this->view->UserForm = $form;
    }
    
    public function deleteAction($id)
    {
        $idUser = $this->session->get('idUser');
        
        if($id == $idUser){
            $this->flashSession->error("No se puede eliminar el usuario que esta actualmente en sesión, por favor verifique la información");
            $this->trace('fail', "Se intento borrar un usuario en sesión: {$idUser}");
            return $this->response->redirect("user/index");
        }
        
        $account = $this->user->account;
        
        $user = User::findFirst(array(
           "conditions" => "idUser = ?1 AND idAccount = ?2",
            "bind" => array(1 => $id,
                            2 => $account->idAccount)
        ));
        
        if(!$user){
            $this->flashSession->error("El usuario que ha intentado eliminar no existe, por favor verifique la información");
            $this->trace('fail', "El usuario no existe: {$idUser}");
            return $this->response->redirect("user/index");
        }
        
        if(!$user->delete()){
            foreach ($user->getMessages() as $msg){
                $this->flashSession->error($msg);
                $this->logger->log("Error while deleting user {$msg}, user: {$user->idUser}/{$user->userName}");                
            }
            return $this->response->redirect("user/index");
        }
        else{
            $this->flashSession->warning("Se ha eliminado el usuario <strong>{$user->userName}</strong> exitosamente");
            $this->trace('success', "Se elimino el usuario: {$id}");            
            return $this->response->redirect("user/index");
        }
    }
    
    public function passeditAction($id)
    {
        $account = $this->user->account;
        
        $editUser = User::findFirst(array(
            "conditions" => "idUser = ?1 AND idAccount = ?2",
            "bind" => array(1 => $id,
                            2 => $account->idAccount)
        ));
        
        if(!$editUser){
            $this->flashSession->error("El usuario que intenta editar no existe, por favor verifique la información");
            return $this->response->redirect("account/index");
        }
                
        $this->view->setVar("user", $editUser);
        
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
                
                if(!$editUser->save()){
                    foreach ($editUser->getMessages() as $message) {
                        $this->flashSession->error($message);
                    }
                    $this->trace("fail","No se edito la contraseña del usuario con ID: {$editUser->idUser}");
                }
                else{
                    $this->flashSession->success('Se ha editado la contraseña exitosamente del usuario <strong>' .$editUser->userName. '</strong>');
                    $this->trace("sucess","Se edito la contraseña del usuario con ID: {$editUser->idUser}");
                    return $this->response->redirect("user/index");
                }
            }
        }
    }
}