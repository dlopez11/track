<?php

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Check;
use Phalcon\Forms\Element\Numeric;

class AccountForm extends Form
{
    public function initialize()
    {
        $this->add(new Text("name", array(                        
            'placeholder' => '*Nombre de la cuenta',
            'required' => 'required',
            'autofocus' => 'autofocus',
            'class' => 'form-control'
        )));
        
        $this->add(new Text("nit", array(                        
            'placeholder' => '*NIT',
            'required' => 'required',
            'class' => 'form-control'
        )));
        
        $this->add(new Text("address", array(                        
            'placeholder' => '*Dirección',
            'required' => 'required',
            'class' => 'form-control'
        )));
        
        $this->add(new Select('state', array(
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
            'GUAVIARE' => 'GUAVIARE',
            'GUAJIRA' => 'GUAJIRA',
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
        
        $this->add(new Select('city', array(

        )));
        
        $this->add(new Text("phone", array(                        
            'placeholder' => '*Teléfono',
            'required' => 'required',
            'class' => 'form-control'
        )));
        
        $this->add(new Check('status', array(
            'value' => '1'
        )));
        
        $this->add(new Numeric("totalUsers", array(                        
            'placeholder' => '*Cantidad de usuarios permitidos',
            'required' => 'required',
            'class' => 'form-control'
        )));
    }
    
}