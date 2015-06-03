<?php

class IndexController extends ControllerBase
{
    public function IndexAction()
    {
        $users = User::find(array(
            'conditions' => 'idAccount = ?1',
            'bind' => array(1 => $this->user->idAccount)
        ));
        
        $tvisits = Visittype::find(array(
            'conditions' => 'idAccount = ?1',
            'bind' => array(1 => $this->user->idAccount)
        ));
        
        $clients = Client::find(array(
            'conditions' => 'idAccount = ?1',
            'bind' => array(1 => $this->user->idAccount)
        ));
        
        $this->view->setVar('users', $users);
        $this->view->setVar('tvisits', $tvisits);
        $this->view->setVar('clients', $clients);
    }
}