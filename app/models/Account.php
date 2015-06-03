<?php

use Phalcon\Mvc\Model\Validator\Uniqueness;

class Account extends \Phalcon\Mvc\Model
{    
    public $idAccount; 
    
    public function initialize()
    {
        $this->hasMany("idAccount", "User", "idAccount");
    }
    
     public function validation()
    {
      $this->validate(new Uniqueness(array(
          "field" => 'name',
          'message' => 'Ya existe una cuenta con este nombre en la base de datos'
      )));
    }
}