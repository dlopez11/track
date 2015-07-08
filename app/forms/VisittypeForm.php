<?php

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Forms\Element\Select;

class VisittypeForm extends Form
{
    public function initialize()
    {
        $this->add(new Text("name", array(                        
            'placeholder' => '*Nombre del tipo de visita',
            'autofocus' => 'autofocus',
            'class' => 'form-control',
            'required' => 'required',
        )));
        
        $this->add(new TextArea("description", array(                        
            'placeholder' => '*Descripción',
            'class' => 'form-control',
            'required' => 'required',
        )));
        
        $this->add(new Select('category', array(
            'required' => 'required',
        )));
    }
}