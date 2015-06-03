<?php

class SessionController extends ControllerBase
{
    public function loginAction()
    {
        if ($this->request->isPost()) {
            $msg = "Usuario o contraseña incorrecta";
//            if ($this->security->checkToken()) {
                $username = $this->request->getPost("username");
                $password = $this->request->getPost("password");

                $user = User::findFirst(array(
                    "userName = ?0",
                    "bind" => array($username)
                ));

                if ($user && $this->hash->checkHash($password, $user->password)) {
                    $this->session->set('idUser', $user->idUser);
                    $this->session->set('authenticated', true);
//
//                    $this->user = $user;
//                    $this->trace("success", "User: {$username} login");
                    return $this->response->redirect("");
                }
                else {
//                    $this->trace("fail", "Access denied username: {$username}, password: [{$password}]");
                }

                $this->flashSession->error($msg);
                return $this->response->redirect('session/login');    
//            }
        }
    }
    
    public function logoutAction()
    {
        $this->session->destroy();
        return $this->response->redirect('session/login');
    }
    
    public function recoverpassAction()
    {
        if($this->request->isPost()){
            $email = $this->request->getPost('email');
                
            $user = User::findFirst(array(
                'condition' => 'email = ?1',
                'bind' => array(1 => $email)                
            ));
            
            try {
                if($user){
                    $cod = uniqid();
                    $urlManager = $urlManager = Phalcon\DI::getDefault()->get('urlManager');
                    $url = $urlManager->get_base_uri(true);
                    $url.= 'session/resetpassword/' . $cod;

                    $recoverObj = new Tmprecoverpass();
                    $recoverObj->idTmprecoverpass = $cod;
                    $recoverObj->idUser = $user->idUser;
                    $recoverObj->url = $url;
                    $recoverObj->date = time();

                    if(!$recoverObj->save()){
                        foreach ($recoverObj->getMessages() as $msg){
                            throw new Exception($msg);
                        }
                    }
                    
                    $mail = Systemmail::findFirst(array(
                        'conditions' => 'category = ?1',
                        'bind' => array(1 => 'recover-password')
                    ));


                    if ($mail) {
                        $data = new stdClass();
                        $data->fromName = $mail->fromName;
                        $data->fromEmail = $mail->fromEmail;
                        $data->subject = $mail->subject;
                        $data->target = array($user->email);

                        $editorObj = new \Sigmamovil\Logic\Editor\HtmlObj();
                        $editorObj->assignContent(json_decode($mail->content));
                        $content = $editorObj->render();

                        $html = str_replace("tmp-url", $url, $content);
                        $plainText = str_replace("tmp-url", $url, $mail->plainText);
                    }
                    else {
                        $data = new stdClass();
                        $data->fromName = "soporte@sigmamovil.com";
                        $data->fromEmail = "Soporte Sigma Móvil";
                        $data->subject = "Instrucciones para recuperar la contraseña de Sigma Móvil All In One";
                        $data->target = array($user->email);
                        
                        $content = '<table style="background-color: #E6E6E6; width: 100%;"><tbody><tr><td style="padding: 20px;"><center><table style="width: 600px;" width="600px" cellspacing="0" cellpadding="0"><tbody><tr><td style="width: 100%; vertical-align: top; padding:0; background-color: #FFFFFF; border-top-left-radius: 0px; border-top-right-radius: 0px; border-bottom-right-radius: 0px; border-bottom-left-radius: 0px; border-color: #FFFFFF; border-style: none; border-width: 0px;"><table style="table-layout: fixed; width:100%; border-spacing: 0px;" width="100%" cellpadding="0"><tbody></tbody></table></td></tr><tr><td style="width: 100%; vertical-align: top; padding:0; background-color: #FFFFFF; border-top-left-radius: 0px; border-top-right-radius: 0px; border-bottom-right-radius: 0px; border-bottom-left-radius: 0px; border-color: #FFFFFF; border-style: none; border-width: 0px;"><table style="table-layout: fixed; width:100%; border-spacing: 0px;" width="100%" cellpadding="0"><tbody><tr><td style="padding-left: 0px; padding-right: 0px;"><table style="border-color: #FFFFFF; border-style: none; border-width: 0px; background-color: transparent; border-top-left-radius: 0px; border-top-right-radius: 0px; border-bottom-right-radius: 0px; border-bottom-left-radius: 0px; margin-top: 0px; margin-bottom: 0px; width:100%; border-spacing: 0px" cellpadding="0" width="100%"><tbody><tr><td style="width: 100%; padding-left: 0px; padding-right: 0px;" width="100%"><table style="border-color: #FFFFFF; border-style: none; border-width: 0px; background-color: transparent; border-top-left-radius: 0px; border-top-right-radius: 0px; border-bottom-right-radius: 0px; border-bottom-left-radius: 0px; margin-top: 0px; margin-bottom: 0px; width: 100%;" cellpadding="0" width="100%"><tbody><tr><td style="word-break: break-word; padding: 15px 15px; font-family: Helvetica, Arial, sans-serif;"><p></p><h2><span data-redactor="verified" data-redactor-inlinemethods="" style="color: rgb(227, 108, 9); font-family: Trebuchet MS, sans-serif;">Estimado usuario:</span></h2></td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table></td></tr><tr><td style="width: 100%; vertical-align: top; padding:0; background-color: #FFFFFF; border-top-left-radius: 0px; border-top-right-radius: 0px; border-bottom-right-radius: 0px; border-bottom-left-radius: 0px; border-color: #FFFFFF; border-style: none; border-width: 0px;"><table style="table-layout: fixed; width:100%; border-spacing: 0px;" width="100%" cellpadding="0"><tbody><tr><td style="padding-left: 0px; padding-right: 0px;"><table style="border-color: #FFFFFF; border-style: none; border-width: 0px; background-color: transparent; border-top-left-radius: 0px; border-top-right-radius: 0px; border-bottom-right-radius: 0px; border-bottom-left-radius: 0px; margin-top: 0px; margin-bottom: 0px; width:100%; border-spacing: 0px" cellpadding="0" width="100%"><tbody><tr><td style="width: 100%; padding-left: 0px; padding-right: 0px;" width="100%"><table style="border-color: #FFFFFF; border-style: none; border-width: 0px; background-color: transparent; border-top-left-radius: 0px; border-top-right-radius: 0px; border-bottom-right-radius: 0px; border-bottom-left-radius: 0px; margin-top: 0px; margin-bottom: 0px; width: 100%;" cellpadding="0" width="100%"><tbody><tr><td style="word-break: break-word; padding: 15px 15px; font-family: Helvetica, Arial, sans-serif;"><p></p><p><span data-redactor="verified" data-redactor-inlinemethods="" style="font-family: Trebuchet MS, sans-serif;">Usted ha solicitado recuperar la contraseña de su usuario para ingresar a nuestra plataforma. Para finalizar este proceso, por favor, visite el siguiente enlace:</span></p></td></tr></tbody></table></td></tr></tbody></table></td></tr><tr><td style="padding-left: 0px; padding-right: 0px;"><table style="border-color: #FFFFFF; border-style: none; border-width: 0px; background-color: transparent; border-top-left-radius: 0px; border-top-right-radius: 0px; border-bottom-right-radius: 0px; border-bottom-left-radius: 0px; margin-top: 0px; margin-bottom: 0px; width:100%; border-spacing: 0px" cellpadding="0" width="100%"><tbody><tr><td style="width: 100%; padding-left: 0px; padding-right: 0px;" width="100%"><table style="border-color: #FFFFFF; border-style: none; border-width: 0px; background-color: transparent; border-top-left-radius: 0px; border-top-right-radius: 0px; border-bottom-right-radius: 0px; border-bottom-left-radius: 0px; margin-top: 0px; margin-bottom: 0px; width: 100%;" cellpadding="0" width="100%"><tbody><tr><td style="word-break: break-word; padding: 15px 15px; font-family: Helvetica, Arial, sans-serif;"><p><span data-redactor="verified" data-redactor-inlinemethods="" style="color: rgb(54, 96, 146); font-family: Trebuchet MS, sans-serif; font-size: 18px;">tmp-url</span></p></td></tr></tbody></table></td></tr></tbody></table></td></tr><tr><td style="padding-left: 0px; padding-right: 0px;"><table style="border-color: #FFFFFF; border-style: none; border-width: 0px; background-color: transparent; border-top-left-radius: 0px; border-top-right-radius: 0px; border-bottom-right-radius: 0px; border-bottom-left-radius: 0px; margin-top: 0px; margin-bottom: 0px; width:100%; border-spacing: 0px" cellpadding="0" width="100%"><tbody><tr><td style="width: 100%; padding-left: 0px; padding-right: 0px;" width="100%"><table style="border-color: #FFFFFF; border-style: none; border-width: 0px; background-color: transparent; border-top-left-radius: 0px; border-top-right-radius: 0px; border-bottom-right-radius: 0px; border-bottom-left-radius: 0px; margin-top: 0px; margin-bottom: 0px; width: 100%;" cellpadding="0" width="100%"><tbody><tr><td style="word-break: break-word; padding: 15px 15px; font-family: Helvetica, Arial, sans-serif;"><p></p><p><span data-redactor="verified" data-redactor-inlinemethods="" style="font-family: Trebuchet MS, sans-serif;">Si no ha solicitado ningún cambio, simplemente ignore este mensaje. Si tiene cualquier otra pregunta acerca de su cuenta, por favor, use nuestras Preguntas frecuentes o contacte con nuestro equipo de asistencia en&nbsp;</span><span style="color: rgb(227, 108, 9); font-family: Trebuchet MS, sans-serif; background-color: initial;">soporte@sigmamovil.com.</span></p></td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table></td></tr><tr><td style="width: 100%; vertical-align: top; padding:0; background-color: #FFFFFF; border-top-left-radius: 0px; border-top-right-radius: 0px; border-bottom-right-radius: 0px; border-bottom-left-radius: 0px; border-color: #FFFFFF; border-style: none; border-width: 0px;"><table style="table-layout: fixed; width:100%; border-spacing: 0px;" width="100%" cellpadding="0"><tbody></tbody></table></td></tr></tbody></table></center></td></tr></tbody></table>';

                        $html = str_replace("tmp-url", $url, $content);
                        $plainText = $url;
                    }


                    $mailSender = new \Sigmamovil\General\Misc\MailSender();
                    $mailSender->setData($data);
                    $mailSender->setHtml($html);
                    $mailSender->setPlainText($plainText);
                    $mailSender->sendBasicMail();
                        
                    $this->trace("success", "Se han enviado instrucciones para recuperar contraseña al usuario {$user->idUser}/{$user->username} con email {$email} ");
                }
                else {
                    $this->trace("fail", "No se logro recuperar la contraseña del usuario [{$user->idUser}], [{$email}]");
                }
                
                $this->notification->success('Se ha enviado un correo electronico con instrucciones para recuperar la contraseña');
                return $this->response->redirect('session/login');
            } 
            catch (Exception $ex) {
                $this->trace("fail", "No se logro recuperar la contraseña del usuario [{$user->idUser}], [{$email}]");
                $this->notification->error($ex->getMessage());
            }
        }        
    }
    
