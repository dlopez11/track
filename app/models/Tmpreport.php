<?php

class Tmpreport extends Phalcon\Mvc\Model
{
    public $idAccount;
    
    public function initialize()
    {
        $this->belongsTo("idAccount", "Account", "idAccount", array(
            "foreignKey" => true,
        ));
    }
}