<?php

use Phalcon\Mvc\Model\Validator\Uniqueness;
use Phalcon\Mvc\Model\Validator\PresenceOf;

class Visittype extends \Phalcon\Mvc\Model
{
    public $idAccount; 
    public $idVisittype; 

    public function initialize()
    {
        $this->belongsTo("idAccount", "Account", "idAccount");
        $this->hasMany("idVisittype", "Visit", "idVisittype");
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
        
        if ($this->validationHasFailed() == true) {
            return false;
        }
    }
}