<?php

try {
    //Register an autoloader
    $loader = new \Phalcon\Loader();
    $loader->registerDirs(array())->register();

    $loader->registerNamespaces(array(
        'Sigmamovil\Misc' => '../app/misc/'
    ), true);

    //Create a DI
    $di = new Phalcon\DI\FactoryDefault();


    /* Configuracion */
    $config = new \Phalcon\Config\Adapter\Ini("../../config/configuration.ini");

    $di->set('logger', function () {
        // Archivo de log
        return new \Phalcon\Logger\Adapter\File("../../app/logs/debug.log");
    });

    /*
     * Cache, se encarga de comunicarse con Memcache
     */
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
    //$application = new \Phalcon\Mvc\Application($di);
} 
catch(\Phalcon\Exception $e) {
     echo "PhalconException: ", $e->getMessage();
}