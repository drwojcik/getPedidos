<?php

namespace Basico\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class TipoFuncionarioRepository extends EntityRepository {
	
	/**
	 * Retorna um array no qual o indice
	 * é o CodTipoFuncionario e valor é a TipoFuncionario
	 *
	 * @return array
	 */
	public function fetchPairs(array $whereParams = null, array $orderBy = null){
		/*$entities = $this->findBy($whereParams, $orderBy);
			
		$arrayTipoFuncionario = array();
		foreach($entities as $entity){
			$arrayTipoFuncionario[$entity->getCodTipoFuncionario()] = $entity->getTipoFuncionario();
		}
		return $arrayTipoFuncionario;*/
		
		$qb = $this->getEntityManager()->createQueryBuilder();
		$qb->select('p.CodTipoFuncionario, p.TipoFuncionario')
		->from($this->_entityName,'p');
		$paramCount = 0;
		if(!empty($whereParams)){
			foreach ($whereParams as $campo => $valor){
				$paramCount++;
				$qb->andWhere("p.{$campo} = ?{$paramCount}");
				$qb->setParameter($paramCount, $valor);
			}
		}
		//seta o order by
		if(!empty($orderBy)){
			foreach ($orderBy as $order => $value){
				$qb->addOrderBy('p.'.$order,$value);
			}
		}
		
		$entities = $qb->getQuery()->getResult();
		$arrayRetorno = array();
		foreach($entities as $entity){
			$arrayRetorno[$entity['CodTipoFuncionario']] = $entity['TipoFuncionario'];
		}
		return $arrayRetorno;
		
	}
	
}