<?php

namespace Sigmamovil\Misc;

class SmartMenu extends \Phalcon\Mvc\User\Component implements \Iterator
{	
    protected $controller;

    private $_menu = array (
        "Inicio" => array(
            "controller" => array("index"),
            "class" => "",
            "url" => "",
            "title" => "Inicio",
            "icon" => "",
            "target" => ""
        ),
        "Cuentas" => array(
            "controller" => array("account"),
            "class" => "",
            "url" => "account",
            "title" => "Cuentas",
            "icon" => "",
            "target" => ""
        ),
        "Usuarios" => array(
            "controller" => array("user"),
            "class" => "",
            "url" => "user",
            "title" => "Usuarios",
            "icon" => "",
            "target" => ""
        ),
        "Clientes" => array(
            "controller" => array("client"),
            "class" => "",
            "url" => "",
            "title" => "Clientes",
            "icon" => "",
            "target" => ""
        ),
        "Tipos de visitas" => array(
            "controller" => array('visittype'),
            "class" => "",
            "url" => "visittype",
            "title" => "Tipos de visitas",
            "icon" => "",
            "target" => ""
        ),
    );

    public function __construct() 
    {
        $this->controller =  $this->view->getControllerName();
    }


    public function get() 
    {
        return $this;
    }
	
    public function rewind()
    {
        \reset($this->_menu);
    }

    public function current()
    {
        $obj = new \stdClass();
		
        $curr = \current($this->_menu);

        $obj->title = $curr['title'];
        $obj->icon = $curr['icon'];
        $obj->url = $curr['url'];
        $obj->class = '';
        $obj->target = $curr['target'];

        if (\in_array($this->controller, $curr['controller'])) {
            $obj->class = 'active';
        }
		
        return $obj;
    }

    public function key()
    {
        return \key($this->_menu);
    }

    public function next()
    {
        $var = \next($this->_menu);
    }

    public function valid()
    {
        $key = \key($this->_menu);
        $var = ($key !== NULL && $key !== FALSE);
        return $var;
    }
}
