<?php

class Account extends \Phalcon\Mvc\Model

{    
    public $idAccount; 
    
    public function initialize()
    {
        $this->hasMany("idAccount", "User", "idAccount");
    }
}