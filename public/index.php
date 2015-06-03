<?php

try {
    $config = new Phalcon\Config\Adapter\Ini('../app/config/configuration.ini');
    //Register an autoloader
    $loader = new \Phalcon\Loader();
    $loader->registerDirs(array(
        '../app/controllers/',
        '../app/models/',
        '../app/forms/',
        '../app/views/',
        '../app/plugins/',
        '../app/validators/',
        '../app/wrappers/',
    ));
    
    
    $loader->registerNamespaces(array(
        'Sigmamovil\Misc' => '../app/misc/'
    ), true);
    
    $loader->register();
    //Create a DI
    $di = new Phalcon\DI\FactoryDefault();
    
   //Registering Volt as template engine
    $di->set('view', function() {
        $view = new \Phalcon\Mvc\View();
        $view->setViewsDir('../app/views/');
        $view->registerEngines(array(
                ".volt" => 'volt'
        ));
        return $view;
    });
    
    
    $di->setShared('volt', function($view, $di) {
        $volt = new \Phalcon\Mvc\View\Engine\Volt($view, $di);

        $volt->setOptions(array(
                "compileAlways" => true,
                "compiledPath" => "../app/compiled-templates/",
                'stat' => true
        ));

        $compiler = $volt->getCompiler();
        return $volt;
    });
    
                
    //Setup a base URI so that all generated URIs include the "tutorial" folder
    $di->set('url', function(){
        $url = new \Phalcon\Mvc\Url();
        $url->setBaseUri('/track/');
        return $url;
    });
    
    $di->setShared('session', function() {
        $session = new Phalcon\Session\Adapter\Files();
        $session->start();
        return $session;
    });
    
    $di->set('router', function () {
        $router = new \Phalcon\Mvc\Router\Annotations();
        $router->addResource('Api', '/api');
        return $router;
    });
    
    $di->set('db', function() use ($config) {
        return new \Phalcon\Db\Adapter\Pdo\Mysql(array(
            "host" => $config->database->host,
            "username" => $config->database->username,
            "password" => $config->database->password,
            "dbname" => $config->database->dbname
        ));
    });
    
    $di->set('dispatcher', function() use ($di) {
        $dispatcher = new Phalcon\Mvc\Dispatcher();
        return $dispatcher;
    });
    
    $di->set('hash', function(){
        $hash = new \Phalcon\Security();
        //Set the password hashing factor to 12 rounds
        $hash->setWorkFactor(12);
        return $hash;
    }, true);
    
    $di->setShared('db', function() use ($di, $config) {
        // Events Manager para la base de datos
        $eventsManager = new \Phalcon\Events\Manager();
        $connection = new \Phalcon\Db\Adapter\Pdo\Mysql($config->database->toArray());
        $connection->setEventsManager($eventsManager);
        
        return $connection;
    });
              
    $di->set('modelsManager', function(){
        return new \Phalcon\Mvc\Model\Manager();
    });
    
    
    $di->set('logger', function () {
        // Archivo de log
        return new \Phalcon\Logger\Adapter\File("../app/logs/debug.log");
    });
    
    $urlManager = new \Sigmamovil\Misc\UrlManager($config);
	
    $di->set('urlManager', $urlManager);   
                
    $di->set('flashSession', function(){
        $flash = new \Phalcon\Flash\Session(array(
            'error' => 'alert alert-danger text-center',
            'success' => 'alert alert-success text-center',
            'notice' => 'alert alert-info text-center',
            'warning' => 'alert alert-warning text-center',
        ));
        
        return $flash;
    });
    
    /*
    * Este objeto se encarga de crear el menú principal sidebar de la izquierda
    */
    $di->set('smartMenu', function(){
        return new \Sigmamovil\Misc\SmartMenu();
    });
	
	/*
    * Información del sistema
    */
    $system = new \stdClass;
    $system->status = $config->system->status;
    $system->ipaddresses = $config->system->ipaddresses;
    $di->set('system', $system);
	
    /**
     * Se encarga de monitorear los accesos a los controladores y acciones, y asi mismo pasarle los parametros
     * de seguridad a security 
     */
     $di->set('dispatcher', function() use ($di) {
     	$eventsManager = $di->getShared('eventsManager');
     	$security = new \Security($di);
        /**
         * We listen for events in the dispatcher using the Security plugin
         */
        $eventsManager->attach('dispatch', $security);

        $dispatcher = new \Phalcon\Mvc\Dispatcher();
        $dispatcher->setEventsManager($eventsManager);

        return $dispatcher;
    });
    
    // Ruta de APP
    $apppath = realpath('../');
    $di->set('appPath', function () use ($apppath) {
        $obj = new \stdClass;
        $obj->path = $apppath;

        return $obj;
    });
    
    $path = new \stdClass();
    $path->path = $config->general->path;
    $path->tmpfolder = $config->general->tmp;
    $di->set('path', $path);
    
    $di->set('hash', function(){
        $hash = new \Phalcon\Security();
        //Set the password hashing factor to 12 rounds
        $hash->setWorkFactor(12);
        return $hash;
    }, true);
         
    
    $di->set('cache', function () use ($config){
        $frontCache = new \Phalcon\Cache\Frontend\Data(array(
            "lifetime" => 172800
        ));

        if (class_exists('Memcache')) {
            $cache = new \Phalcon\Cache\Backend\Memcache($frontCache, array(
                "host" => "localhost",
                "port" => "11211"
            ));
        }
        else {
            $cache = new \Phalcon\Cache\Backend\File($frontCache, array(
                "cacheDir" => $config->cache->acldir
            ));
        }
        return $cache;
    });
    
    
    $di->set('acl', function(){
        $acl = new \Phalcon\Acl\Adapter\Memory();
        $acl->setDefaultAction(\Phalcon\Acl::DENY);

        return $acl;
    });

    //Handle the request
    $application = new \Phalcon\Mvc\Application($di);

    echo $application->handle()->getContent();
} 
catch(\Phalcon\Exception $e) {
     echo "PhalconException: ", $e->getMessage();
}