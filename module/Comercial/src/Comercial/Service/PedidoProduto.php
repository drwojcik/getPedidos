<?php

namespace Comercial\Service;

use Jlib\Util\FormatValue;

use Doctrine\ORM\EntityManager;
use Basico\Entity\Configurator;
use Basico\Service\AbstractService;

class PedidoProduto extends AbstractService {
	
	public function __construct(EntityManager $em){
		parent::__construct($em);
		$this->entity = 'Comercial\Entity\PedidoProduto';
	}
	
	public function insert($data){
	    //busca o MAX(id) da tabela
	    
	  #  $novoID = $this->getNovoCodigo($this->entity, 'idPedidoProduto');
	  #  $data['idPedidoProduto'] = $novoID;	    
	    
	    return parent::insert($data);
	}		
	
	/**
	 * @see Basico\Service.AbstractService::prepareToSave()
	 */
	public function prepareToSave(array $dados){
	    $dados = $this->setEntities($dados);
	    $formater = new FormatValue();
	    if (isset($dados['Preco'])){
	        $valor = $formater->formatCurrencyToSave($dados['Preco']);
	        $dados['Preco'] = $valor;
	    }
	    return $dados;
	}
	
	protected function setEntities($data){

	    return $data;
	}
		
}