<?php

namespace Comercial\Form;



use Zend\Form\Element\Select;
use Zend\Form\Element\Textarea;
use Zend\Form\Element\Text;
use Zend\Form\Element\Hidden;
use Zend\Form\Form;

class Pedido extends Form {

    
	public function __construct($name = null){
	    
		parent::__construct('FrmPedido');
		$this->setAttribute('class', 'short_linespacing');
		
		
		
		$this->setAttribute('method', 'post');
	
		
		//idPedido
		$idPedido = new Text('idPedido');
		$idPedido->setLabel('Cód.');
		$idPedido->setAttribute('class', 'form-control');
		$idPedido->setAttribute('id', 'prpct_idPedido');
		$idPedido->setAttribute('readonly', 'readonly');
		$this->add($idPedido);
		
		//$Total
		$Total = new Text('Total');
		$Total->setLabel('Total');
		$Total->setAttribute('class', 'form-control');
		$Total->setAttribute('id', 'prpct_Total');
		$Total->setAttribute('readonly', 'readonly');
		$this->add($Total);
		
		
		//Data
		$dtAtual = new \DateTime();		
		$Data = new Text('Data');
		$Data->setLabel('Data');
		$Data->setAttribute('class', 'form-control datepicker');
		$Data->setAttribute('id', 'prpct_Data');
		$Data->setAttribute('placeholder', '__/__/____');
		$Data->setValue($dtAtual->format('d/m/Y'));
		$this->add($Data);
		
	}
}