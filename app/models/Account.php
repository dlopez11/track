<?php

use Phalcon\Mvc\Model\Validator\Uniqueness;

class Account extends \Phalcon\Mvc\Model
{    
    public function initialize()
    {
        
    }
    
     public function validation()
    {
      $this->validate(new Uniqueness(array(
          "field" => 'name'
      )));
    }
}