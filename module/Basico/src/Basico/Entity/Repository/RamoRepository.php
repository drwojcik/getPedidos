<?php

/**
 * Classe responsável por conter os métodos
 * de consulta no banco
 * 
 * @author Jonathan Fernando <jonathan@softwar.com.br>
 * @since 2013-10-30
 */
namespace Basico\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class RamoRepository extends EntityRepository
{
	/**
	 * Acrescentada ordenação alfabética dos resultados - #13369
	 * @author Gabriel Henrique <gabrielh@softwar.com.br>
	 * @since 2017-01-11
	 */
	public function fetchPairs(array $whereParams = null, array $orderBy = null){
		/*$entities = $this->findBy($whereParams, $orderBy);
		 	
		$arrayRamo = array();
		foreach($entities as $entity){
		$arrayRamo[$entity->getCodRamo()] = $entity->getRamo();
		}
		return $arrayTipoFuncionario;*/
	
		$qb = $this->getEntityManager()->createQueryBuilder();
		$qb->select('p.CodRamo, p.Ramo')
		->from($this->_entityName,'p');
		$paramCount = 0;
		if(!empty($whereParams)){
			foreach ($whereParams as $campo => $valor){
				$paramCount++;
				$qb->andWhere("p.{$campo} = ?{$paramCount}");
				$qb->setParameter($paramCount, $valor);
			}
		}
		
		//Ordena alfabéticamente
		$qb->addOrderBy('p.Ramo');
	
		$entities = $qb->getQuery()->getResult();
		$arrayRetorno = array();
		foreach($entities as $entity){
			$arrayRetorno[$entity['CodRamo']] = $entity['Ramo'];
		}
		return $arrayRetorno;
	
	}
}

?>