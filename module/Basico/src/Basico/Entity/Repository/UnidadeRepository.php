<?php

namespace Basico\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class UnidadeRepository extends AbstractEntityRepository {
	
	/**
	 * Retorna um array no qual o indice
	 * é o CodUnidade e valor é a Unidade
	 *
	 * @return array
	 */
	public function fetchPairs(array $whereParams = null, array $order = null){
	                
		/*if (!empty($whereParams)){
			$entities = $this->findBy($whereParams);
		} else {
			$entities = $this->findAll();
		}
	
		$arrayUnidade = array();
		foreach($entities as $entity){
			$arrayUnidade[$entity->getCodUnidade()] = $entity->getUnidade();
		}
		return $arrayUnidade;*/
		$qb = $this->getEntityManager()->createQueryBuilder();
		$qb->select('p.CodUnidade, p.Unidade')
		->from($this->_entityName,'p');
		$paramCount = 0;
		if(!empty($whereParams)){
			foreach ($whereParams as $campo => $valor){
				$paramCount++;
				$qb->andWhere("p.{$campo} = ?{$paramCount}");
				$qb->setParameter($paramCount, $valor);
			}
		}
		/*//seta o order by
		if(!empty($orderBy)){
			foreach ($orderBy as $order => $value){
				$qb->addOrderBy('p.'.$order,$value);
			}
		}*/
		 
		$entities = $qb->getQuery()->getResult();
		$arrayRetorno = array();
		foreach($entities as $entity){
			$arrayRetorno[$entity['CodUnidade']] = $entity['Unidade'];
		}
		return $arrayRetorno;
		
		
	}
	
	public function dataToHtmlTable ($data) {
        throw new \Exception('Método não implementado.');
    }

}