    public function resetpasswordAction($unique)
    {
        $url = Tmprecoverpass::findFirst(array(
            'conditions' => 'idTmprecoverpass = ?1',
            'bind' => array(1 => $unique)
        ));
        
        $time = strtotime("-30 minutes");
        
        if($url && ($url->date <= $time || $url->date >= $time)){
            $this->session->set('idUser', $url->idUser);
            $this->view->setVar('uniq', $unique);
        }
        else{
            $this->trace("fail","No se recupero la contraseña por que el link es invalido, no existe o expiro el ID: {$unique}");
            return $this->response->redirect('error/link');
        }
    }
    
    public function setnewpassAction()
    {
        if($this->request->isPost()){
            
            $uniq = $this->request->getPost("uniq");
            
            $url = Tmprecoverpass::findFirst(array(
                'confitions' => "idTmprecoverpass = ?1",
                'bind' => array(1 => $uniq)
            ));
            
            $time = strtotime("-30 minutes");
            
            if($url && $url->date >= $time){
                
                $pass = $this->request->getPost("pass1");
                $pass2 = $this->request->getPost("pass2");
                
                if(empty($pass) || empty($pass2)){
                    $this->notification->error("Ha enviado campos vacíos, por favor verifique la información");
                    $this->dispatcher->forward(array(
                        "controller" => "session",
                        "action" => "resetpassword",
                        "params" => array($uniq)
                    ));                    
                }
                else if(strlen($pass) < 8 || strlen($pass) > 40){
                    $this->notification->error("La contraseña es muy corta o muy larga, esta debe tener mínimo 8 y máximo 40 caracteres, por favor verifique la información");
                    $this->dispatcher->forward(array(
                        "controller" => "session",
                        "action" => "resetpassword",
                        "params" => array($uniq)
                    ));
                }
                else if($pass !== $pass2){
                    $this->notification->error("Las contraseñas no coinciden, por favor verifique la información");
                    $this->dispatcher->forward(array(
                        "controller" => "session",
                        "action" => "resetpassword",
                        "params" => array($uniq)
                    ));
                }
                else{
                    $idUser = $this->session->get('idUser');
                    
                    $user = User::findFirst(array(
                        'conditions' => 'idUser = ?1',
                        'bind' => array(1 => $idUser)
                    ));
                    
                    if($user){
                        $user->password = $this->security->hash($pass);
                        
                        if(!$user->save()){
                            $this->notification->error('Ha ocurrido un error, contacte con el administrador');
                            foreach ($user->getMessages() as $msg){
                                $this->logger->log('Error while recovering user password' . $msg);
                                $this->logger->log("User {$user->idUser}/{$user->username}");
                                $this->trace("fail","Fallo la recuperación de contraseña");
                                $this->notification->error('Ha ocurrido un error, por favor contacte al administrador');
                            }
                        }
                        else{
                            $idUser = $this->session->remove('idUser');
                            $url->delete();
                            $this->notification->success('Se ha actualizado el usuario correctamente');
                            $this->trace("success","Se recupero la contraseña del usuario {$user->idUser}/{$user->username}");
                            return $this->response->redirect('session/login');
                        }                        
                    }
                    else{
                        $this->trace("fail", "No se recupero la contraseña por que el usuario no existe");
                        return $this->response->redirect('error/link');
                    }
                }
            }
            else{
                $this->trace("fail","No se recupero la contraseña por que el link es invalido, no existe o expiro ID: {$uniq}");
                return $this->response->redirect('error/link');
            }
        }
    }  
    
