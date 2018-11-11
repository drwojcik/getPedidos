<?php

namespace Comercial\Form;


use Zend\Form\Element\Text;
use Zend\Form\Element\Hidden;
use Zend\Form\Form;

class PedidoProduto extends Form {     
	
	public function __construct($name = null){
	    
		parent::__construct('FrmPedidoProduto');
		$this->setAttribute('class', 'form-horizontal short_linespacing');					
		$this->setAttribute('method', 'post');
		
	
		
		//Manter o nome dos campos com o mesmo nome dos campos declarados na Entity.
		//Dessa forma o valor dos campos serão setados automaticamente.
		
		//idPedidoProduto
		$idPedidoProduto = new Text('idPedidoProduto');
		$idPedidoProduto->setLabel('Cód.');
		$idPedidoProduto->setAttribute('id', 'cntt_idPedidoProduto');
		$idPedidoProduto->setAttribute('class', 'form-control');
		$idPedidoProduto->setAttribute('readonly', 'readonly');
		$this->add($idPedidoProduto);
		
		//ProdutoIdProduto
		$ProdutoIdProduto = new Hidden('ProdutoIdProduto');
		$ProdutoIdProduto->setAttribute('id', 'cntt_ProdutoidProduto');
		$this->add($ProdutoIdProduto);
		
		//PedidoIdPedido
		$Pedido = new Hidden('PedidoIdPedido');
		$Pedido->setAttribute('id', 'cntt_PedidoIdPedido');
		$this->add($Pedido);
		
		//Nome
		$Nome = new Text('NomeProduto');
		$Nome->setLabel('Produto');
		$Nome->setAttribute('id', 'cntt_NomeProduto');
		$Nome->setAttribute('class', 'form-control');
		$Nome->setAttribute('placeholder', 'Digite e selecione um produto da lista');
		$this->add($Nome);
		
		//QtdeProduto
		$QtdeProduto = new Text('QtdeProduto');
		$QtdeProduto->setLabel('Qtde');
		$QtdeProduto->setAttribute('id', 'cntt_QtdeProduto');
		$QtdeProduto->setAttribute('class', 'form-control');
		$this->add($QtdeProduto);
		
		//SubTotal
		$SubTotal = new Text('SubTotal');
		$SubTotal->setLabel('SubTotal');
		$SubTotal->setAttribute('id', 'cntt_SubTotal');
		$SubTotal->setAttribute('class', 'form-control');
		$SubTotal->setAttribute('readonly', 'readonly');
		$this->add($SubTotal);
		
		
		//ValorUnitario
		$ValorUnitario = new Text('ValorUnitario');
		$ValorUnitario->setLabel('Preço');
		$ValorUnitario->setAttribute('id', 'cntt_ValorUnitario');
		$ValorUnitario->setAttribute('class', 'form-control');
		$ValorUnitario->setAttribute('readonly', 'readonly');
		$this->add($ValorUnitario);
		
				
	}
} 