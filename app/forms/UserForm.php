<?php

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Email;
use Phalcon\Forms\Element\Select;

class UserForm extends Form
{
    public function initialize($user, $role)
    {
        $this->add(new Text("userName", array(
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
        
        $this->add(new Text("lastName", array(                        
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
        
        $roles = Role::find();
        $r = array();
        
        if ($role->name == 'sudo') {
            foreach ($roles as $rol) {
                $r[$rol->idRole] = $rol->name;
            }            
        }
        else{
            foreach ($roles as $rol){
                if($rol->name != 'sudo' && $rol->name != 'admin' && $rol->name != 'user'){
                    $r[$rol->idRole] = $rol->name;
                }
            }
        }
        
        $this->add(new Select('idRole', 
            $r, 
            array(
                'placeholder' => '*Funciones',
                'required' => 'required',                
                'style' => 'width:100%;',
            )
        ));
    }
}