<?php

namespace Basico\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Zend\Authentication\Storage\Session As SessionStorage;

class CentroCustoRepository extends EntityRepository {
	
	/**
	 * Retorna um array no qual o indice
	 * é o CodCentroCusto e valor é o CentroCusto
	 *
	 * @return array
	 */
	public function fetchPairs(array $whereParams = null, array $orderBy = null){
		/*$entities = $this->findBy($whereParams, $orderBy);
					
		$arrayCentroCusto = array();
		foreach($entities as $entity){
			$arrayCentroCusto[$entity->getCodCentroCusto()] = $entity->getCentroCusto();
		}
		return $arrayCentroCusto;*/
		
		//Alterado em 31/10/2017 por Diego Wojcik, captura os parâmetros de empresa e obra na sessão - #16480
		$sessionParametros = new SessionStorage('Parametros');
		$parametros =  $sessionParametros->read();
		
		$qb = $this->getEntityManager()->createQueryBuilder();
		$qb->select('c.CodCentroCusto, c.CentroCusto')
		->from($this->_entityName,'c');
		$paramCount = 0;
		if(!empty($whereParams)){
			foreach ($whereParams as $campo => $valor){
				$paramCount++;
				$qb->andWhere("c.{$campo} = ?{$paramCount}");
				$qb->setParameter($paramCount, $valor);
			}
		}
		
		//seta o order by
		if(!empty($orderBy)){
			foreach ($orderBy as $order => $value){
				$qb->addOrderBy('c.'.$order,$value);
			}
		}
			
		$entities = $qb->getQuery()->getResult();
		$arrayRetorno = array();
		//Alterado em 01/11/2017 por Diego Wojcik, valida centro de custo #16480
		if(!$parametros['centrocustos']){
			$arrayRetorno[0] = 'Centro de custo indisponível';
		}else{
			foreach($entities as $entity){
				$arrayRetorno[$entity['CodCentroCusto']] = $entity['CentroCusto'];
			}
		}
		return $arrayRetorno;
		
		
	}
	
	
}