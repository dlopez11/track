<?php

use Phalcon\Mvc\Model\Validator\Uniqueness;

class Visittype extends \Phalcon\Mvc\Model
{
    public $idAccount; 
    public $idVisitcategory; 

    public function initialize()
    {
        $this->belongsTo("idAccount", "Account", "idAccount");
        $this->belongsTo("idVisitcategory", "Visitcategory", "idVisitcategory");
    }
    
    public function validation()
    {
//        $this->validate(new Uniqueness(array(
//            'field' => 'name',
//            'message' => 'Ya existe un tipo de visita con este nombre, por favor valide la información'
//        )));
        
        if ($this->validationHasFailed() == true) {
            return false;
        }
    }
}