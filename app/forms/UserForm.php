<?php

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Email;

class UserForm extends Form
{
    public function initialize()
    {
        $this->add(new Text("username", array(                        
            'placeholder' => '*Nombre de usuario',
            'required' => 'required',
            'style' => 'width:100%;',
        )));
        
        $this->add(new Password("pass", array(                        
            'placeholder' => '*Contraseña',
            'required' => 'required',
            'style' => 'width:100%;',
        )));
        
        $this->add(new Password("pass2", array(                        
            'placeholder' => '*Repita la contraseña',
            'required' => 'required',
            'style' => 'width:100%;',
        )));
        
        $this->add(new Email("email", array(                        
            'placeholder' => '*Email',
            'required' => 'required',
            'style' => 'width:100%;',
        )));
        
        $this->add(new Text("name-user", array(                        
            'placeholder' => '*Nombre',
            'required' => 'required',
            'style' => 'width:100%;',
        )));
        
        $this->add(new Text("lastname", array(                        
            'placeholder' => '*Apellido',
            'required' => 'required',
            'style' => 'width:100%;',
        )));
        
        $this->add(new Text("address-user", array(                        
            'placeholder' => '*Dirección',
            'required' => 'required',
            'style' => 'width:100%;',
        )));
        
        $this->add(new Text("phone-user", array(                        
            'placeholder' => '*Teléfono',
            'required' => 'required',
            'style' => 'width:100%;',
        )));
    }
}