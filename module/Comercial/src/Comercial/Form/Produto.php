<?php

namespace Comercial\Form;

use Zend\Form\Element\Select;
use Zend\Form\Element\Textarea;
use Zend\Form\Element\Text;
use Zend\Form\Element\Hidden;
use Zend\Form\Form;
use Zend\Form\Element\Checkbox;

class Produto extends Form {

    
	
	public function __construct($name = null){
	    
		parent::__construct('Produto');
		$this->setAttribute('class', 'short_linespacing');
		
	
		
		$this->setAttribute('method', 'post');
	
		
		//idProduto
		$idProduto = new Text('idProduto');
		$idProduto->setLabel('Cód.');
		$idProduto->setAttribute('class', 'form-control');
		$idProduto->setAttribute('id', 'prpct_idProduto');
		$idProduto->setAttribute('readonly', 'readonly');
		$this->add($idProduto);
		
		//Nome
		$Nome = new Text('Nome');
		$Nome->setLabel('Nome');
		$Nome->setAttribute('class', 'form-control btn-obrigatorio');
		$Nome->setAttribute('id', 'prpct_Nome');
		$this->add($Nome);
		
		//SKU
		$SKU = new Text('SKU');
		$SKU->setLabel('SKU');
		$SKU->setAttribute('class', 'form-control');
		$SKU->setAttribute('id', 'prpct_SKU');
		$this->add($SKU);
		
		//Descricao
		$Descricao = new Text('Descricao');
		$Descricao->setLabel('Descrição');
		$Descricao->setAttribute('class', 'form-control select');
		$Descricao->setAttribute('id', 'prpct_Descricao');
		$this->add($Descricao);
		
		//Preco
		$Preco = new Text('Preco');
		$Preco->setLabel('Preço');
		$Preco->setAttribute('class', 'form-control select');
		$Preco->setAttribute('id', 'prpct_Preco');
		$this->add($Preco);
		
	
		
		
	}
}