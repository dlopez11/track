<?php

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Forms\Element\Select;

class ClientForm extends Form
{
    public function initialize()
    {
        $this->add(new Text("name", array(                        
            'placeholder' => '*Nombre del cliente',
            'autofocus' => 'autofocus',
            'class' => 'form-control',
            'required' => 'required',
        )));
        
        $this->add(new TextArea("description", array(                        
            'placeholder' => '*Descripción',
            'class' => 'form-control',
            'required' => 'required',
        )));
        
        $this->add(new Text("nit", array(                        
            'placeholder' => '*NIT',
            'required' => 'required',
            'class' => 'form-control',
        )));
        
        $this->add(new Text("address", array(                        
            'placeholder' => '*Dirección',
            'required' => 'required',
            'class' => 'form-control',
        )));
        
        $this->add(new Text("phone", array(                        
            'placeholder' => '*Teléfono',
            'required' => 'required',
            'class' => 'form-control',
        )));
        
        $this->add(new Select('city', array(
            'Cali' => 'Cali',
            'Bogota' => 'Bogota',
            'Monteria' => 'Monteria',
        )));
        
        $this->add(new Select('state', array(
            'Cali' => 'Cali',
            'Bogota' => 'Bogota',
            'Monteria' => 'Monteria',
        )));
    }
}