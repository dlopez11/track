<?php

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Check;

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
        
        $this->add(new Select('city', array(
            'APARTADO' => 'APARTADO',
            'ARAUCA' => 'ARAUCA',
            'BARRANQUILLA' => 'BARRANQUILLA',
            'BELLO' => 'BELLO',
            'CALI' => 'CALI',
            'CAUCASIA' => 'CAUCASIA',
            'LETICIA' => 'LETICIA',
            'MEDELLIN' => 'MEDELLIN',
            'MONTERIA' => 'MONTERIA',
            'YARUMAL' => 'YARUMAL',
        )));
        
        $this->add(new Text("phone", array(                        
            'placeholder' => '*Teléfono',
            'required' => 'required',
            'class' => 'form-control'
        )));
        
        $this->add(new Check('status', array(
            'value' => '1'
        )));
    }
    
}