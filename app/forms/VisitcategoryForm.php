<?php

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;

class VisitcategoryForm extends Form
{
    public function initialize()
    {
        $this->add(new Text("name", array(                        
            'placeholder' => '*Nombre de la categoria',
            'autofocus' => 'autofocus',
            'class' => 'form-control',
            'required' => 'required',
        )));
        
        $this->add(new TextArea("description", array(                        
            'placeholder' => 'Descripción',
            'class' => 'form-control',
        )));
    }
}