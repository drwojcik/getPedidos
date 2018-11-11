<?php

namespace Comercial\Service;

use Jlib\Util\FormatValue;

use Doctrine\ORM\EntityManager;
use Basico\Entity\Configurator;
use Basico\Service\AbstractService;

class Pedido extends AbstractService {
	
	public function __construct(EntityManager $em){
		parent::__construct($em);
		$this->entity = 'Comercial\Entity\Pedido';
	}
	
	public function insert($data){
	    //busca o MAX(id) da tabela
	    $novoID = $this->getNovoCodigo($this->entity, 'idPedido');
	    $data['idPedido'] = $novoID;
	    
	    return parent::insert($data);
	}	
	
	/**
	 * @see Basico\Service.AbstractService::prepareToSave()
	 */
	public function prepareToSave(array $dados){
	    $dados = $this->setEntities($dados);
	    
	    $formater = new FormatValue();
	    if (isset($dados['Data'])){
	        $valor = $formater->formatDateToSave($dados['Data']);
	        $dados['Data'] = $valor;
	    }
	    if (isset($dados['Total'])){
	        $valor = $formater->formatCurrencyToSave($dados['Total']);
	        $dados['Total'] = $valor;
	    }
	    
	   
		return $dados;
	}
	
	protected function setEntities($data){
	   
	    return $data;
	}
	
}