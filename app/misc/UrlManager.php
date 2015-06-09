<?php

namespace Sigmamovil\Misc;

class UrlManager
{
    protected $url_manager;
    protected $protocol;
    protected $host;
    protected $port;
    protected $url_base;
    protected $app_base_trailing;

    /**
     * Cargamos los datos del archivo de configuración si existe
     * @param type $config
     */
    public function __construct($config)
    {	

        if (isset($config->urlmanager)) {
            $this->protocol = $config->urlmanager->protocol;
            $this->host = $config->urlmanager->host;
            $this->port = $config->urlmanager->port;
            if ($config->urlmanager->urlbase != '') {
                $this->app_base = $config->urlmanager->urlbase . '/';
            }
            else {
                $this->app_base = $config->urlmanager->urlbase;
            }
        }
        else {
            $this->protocol = "http";
            $this->host = "localhost";
            $this->port = 80;
            $this->url_base = "track";
            $this->app_base = "track";
        }
        
        $this->app_base_trailing = $this->app_base . ($this->app_base == ''?'/':'');
    }

    /**
     * Crea el prefijo de la URL, si se le pasa true, retorna el prefijo con protocolo ejemplo: http://localhost/ ,
     * si se le pasa false retorna vacio
     * @param boolean $full
     * @return string
     */
    protected function get_prefix($full = false)
    {
        if ($full) {
            $prefix = $this->protocol . '://' .$this->host . '/';
        }
        else {
            $prefix = '';
        }
        
        return $prefix;
    }

    /**
     * Returns the url base ex: "emarketing" and url full ex: "http://localhost/aio"
     * @return type
     */
    public function get_base_uri($full = false)
    {
        return $this->get_prefix($full) . $this->app_base;
    }
}
