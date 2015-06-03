<?php

use Phalcon\Mvc\Model\Validator\Uniqueness;
use Phalcon\Mvc\Model\Validator\PresenceOf;
use Phalcon\Mvc\Model\Validator\StringLength;
use Phalcon\Mvc\Model\Validator\Regex;
use Phalcon\Mvc\Model\Validator\Email;

class User extends Phalcon\Mvc\Model
{
    public $idAccount;
    public $idRole;
    
    public function initialize()
    {
        $this->belongsTo("idAccount", "Account", "idAccount");
        $this->belongsTo("idRole", "Role", "idRole");
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
        
        $this->validate(new PresenceOf(array(
            'field' => 'lastName',
            'message' => 'El apellido es obligatorio, por favor valide la información'
        )));
        
        $this->validate(new SpaceValidator(array(
            'field' => 'lastName',
            'message' => 'El apellido esta vacío, por favor valide la información'
        )));
        
        $this->validate(new PresenceOf(array(
            'field' => 'username',
            'message' => 'El nombre de usuario es obligatorio, por favor valide la información'
        )));
        
        $this->validate(new Uniqueness(array(
            'field' => 'username',
            'message' => 'El nombre de usuario ya existe, por favor valide la información'
        )));
        
        $this->validate(new SpaceValidator(array(
            'field' => 'username',
            'message' => 'El nombre de usuario esta vacío, por favor valide la información'
        )));
        
        $this->validate(new StringLength(array(
            "field" => "username",
            "min" => 4,
            "message" => "El nombre de usuario es muy corto, debe tener al menos 4 caracteres"			
        )));
        
        $this->validate(new Regex(array(
            'field' => 'username',
            'pattern' => '/^[a-z0-9\._-]{4,30}/',
            'message' => 'EL nombre de usuario no debe tener espacios ni caracteres especiales, tampoco letras mayúsculas y debe tener mínimo 4 y máximo 40 caracteres'
        )));
        
        $this->validate(new PresenceOf(array(
            'field' => 'email',
            'message' => 'El email es obligatorio, por favor valide la información'
        )));
        
        $this->validate(new Email(array(
            'field' => 'email',
            'message' => 'El email ingresado no es valido, por favor valide la información'
        )));
        
        $this->validate(new SpaceValidator(array(
            'field' => 'email',
            'message' => 'El campo Email esta vacío, por favor valide la información'
        )));
        
        $this->validate(new Uniqueness(array(
            'field' => 'email',
            'message' => 'El email ingresado ya existe, por favor valide la información'
        )));
        
        $this->validate(new PresenceOf(array(
            'field' => 'password',
            'message' => 'La contraseña es obligatoria, por favor valide la información'
        )));
        
        $this->validate(new SpaceValidator(array(
            'field' => 'password',
            'message' => 'El campo Contraseña esta vacío, por favor valide la información'
        )));
        
        $this->validate(new StringLength(array(
            "field" => "password",
            "min" => 8,
            "message" => "La contraseña es muy corta, debe tener como minimo 8 caracteres"			
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
            'field' => 'phone',
            'message' => 'El telefono es obligatorio, por favor valide la información'
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
