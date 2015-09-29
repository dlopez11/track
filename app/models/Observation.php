<?php

class Observation extends \Phalcon\Mvc\Model
{    
    public $idVisit; 
    
    public function initialize()
    {
        $this->belongsTo("idVisit", "Visit", "idVisit", array(
            "foreignKey" => true,
        ));
    }
}