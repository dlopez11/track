<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ControllerBase
 *
 * @author Will
 */
class ControllerBase extends \Phalcon\Mvc\Controller
{
    protected $_isJsonResponse = false;

    public function initialize()
    {
//    	if (isset($this->userData)) {
//            $this->user = $this->userData;
//    	}
    }
    /**
     * Llamar este metodo para enviar respuestas en modo JSON
     * @param string $content
     * @param int $status
     * @param string $message
     * @return \Phalcon\Http\ResponseInterface
     */
    public function set_json_response($content, $status = 200, $message = '') 
    {
        $this->view->disable();

        $this->_isJsonResponse = true;
        $this->response->setContentType('application/json', 'UTF-8');

        if ($status != 200) {
                $this->response->setStatusCode($status, $message);
        }
        if (is_array($content)) {
                $content = json_encode($content);
        }
        $this->response->setContent($content);
        return $this->response;
    }
    
    
    /**
    * Esta función recibe el tipo de Trace y el mensaje para guardar el registro en la base de datos
    * @param string $controller
    * @param string $action
    * @param int $date
    * @param int $ip
    */
    public function trace($status, $msg)
    {
        $controller = $this->dispatcher->getControllerName();
        $action = $this->dispatcher->getActionName();
        $date = time();
        $ip = $_SERVER['REMOTE_ADDR'];
        $user = 1;

        $operation = $controller . '::' .$action;

        Trace::createTrace($user, $status, $operation, $msg, $date, $ip);
    }
    
    /**
    * Retorna el contenido (JSON) POST desde ember transformado en datos que pueda leer PHP
    */
    public function getRequestContent()
    {
        if($this->requestContent && isset($this->requestContent->content)) {
            return $this->requestContent->content;
        }
        else {
            return $this->request->getRawBody();
        }
    }
    
    /**
     * Función que valida el prefijo de una cuenta, si este se encuentra vacio, toma las primeras
     * 4 letras del nombre de la cuenta
     * @param string $name
     * @param string $prefix
     * @return string $new_prefix
     */
    public function validatePrefix($name, $prefix)
    {
        $p = (empty($prefix) ? $name : $prefix);

        $prefix = (strlen($p) <= 4 ? strtolower($p) : $this->getPrefix($p));

        return $prefix;
    }
    
    /**
     * Toma una cadena y la recorta hasta dejarla del de tamaño de 4 caracteres
     * @param string $name
     * @return string $string
     */
    public function getPrefix($name)
    {
        $name = str_replace(' ', '', $name);
        $prefix = strtolower(substr($name, 0, 4));

        return $prefix;
    }
    
    /**
     * Toma el nombre de usuario y borra el prefijo
     * @param string $username
     * @return string
     */
    public function removePrefix($username)
    {
        $un = explode('_', $username);

        if (isset($un[1])) {
                return $un[1];
        }
        return $username;
    }
}
