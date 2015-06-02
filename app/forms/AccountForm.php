<?php

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Numeric;
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
            'style' => 'width:100%;'
        )));
        
        $this->add(new Text("nit", array(                        
            'placeholder' => '*NIT',
            'required' => 'required',
            'style' => 'width:100%;',
        )));
        
        $this->add(new Text("address", array(                        
            'placeholder' => '*Dirección',
            'required' => 'required',
            'style' => 'width:100%;',
        )));
        
        $this->add(new Select('city', array(
            'Cali' => 'Cali',
            'Bogota' => 'Bogota',
            'Monteria' => 'Monteria',
        )));
        
        $this->add(new Numeric("phone", array(                        
            'placeholder' => '*Teléfono',
            'required' => 'required',
            'style' => 'width:100%;',
        )));
        
        $this->add(new Check('status', array(
            'value' => '1'
        )));
    }
}