<?php

use Phalcon\Mvc\Model\Validator\Uniqueness;
use Phalcon\Mvc\Model\Validator\PresenceOf;

class Account extends \Phalcon\Mvc\Model

{    
    public $idAccount; 
    
    public function initialize()
    {
        $this->hasMany("idAccount", "User", "idAccount");
    }
    
    public function validation()
    {
        $this->validate(new PresenceOf(array(
            'field' => 'name',
            'message' => 'El nombre es obligatorio, por favor valide la información'
        )));
        
        $this->validate(new SpaceValidator(array(
            'field' => 'name',
            'message' => 'El campo nombre esta vacío, por favor valide la información'
        )));
        
        $this->validate(new Uniqueness(array(
            'field' => 'name',
            'message' => 'Ya existe una cuenta con este nombre, por favor valide la información'
        )));
        
        $this->validate(new PresenceOf(array(
            'field' => 'nit',
            'message' => 'El nombre es obligatorio, por favor valide la información'
        )));
        
        $this->validate(new SpaceValidator(array(
            'field' => 'nit',
            'message' => 'El campo nombre esta vacío, por favor valide la información'
        )));
        
        $this->validate(new PresenceOf(array(
            'field' => 'address',
            'message' => 'La dirección es obligatoria, por favor valide la información'
        )));
        
        $this->validate(new SpaceValidator(array(
            'field' => 'address',
            'message' => 'El campo dirección esta vacío, por favor valide la información'
        )));
        
        $this->validate(new PresenceOf(array(
            'field' => 'city',
            'message' => 'El campo ciudad es obligatorio, por favor valide la información'
        )));
        
        $this->validate(new SpaceValidator(array(
            'field' => 'city',
            'message' => 'El campo ciudad esta vacío, por favor valide la información'
        )));
        
        $this->validate(new PresenceOf(array(
            'field' => 'state',
            'message' => 'El campo departamento es obligatorio, por favor valide la información'
        )));
        
        $this->validate(new SpaceValidator(array(
            'field' => 'state',
            'message' => 'El campo departamento esta vacío, por favor valide la información'
        )));
        
        if ($this->validationHasFailed() == true) {
            return false;
        }
    }
}