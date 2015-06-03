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
        
        $this->add(new Text("name-user", array(                        
            'placeholder' => '*Nombre',
            'required' => 'required',
            'class' => 'form-control',
        )));
        
        $this->add(new Text("lastName", array(                        
            'placeholder' => '*Apellido',
            'required' => 'required',
            'class' => 'form-control',
        )));
        
        $this->add(new Text("address-user", array(                        
            'placeholder' => '*Dirección',
            'required' => 'required',
            'class' => 'form-control',
        )));
        
        $this->add(new Select('state-user', array(
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
            'GUAVIARE' => 'GUAVIARE',
            'HUILA' => 'HUILA',
            'LA GUAJIRA' => 'LA GUAJIRA',
            'MAGDALENA' => 'MAGDALENA',
            'META' => 'META',
            'NARIÑO' => 'NARIÑO',
            'NORTE DE SANTANDER' => 'NORTE DE SANTANDER',
            'PUTUMAYO' => 'PUTUMAYO',
            'QUINDIO' => 'QUINDIO',
            'RISARALDA' => 'RISARALDA',
            'SAN ANDRES Y PROVIDENCIA' => 'SAN ANDRES Y PROVIDENCIA',
            'SANTANDER' => 'SANTANDER',
            'SUCRE' => 'SUCRE',
            'TOLIMA' => 'TOLIMA',
            'VALLE DEL CAUCA' => 'VALLE DEL CAUCA',
            'VAUPES' => 'VAUPES',
            'VICHADA' => 'VICHADA',
        )));
        
        $this->add(new Select('city-user', array(
            'APARTADO' => 'APARTADO',
            'ARAUCA' => 'ARAUCA',
            'BARRANQUILLA' => 'BARRANQUILLA',
            'BELLO' => 'BELLO',
            'CAUCASIA' => 'CAUCASIA',
            'LETICIA' => 'LETICIA',
            'MEDELLIN' => 'MEDELLIN',
            'MONTERIA' => 'MONTERIA',
            'YARUMAL' => 'YARUMAL',
        )));
        
        $this->add(new Text("phone-user", array(                        
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