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
            'class' => 'form-control',
        )));
        
        $this->add(new Password("pass", array(                        
            'placeholder' => '*Contraseña',
            'required' => 'required',
            'class' => 'form-control',
        )));
        
        $this->add(new Password("pass2", array(                        
            'placeholder' => '*Repita la contraseña',
            'required' => 'required',
            'class' => 'form-control',
        )));
        
        $this->add(new Email("email", array(                        
            'placeholder' => '*Email',
            'required' => 'required',
            'class' => 'form-control',
        )));
        
        $this->add(new Text("name_user", array(                        
            'placeholder' => '*Nombre',
            'required' => 'required',
            'class' => 'form-control',
        )));
        
        $this->add(new Text("lastName", array(                        
            'placeholder' => '*Apellido',
            'required' => 'required',
            'class' => 'form-control',
        )));
        
        $this->add(new Text("address_user", array(                        
            'placeholder' => '*Dirección',
            'required' => 'required',
            'class' => 'form-control',
        )));
        
        $this->add(new Select('state_user', array(
            '' => '*Seleccionar Departamento',
            'AMAZONAS' => 'AMAZONAS',
            'ANTIOQUIA' => 'ANTIOQUIA',
            'ARAUCA' => 'ARAUCA',
            'ATLANTICO' => 'ATLANTICO',
            'BOLIVAR' => 'BOLIVAR',
            'BOYACA' => 'BOYACA',
            'BOYACA' => 'BOYACA',
            'CALDAS' => 'CALDAS',
            'CAQUETA' => 'CAQUETA',
            'CASANARE' => 'CASANARE',
            'CAUCA' => 'CAUCA',
            'CESAR' => 'CESAR',
            'CHOCO' => 'CHOCO',
            'CORDOBA' => 'CORDOBA',
            'CUNDINAMARCA' => 'CUNDINAMARCA',
            'GUAINIA' => 'GUAINIA',
            'GUAJIRA' => 'GUAJIRA',
            'GUAVIARE' => 'GUAVIARE',
            'HUILA' => 'HUILA',
            'MAGDALENA' => 'MAGDALENA',
            'META' => 'META',
            'NARIÑO' => 'NARIÑO',
            'NTE_SANTANDER' => 'NORTE DE SANTANDER',
            'PUTUMAYO' => 'PUTUMAYO',
            'QUINDIO' => 'QUINDIO',
            'RISARALDA' => 'RISARALDA',
            'SAN_ANDRES' => 'SAN ANDRES Y PROVIDENCIA',
            'SANTANDER' => 'SANTANDER',
            'SUCRE' => 'SUCRE',
            'TOLIMA' => 'TOLIMA',
            'VALLE' => 'VALLE DEL CAUCA',
            'VAUPES' => 'VAUPES',
            'VICHADA' => 'VICHADA',
        )));
        
        $this->add(new Select('city_user', array(
        )));
        
        $this->add(new Text("phone_user", array(                        
            'placeholder' => '*Teléfono',
            'required' => 'required',
            'class' => 'form-control',
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
                if($rol->name != 'sudo'){
                    $r[$rol->idRole] = $rol->name;
                }
            }
        }
        
        $this->add(new Select('idRole', 
            $r, 
            array(
                'placeholder' => '*Funciones',
                'required' => 'required',                
                'class' => 'form-control select2',                
            )
        ));
    }
}