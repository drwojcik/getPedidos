<?php

namespace Basico\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class UfRepository extends EntityRepository{
	
	public function dataToHtmlTable ($data) {
		throw new \Exception('Método não implementado.');
	}
	
	/**
	 * Retorna um array no qual o indice é o CodAdvocaciaRito a e valor é a Rito.
	 *
	 * @return array
	 */
	public function fetchPairs(array $whereParams = null, array $orderBy = null){
		$entities = $this->findBy($whereParams, $orderBy);
			
		$arrayUf = array();
		foreach($entities as $entity){
			$arrayUf[$entity->getCodigoUf()] = $entity->getUf();
		}
		return $arrayUf;
	}
	
}