<?php

class Visit extends \Phalcon\Mvc\Model
{
    public $idUser;
    public $idVisittype;
    public $idClient;

    public function initialize()
    {
        $this->belongsTo("idUser", "User", "idUser", array(
            "foreignKey" => true,
        ));
        
        $this->belongsTo("idVisittype", "Visittype", "idVisittype", array(
            "foreignKey" => true,
        ));
        
        $this->belongsTo("idClient", "Client", "idClient", array(
            "foreignKey" => true,
        ));
    }
}