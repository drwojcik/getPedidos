<?php

namespace Basico\Form;

use Zend\Form\Element\Text;
use Zend\Form\Form;

class Auth extends Form {	
    
	public function __construct($name = null){	    	    
	    
		parent::__construct('Auth');			
		$this->setAttribute('method', 'post');
		$this->setAttribute('class', 'form-horizontal');
		
		//Login
		$Login = new Text('Login');
		$Login->setLabel('Login');
		$Login->setAttribute('id', 'auth_Login');
		$Login->setAttribute('placeholder', 'Informe o Login');
		$this->add($Login);
		
		//Senha
		$Senha = new Text('Senha');
		$Senha->setLabel('Senha');
		$Senha->setAttribute('id', 'auth_Senha');
		$this->add($Senha);
	}
}
