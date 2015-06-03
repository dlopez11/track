<?php

use Phalcon\Mvc\Model\Validator\Uniqueness;

class Account extends \Phalcon\Mvc\Model
{    
    public function initialize()
    {
        $this->hasMany("idAccount", "User", "idAccount");
    }
    
     public function validation()
    {
      $this->validate(new Uniqueness(array(
          "field" => 'name'
      )));
    }
}