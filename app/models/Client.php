<?php

use Phalcon\Mvc\Model\Validator\Uniqueness;
use Phalcon\Mvc\Model\Validator\PresenceOf;

class Client extends \Phalcon\Mvc\Model
{
    public $idAccount; 

    public function initialize()
    {
        $this->belongsTo("idAccount", "Account", "idAccount");
    }
    
    public function validation()
    {
        $this->validate(new PresenceOf(array(
            'field' => 'name',
            'message' => 'El nombre del cliente es obligatorio, por favor valide la información'
        )));
        
        $this->validate(new SpaceValidator(array(
            'field' => 'name',
            'message' => 'El campo nombre esta vacío, por favor valide la información'
        )));
        
        $this->validate(new PresenceOf(array(
            'field' => 'description',
            'message' => 'La descripción del Cliente es obligatoria, por favor valide la información'
        )));
        
        $this->validate(new SpaceValidator(array(
            'field' => 'description',
            'message' => 'El campo descripción esta vacío, por favor valide la información'
        )));
        
        $this->validate(new PresenceOf(array(
            'field' => 'nit',
            'message' => 'El nit del cliente es obligatorio, por favor valide la información'
        )));
        
        $this->validate(new SpaceValidator(array(
            'field' => 'nit',
            'message' => 'El campo nit esta vacío, por favor valide la información'
        )));
        
        $this->validate(new PresenceOf(array(
            'field' => 'address',
            'message' => 'La dirección del cliente es obligatoria, por favor valide la información'
        )));
        
        $this->validate(new SpaceValidator(array(
            'field' => 'address',
            'message' => 'El campo dirección esta vacío, por favor valide la información'
        )));
        
        $this->validate(new PresenceOf(array(
            'field' => 'phone',
            'message' => 'El telefono del cliente es obligatorio, por favor valide la información'
        )));
        
        $this->validate(new SpaceValidator(array(
            'field' => 'phone',
            'message' => 'El campo telefono esta vacío, por favor valide la información'
        )));
        
        if ($this->validationHasFailed() == true) {
            return false;
        }
    }
}