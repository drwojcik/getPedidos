<?php

namespace Basico\Entity\Repository;

use Basico\Entity\Repository\AbstractEntityRepository;

class ViewTipoFornecedorRepository extends AbstractEntityRepository {
	
    /**
     * Retorna um array no qual o indice
     * é o Codigo e valor é o Nome
     *
     * @return array
     */
    public function fetchPairs(array $whereParams = null, array $orderBy = null){
        /*$entities = $this->findBy($whereParams, $orderBy);
        	
        $arrayCentroCusto = array();
        foreach($entities as $entity){
            $arrayCentroCusto[$entity->getCodigo()] = $entity->getNome();
        }
        return $arrayCentroCusto;*/
    	$qb = $this->getEntityManager()->createQueryBuilder();
    	$qb->select('o.Codigo, o.Nome')
    	->from('Basico\Entity\ViewTipoFornecedor', 'o');
    	
    	//seta o where na query
    	$paramCount = 0;
    	foreach ($whereParams as $campo => $valor){
    		$paramCount++;
    		$qb->andWhere("o.{$campo} = ?{$paramCount}");
    		$qb->setParameter($paramCount, $valor);
    	}
    	
    	//seta o order by
    	foreach ($orderBy as $order => $value){
    		$qb->addOrderBy('o.'.$order,$value);
    	}
    	
    	$entities = $qb->getQuery()->getResult();
    		
    	$arrayObra = array();
    	foreach($entities as $entity){
    		//$arrayObra[$entity->getCodObra()] = $entity->getObra();
    		$arrayObra[$entity['Codigo']] = $entity['Nome'];
    	}
    	return $arrayObra;
    	
    	
    }
    
    /**
     * @see Basico\Entity\Repository.AbstractEntityRepository::dataToHtmlTable()
     */
    public function dataToHtmlTable ($data)
    {
        throw new \Exception('Método não implementado.');        
    }
}