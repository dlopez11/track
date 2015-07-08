<?php

use Phalcon\Mvc\Model\Validator\PresenceOf;

class Visitcategory extends \Phalcon\Mvc\Model
{
    public $idAccount; 
    public $idVisittype; 

    public function initialize()
    {
        $this->belongsTo("idAccount", "Account", "idAccount");
        $this->hasMany("idVisitcategory", "Visitcategory", "idVisitcategory");
    }
    
    public function validation()
    {
        $this->validate(new PresenceOf(array(
            'field' => 'name',
            'message' => 'El nombre de la categoria es obligatorio, por favor valide la información'
        )));
        
        $this->validate(new SpaceValidator(array(
            'field' => 'name',
            'message' => 'El campo nombre esta vacío, por favor valide la información'
        )));
        
        if ($this->validationHasFailed() == true) {
            return false;
        }
    }
}