    public function superuserAction($idUser)
    {
        $user = User::findFirst(array(
            'conditions' => 'idUser = ?1',
            'bind' => array(1 => $idUser)
        ));

        if (!$user) {
            $this->notification->error("No se ha podido ingresar como el usuario, porque este no existe");
            return $this->response->redirect('account/index');
        }

        $this->session->set('idUser', $user->idUser);
        $this->session->set('authenticated', true);

        $u = $this->user;
        
        $this->user = $user;
        $this->user->role = $u->role;

        $uefective = $this->session->get('userEfective');
        
        $this->trace("success", "Login by superuser: {$uefective->username} / {$uefective->idUser}, in account {$this->user->account->idAccount} with user {$this->user->username} / {$this->user->idUser}");
        return $this->response->redirect("");
    }
    
    public function logoutsuperuserAction()
    {
        $uefective = $this->session->get('userEfective');
        $olduser = $this->user;
        $oldAccount = $this->user->account;

        if (isset($uefective)) {
            $this->session->set('idUser', $uefective->idUser);
            $this->session->set('authenticated', true);

            $this->user = $uefective;

            $this->session->remove('userEfective');
            $this->trace("success", "Logout by superuser: {$uefective->username} / {$uefective->idUser}, in account {$oldAccount->idAccount} with user {$olduser->username} / {$olduser->idUser}");
            return $this->response->redirect("account/userlist/{$oldAccount->idAccount}");
        }
        else {
            return $this->response->redirect("error/unauthorized");
        }
    }
}