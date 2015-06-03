<?php

class UserController extends ControllerBase
{
    public function indexAction()
    {
        $currentPage = $this->request->getQuery('page', null, 1);
        
        $builder = $this->modelsManager->createBuilder()
            ->from('User')
//            ->where("User.idAccount = {$this->user->idAccount}")
            ->orderBy('User.created');

        $paginator = new Phalcon\Paginator\Adapter\QueryBuilder(array(
            "builder" => $builder,
            "limit"=> 15,
            "page" => $currentPage
        ));
		
        $page = $paginator->getPaginate();
		
        $this->view->setVar("page", $page);        
    }
    
    public function addAction($idAccount)
    {
        $user = new User();
        
        $obj = new stdClass();
        $obj->name = 'sudo';
        
        $form = new UserForm($user, $obj);
        
        $account = Account::findFirst(array(
            'conditions' => 'idAccount = ?1',
            'bind' => array(1 => $idAccount)
        ));
        
         if (!$account){
            $msg = "<div class='alert alert-warning'><span class='glyphicon glyphicon-remove'></span> La cuenta enviada no existe, por favor verifique la información.</div>";
            return $this->response->redirect("user/index");
        }
        
        if($this->request->isPost()){
            
            $form->bind($this->request->getPost(), $user);
            
            $username = $form->getValue('userName');
            $pass = $form->getValue('pass');
            $pass2 = $form->getValue('pass2');
            
            if($pass !== $pass2){
                $msg = "<div class='alert alert-warning'><span class='glyphicon glyphicon-remove'></span> Las contraseñas ingresas no coinciden, por favor intenrelo nuevamente.</div>";
            }
            else if(strlen($pass) < 8) {
                $msg = "<div class='alert alert-warning'><span class='glyphicon glyphicon-remove'></span> La contraseña es muy corta, debe tener minimo 8 caracteres.</div>";
            }
            else if(strlen($username) < 6){
                $msg = "<div class='alert alert-warning'><span class='glyphicon glyphicon-remove'></span> El nombre de usuario es muy corto, debe tener minimo 6 caracteres.</div>";
            }
            else{
                
                $email = strtolower($form->getValue('email'));
                
                $user->idAccount = $account->idAccount;
                $user->email = $email;
                $user->password =  $this->security->hash($pass);
                $user->created = time();
                $user->updated = time();
                $user->idRole = 2;
                
                if($user->save()){
                    $msg = "<div class='alert alert-success'><span class='glyphicon glyphicon-remove'></span> Se ha creado el usuario exitosamente.</div>";
//                    $this->trace("success","Se creo un usuario con ID: {$user->idUser}");
                    return $this->response->redirect("user/index/{$account->idAccount}");
                }
                else{
                    foreach($user->getMessages() as $message){
                        $this->notification->error($message);
                    }
//                    $this->trace("fail","No se creo el usuario a la cuenta {$account->idAccount}");
                }
            }
        }
        $this->view->UserForm = $form;
        $this->view->setVar('account', $account);
    }
}