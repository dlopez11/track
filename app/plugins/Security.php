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
    public function __construct($dependencyInjector)
    {
        $this->_dependencyInjector = $dependencyInjector;
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
            
            //Registrando roles
            foreach ($roles as $role){
                $acl->addRole(new Phalcon\Acl\Role($role->name));
            }

            //Registrando recursos
            $resources = array(
                'dashboard' => array('read'),
                'account' => array('create', 'read', 'update', 'change-status'),
                'user' => array('create', 'read', 'update', 'delete', 'sudo'),
                'visittype' => array('create', 'read', 'update', 'delete'),
                'client' => array('create', 'read', 'update', 'delete'),
                'visit' => array('read'),
                'statistic' => array('read'),
                'report' => array('download'),
            );
            
            foreach ($resources as $resource => $actions) {
                $acl->addResource(new Phalcon\Acl\Resource($resource), $actions);
            }

            // Sudo
            $acl->allow("sudo", "dashboard", "read");
            $acl->allow("sudo", "account", "create");
            $acl->allow("sudo", "account", "read");
            $acl->allow("sudo", "account", "update");
            $acl->allow("sudo", "account", "change-status");
            $acl->allow("sudo", "user", "create");
            $acl->allow("sudo", "user", "read");
            $acl->allow("sudo", "user", "update");
            $acl->allow("sudo", "user", "delete");
            $acl->allow("sudo", "user", "sudo");
            $acl->allow("sudo", "visittype", "create");
            $acl->allow("sudo", "visittype", "read");
            $acl->allow("sudo", "visittype", "update");
            $acl->allow("sudo", "visittype", "delete");
            $acl->allow("sudo", "client", "create");
            $acl->allow("sudo", "client", "read");
            $acl->allow("sudo", "client", "update");
            $acl->allow("sudo", "client", "delete");
            $acl->allow("sudo", "visit", "read");
            $acl->allow("sudo", "statistic", "read");
            $acl->allow("sudo", "report", "download");
            
            // admin
            $acl->allow("admin", "dashboard", "read");
            $acl->allow("admin", "user", "create");
            $acl->allow("admin", "user", "read");
            $acl->allow("admin", "user", "update");
            $acl->allow("admin", "user", "delete");
            $acl->allow("admin", "visittype", "create");
            $acl->allow("admin", "visittype", "read");
            $acl->allow("admin", "visittype", "update");
            $acl->allow("admin", "visittype", "delete");
            $acl->allow("admin", "client", "create");
            $acl->allow("admin", "client", "read");
            $acl->allow("admin", "client", "update");
            $acl->allow("admin", "client", "delete");
            $acl->allow("admin", "visit", "read");
            $acl->allow("admin", "statistic", "read");
            $acl->allow("admin", "report", "download");
            
            // user
            $acl->allow("user", "visittype", "read");
            $acl->allow("user", "client", "create");
            $acl->allow("user", "client", "read");

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
                'session::validate' => array(),
                'session::logout' => array(),
                'session::recoverpass' => array(),
                'session::resetpassword' => array(),
                'session::setnewpass' => array(),
                
            /* Private resources */
                /* Dashboard */
                'index::index' => array('dashboard' => array('read')),
                /* Account */
                'account::index' => array('account' => array('read')),
                'account::userlist' => array('user' => array ('read'),
                                        'account' => array('read')),
                'account::newuser' => array('user' => array ('read','create'),
                                        'account' => array('read')),
                'account::edituser' => array('user' => array ('read','update'),
                                        'account' => array('read')),
                'account::passedituser' => array('user' => array ('read','update'),
                                        'account' => array('read')),
                'account::deleteuser' => array('user' => array ('read','delete'),
                                        'account' => array('read')),
                'account::add' => array('account' => array('create','read')),
                'account::edit' => array('account' => array('update','read')),
                /* User */
                'user::index' => array('user' => array('read')),
                'user::add' => array('user' => array('create','read')),
                'user::edit' => array('user' => array('update','read')),
                'user::delete' => array('user' => array('delete','read')),
                'user::passedit' => array('user' => array('update','read')),
                /* Client */
                'client::index' => array('client' => array('read')),
                'client::add' => array('client' => array('create','read')),
                'client::edit' => array('client' => array('update','read')),
                'client::remove' => array('client' => array('delete')),
                /* Visittype */
                'visittype::index' => array('visittype' => array('read')),
                'visittype::add' => array('visittype' => array('create','read')),
                'visittype::edit' => array('visittype' => array('update','read')),
                'visittype::remove' => array('visittype' => array('delete')),
                /* Visit */
                'visit::index' => array('visit' => array('read')),
                'visit::map' => array('visit' => array('read')),
                'visit::getmap' => array('visit' => array('read')),
                'visit::getrows' => array('visit' => array('read')),
                'visit::maphistory' => array('visit' => array('read')),
                'session::superuser' => array('user' => array('sudo')),
                'session::logoutsuperuser' => array('user' => array('sudo')),
                /* Estadisticas */
                'statistic::index' => array('statistic' => array('read')),
                'statistic::getdata' => array('statistic' => array('read')),
            );
            
            $this->cache->save('controllermap-cache', $map);
        }
        
        return $map;
    }

    /**
     * This action is executed before execute any action in the application
     */
    public function beforeDispatch(Event $event, Dispatcher $dispatcher)
    {
        $controller = \strtolower($dispatcher->getControllerName());
        $action = \strtolower($dispatcher->getActionName());
        $resource = "$controller::$action";

       
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
            /* Session */
            'session::login',
            'session::validate',
            'session::logout',
            'session::recoverpass',
            'session::resetpassword',
            'session::setnewpass'
        );

        if ($role == 'GUEST') {
            if (!in_array($resource, $this->publicurls)) {
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
                    $this->logger->log("El recurso no se encuentra registrado");
                    $dispatcher->forward(array('controller' => 'error', 'action' => 'index'));
                    return false;
                }

                $reg = $map[$resource];

                foreach($reg as $resources => $actions){
                    foreach ($actions as $act) {
                        if (!$acl->isAllowed($role, $resources, $act)) {
                            $this->logger->log('Acceso denegado');
                            $dispatcher->forward(array('controller' => 'error', 'action' => 'forbidden'));
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
