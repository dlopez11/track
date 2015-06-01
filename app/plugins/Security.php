<?php

use Phalcon\Events\Event,
        Phalcon\Mvc\User\Plugin,
        Phalcon\Mvc\Dispatcher,
        Phalcon\Acl;
/**
 * Security
 *
 * Este es la clase que proporciona los permisos a los usuarios. Esta clase decide si un usuario pueder hacer determinada
 * tarea basandose en el tipo de ROLE que posea
 */
class Security extends Plugin
{
    protected $serverStatus;
    protected $allowed_ips;
    protected $ip;

    public function __construct($dependencyInjector, $serverStatus = 0, $allowed_ips = null, $ip = null)
    {
        $this->_dependencyInjector = $dependencyInjector;
        $this->serverStatus = $serverStatus;
        $this->allowed_ips = $allowed_ips;
        $this->ip = $ip;
    }

    public function getAcl()
    {
        /*
         * Buscar ACL en cache
         */
        $acl = $this->cache->get('acl-cache');

        if (!$acl) {
                // No existe, crear objeto ACL
            $acl = $this->acl;
            $roles = Role::find();

            $modelManager = Phalcon\DI::getDefault()->get('modelsManager');

            $sql = "SELECT Resource.name AS resource, Action.name AS action 
                            FROM Action
                                    JOIN Resource ON (Action.idResource = Resource.idResource)";

            $results = $modelManager->executeQuery($sql);

            $allowed = $modelManager->executeQuery('SELECT Role.name AS rolename, Resource.name AS resname, Action.name AS actname
                                                                                                 FROM Allowed
                                                                                                        JOIN Role ON ( Role.idRole = Allowed.idRole ) 
                                                                                                        JOIN Action ON ( Action.idAction = Allowed.idAction ) 
                                                                                                        JOIN Resource ON ( Action.idResource = Resource.idResource ) ');

            //Registrando roles
            foreach ($roles as $role){
                $acl->addRole(new Phalcon\Acl\Role($role->name));
            }

            //Registrando recursos
            $resources = array();
            
            foreach ($results as $key) {
                if(!isset($resources[$key['resource']])){
                    $resources[$key['resource']] = array($key['action']);
                }
                else {
                    $resources[$key['resource']][] = $key['action'];
                }
            }

            foreach ($resources as $resource => $actions) {
                $acl->addResource(new Phalcon\Acl\Resource($resource), $actions);
            }

            //Relacionando roles y recursos desde la base de datos
            foreach($allowed as $role) {
                $acl->allow($role->rolename, $role->resname, $role->actname);
            }

            $this->cache->save('acl-cache', $acl);
        }

        // Retornar ACL
        $this->_dependencyInjector->set('acl', $acl);
        
        return $acl;
    }

    protected function getControllerMap()
    {
        $map = $this->cache->get('controllermap-cache');
        
        if (!$map) {
            $map = array(
            /* Public resources */    
                /* Error views */
                'error::index' => array(),
                'error::notavailable' => array(),
                'error::unauthorized' => array(),
                'error::forbidden' => array(),
                'error::link' => array(),
                /* Session */
                'session::login' => array(),
                'session::logout' => array(),
                'session::recoverpass' => array(),
                'session::resetpassword' => array(),
                'session::setnewpass' => array(),
                /* Comentarios de los usuarios */
                'suggestion::index' => array(),
                
            /* Private resources */
                /* Dashboard */
                'index::index' => array('dashboard' => array('read')),
                /* Account */
                'account::index' => array('account' => array('read')),
                'account::create' => array('account' => array('create','read')),
                'account::edit' => array('account' => array('update','read')),
                'account::delete' => array('account' => array('delete','read')),
                'account::userlist' => array('user' => array ('read'),
                                        'account' => array('read')),
                'account::usercreate' => array('user' => array ('read','create'),
                                        'account' => array('read')),
                'account::useredit' => array('user' => array ('read','update'),
                                        'account' => array('read')),
                'account::userdelete' => array('user' => array ('read','delete'),
                                        'account' => array('read')),
                'account::passedit' => array('user' => array ('read','update'),
                                        'account' => array('read')),
                /* Account Classification */
                'accountclassification::index' => array('accountclassification' => array('read')),
                'accountclassification::create' => array('accountclassification' => array('create','read')),
                'accountclassification::edit' => array('accountclassification' => array('update','read')),
                'accountclassification::delete' => array('accountclassification' => array('delete','read')),
                /* Adapter */
                'adapter::index' => array('adapter' => array('read')),
                'adapter::create' => array('adapter' => array('create','read')),
                'adapter::edit' => array('adapter' => array('update','read')),
                'adapter::delete' => array('adapter' => array('delete','read')),
                /* Api */
                'api::indexrole' => array('role' => array('read')),
                'api::newrole' => array('role' => array('create','read')),
                'api::editrole' => array('role' => array('update','read')),
                'api::deleterole' => array('role' => array('delete','read')),
                'api::indexresource' => array('resource' => array('read')),
                'api::newresource' => array('resource' => array('create','read')),
                'api::editresource' => array('resource' => array('update','read')),
                'api::deleteresource' => array('resource' => array('delete','read')),
                'api::indexaction' => array('action' => array('read')),
                'api::newaction' => array('action' => array('create','read')),
                'api::editaction' => array('action' => array('update','read')),
                'api::deleteaction' => array('action' => array('delete','read')),
                'api::indexpermissions' => array('permissions' => array('read')),
                'api::addpermissions' => array('permissions' => array('add','read')),
                /* Mail Class */
                'mailclass::index' => array('mailclass' => array('read')),
                'mailclass::create' => array('mailclass' => array('create','read')),
                'mailclass::edit' => array('mailclass' => array('update','read')),
                'mailclass::delete' => array('mailclass' => array('delete','read')),
                /* Marketing */
                'marketing::index' => array('marketing' => array('read')),
                /* Mta */
                'mta::index' => array('mta' => array('read')),
                'mta::create' => array('mta' => array('create','read')),
                'mta::edit' => array('mta' => array('update','read')),
                'mta::delete' => array('mta' => array('delete','read')),
                /* Permissions System */
                'permissionsystem::index' => array('permissions' => array('read')),
                /* Plantillas */
                'plantillas::index' => array('plantillas' => array('read')),
                'plantillas::default' => array('plantillas' => array('read')),
                /* Platform */
                'platform::index' => array('platform' => array('read')),
                'platform::create' => array('platform' => array('create','read')),
                'platform::edit' => array('platform' => array('update','read')),
                'platform::delete' => array('platform' => array('delete','read')),
                /* System */
                'system::index' => array('system' => array('read')),
                'system::configure' => array('system' => array('configure')),
                /* Tools */
                'tools::index' => array('tools' => array('read')),
                /* Url Domain */
                'urldomain::index' => array('urldomain' => array('read')),
                'urldomain::create' => array('urldomain' => array('create','read')),
                'urldomain::edit' => array('urldomain' => array('update','read')),
                'urldomain::delete' => array('urldomain' => array('delete','read')),
                /* User */
                'user::index' => array('user' => array('read')),
                'user::create' => array('user' => array('create','read')),
                'user::edit' => array('user' => array('update','read')),
                'user::delete' => array('user' => array('delete','read')),
                'user::passedit' => array('user' => array('update','read')),
                'session::superuser' => array('user' => array('superuser')),
                'session::logoutsuperuser' => array('user' => array('superuser')),
                /* System mail */
                'systemmail::index' => array('system-mail' => array('read')),
                'systemmail::create' => array('system-mail' => array('create')),
                'systemmail::delete' => array('system-mail' => array('delete')),
                'systemmail::edit' => array('system-mail' => array('update')),
                /* Mail */
                'mail::editor_frame' => array('mail' => array('create')),
                'mailpreview::preview' => array('mail' => array('preview')),
                'mailpreview::createpreview' => array('mail' => array('preview')),
                'mailpreview::previewdata' => array('mail' => array('preview')),
                /* Flash Messages */
                'flashmessage::index' => array('flashmessage' => array('read')),
                'flashmessage::create' => array('flashmessage' => array('create','read')),
                'flashmessage::edit' => array('flashmessage' => array('update','read')),
                'flashmessage::delete' => array('flashmessage' => array('delete','read')),
                /* Data Base */
                'dbase::index' => array('user' => array('read')),
                'dbase::create' => array('user' => array('create', 'read')),
                'dbase::edit' => array('user' => array('update', 'read')),
                'dbase::delete' => array('user' => array('delete', 'read')),
                /* Sending Category */
                'sendingcategory::index' => array('sendingcategory' => array('read')),
                'sendingcategory::create' => array('sendingcategory' => array('create','read')),
                'sendingcategory::edit' => array('sendingcategory' => array('update','read')),
                'sendingcategory::delete' => array('sendingcategory' => array('delete','read')),
            );
            
            $this->cache->save('controllermap-cache', $map);
        }
        
        return $map;
    }

    public function setJsonResponse($content, $status, $message) {
        $this->view->disable();

        $this->_isJsonResponse = true;
        $this->response->setContentType('application/json', 'UTF-8');
        $this->response->setStatusCode($status, $message);

        if (\is_array($content)) {
            $content = \json_encode($content);
        }
        $this->response->setContent($content);
        return $this->response;
    }

    protected function validateResponse($controller, $action = null){
        $controllersWithjsonResponse = array ('api');
        if(\in_array($controller, $controllersWithjsonResponse)){
            return true;
        }
        return false;
    }

    /**
     * This action is executed before execute any action in the application
     */
    public function beforeDispatch(Event $event, Dispatcher $dispatcher)
    {
        $controller = \strtolower($dispatcher->getControllerName());
        $action = \strtolower($dispatcher->getActionName());
        $resource = "$controller::$action";

        if ($this->serverStatus == 0 && !\in_array($this->ip, $this->allowed_ips)) {
            $this->publicurls = array(
                'error::index',
                'error::link',
                'error::notavailable',
                'error::unauthorized',
                'error::forbidden',
            );

            if (!\in_array($resource, $this->publicurls)) {
                return $this->response->redirect('error/notavailable');
            }
            
//            return false;
        }
        else {
            $role = 'GUEST';
            if ($this->session->get('authenticated')) {
                $user = User::findFirstByIdUser($this->session->get('idUser'));
                if ($user) {
                    $role = $user->role->name;
                    
                    $userEfective = new stdClass();
                    $userEfective->enable = false;

                    $efective = $this->session->get('userEfective');
                    if (isset($efective)) {
                        $userEfective->enable = true;
                        $role = $efective->role->name;
                        $user->role = $efective->role;
                    }
                    // Inyectar el usuario
                    $this->_dependencyInjector->set('userData', $user);
                    $this->_dependencyInjector->set('userEfective', $userEfective);
                }
            }
	
            $map = $this->getControllerMap();

            $this->publicurls = array(
                 /* Error views */
                'error::index',
                'error::notavailable',
                'error::unauthorized',
                'error::forbidden',
                'error::link',
                /* Session */
                'session::login',
                'session::logout',
                'session::recoverpass',
                'session::resetpassword',
                'session::setnewpass'
            );

            if ($role == 'GUEST') {
                if ($resource == "error::notavailable") {
                    $this->response->redirect("index");
                    return false;
                }
                else if (!in_array($resource, $this->publicurls)) {
                    $this->response->redirect("session/login");
                    return false;
                }
            }
            else {
                $account = Account::findFirstByIdAccount($user->idAccount);
                
                if ($resource == 'session::login') {
                    $this->response->redirect("index");
                    return false;
                }
                else if ($account->status == 0 && $resource !== 'error::unauthorized') {
                    $this->response->redirect("error/unauthorized");
                    return false;
                }
                else {
                    $acl = $this->getAcl();
                    $this->logger->log("Validando el usuario con rol [$role] en [$resource]");

                    if (!isset($map[$resource])) {
                        if($this->validateResponse($controller) == true){
                            $this->logger->log("El recurso no se encuentra registrado en el sistema de API's");
                            $this->setJsonResponse(array('status' => 'deny'), 403, 'Accion no permitida');
                        }
                        else{
                            $this->logger->log("El recurso no se encuentra registrado");
                            $dispatcher->forward(array('controller' => 'error', 'action' => 'index'));
                        }
                        return false;
                    }

                    $reg = $map[$resource];

                    foreach($reg as $resources => $actions){
                        foreach ($actions as $act) {
                            if (!$acl->isAllowed($role, $resources, $act)) {
                                if($this->validateResponse($controller) == true){
                                    $this->logger->log("Acceso al sistema de API's denegado");
                                    $this->setJsonResponse(array('status' => 'deny'), 403, 'Accion no permitida');
                                }
                                else{
                                    $this->logger->log('Acceso denegado');
                                    $dispatcher->forward(array('controller' => 'error', 'action' => 'forbidden'));
                                }
                                return false;
                            }
                        }
                    }

                    $mapForLoginLikeAnyUser = array('session::superuser');
//
                    if (in_array($resource, $mapForLoginLikeAnyUser)) {
                        $this->session->set('userEfective', $user);
                    }

                    return true;
                }
            }
        }
    }	
}
