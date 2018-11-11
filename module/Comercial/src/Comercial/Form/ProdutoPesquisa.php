<?php

namespace Comercial\Form;


use Zend\Form\Element\Select;
use Zend\Form\Element\Textarea;
use Zend\Form\Element\Text;
use Zend\Form\Element\Hidden;
use Zend\Form\Form;
use Zend\Form\Element\Checkbox;

class ProdutoPesquisa extends Form {

    

    
	
	public function __construct($name = null){
	    
		parent::__construct('ProdutoPesquisa');
		$this->setAttribute('class', 'short_linespacing');
		
		
		
		$this->setAttribute('method', 'post');
	
		//Nome
		$Nome = new Text('Nome');
		$Nome->setLabel('Nome');
		$Nome->setAttribute('class', 'form-control');
		$Nome->setAttribute('id', 'prpct_Nome');
		$this->add($Nome);
		
		//SKU
		$SKU = new Text('SKU');
		$SKU->setLabel('SKU');
		$SKU->setAttribute('class', 'form-control select');
		$SKU->setAttribute('id', 'prpct_SKU');
		$this->add($SKU);
		
		//Descricao
		$Descricao = new Text('Descricao');
		$Descricao->setLabel('Descrição');
		$Descricao->setAttribute('class', 'form-control select');
		$Descricao->setAttribute('id', 'prpct_Descricao');
		$this->add($Descricao);
		
		
	}
}