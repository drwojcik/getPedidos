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

class CidadeRepository extends EntityRepository
{

    /**
     * Retorna um array com os registros da tabela
     *
     * @return array
     * @example
     * 		array('cidade x' => 'cidade x', 'cidade y' => 'cidade y')
     */
    public function fetchPairs(array $whereParams = null, array $orderBy = null){
        /*$entities = $this->findBy($whereParams, $orderBy);
         
        $arrayRetorno = array();
        foreach($entities as $entity){
            $arrayRetorno[$entity->getCodCidade()] = $entity->getCidade();
        }
        return $arrayRetorno;
        */
    	$qb = $this->getEntityManager()->createQueryBuilder();
    	$qb->select('o.CodCidade, o.Cidade')
    	->from($this->getEntityName(), 'o');
    	
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
    		
    	$arrayRetorno = array();
    	foreach($entities as $entity){
    		//$arrayObra[$entity->getCodObra()] = $entity->getObra();
    		$arrayRetorno[$entity['CodCidade']] = $entity['Cidade'];
    	}
    	return $arrayRetorno;
        
        
    }
    
    /**
     * Retorna um array com os registros da tabela
     *
     * @return array
     * @example
     * 		array(1 => 'cidade x', 2 => 'cidade y')
     */
    public function fetchPairsCod(array $whereParams = null, array $orderBy = null){
       /* $entities = $this->findBy($whereParams, $orderBy);
         
        $arrayRetorno = array();
        foreach($entities as $entity){
            $arrayRetorno[$entity->getCodCidade()] = $entity->getCidade();
        }
        return $arrayRetorno;*/
    	$qb = $this->getEntityManager()->createQueryBuilder();
    	$qb->select('o.CodCidade, o.Cidade')
    	->from($this->getEntityName(), 'o');
    	 
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
    	
    	$arrayRetorno = array();
    	foreach($entities as $entity){
    		//$arrayObra[$entity->getCodObra()] = $entity->getObra();
    		$arrayRetorno[$entity['CodCidade']] = $entity['Cidade'];
    	}
    	return $arrayRetorno;
    }
    /**
     * Retorna um array com os registros da tabela
     *
     * @return array
     * @example
     * 		array(Cidade x => 'cidade x', cidade y => 'cidade y')
     */
    public function fetchPairsTexto(array $whereParams = null, array $orderBy = null){
       /* $entities = $this->findBy($whereParams, $orderBy);
         
        $arrayRetorno = array();
        foreach($entities as $entity){
            $arrayRetorno[$entity->getCodCidade()] = $entity->getCidade();
        }
        return $arrayRetorno;*/
    	$qb = $this->getEntityManager()->createQueryBuilder();
    	$qb->select('o.CodCidade, o.Cidade')
    	->from($this->getEntityName(), 'o');
    	 
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
    	
    	$arrayRetorno = array();
    	foreach($entities as $entity){
    		//$arrayObra[$entity->getCodObra()] = $entity->getObra();
    		$arrayRetorno[$entity['Cidade']] = $entity['Cidade'];
    	}
    	return $arrayRetorno;
    }
    
    /**
     * Retorna um array com os UF's da tabela
     *
     * @return array
     * @example
     * 		array('pr' => 'pr', 'sp' => 'sp')
     */
    public function fetchPairsUF(array $whereParams = null, array $orderBy = null){        
        $qb = $this->_em->createQueryBuilder();
        $qb->select('c.UF')
	        ->from($this->getEntityName(), 'c')
        	->groupBy('c.UF');
        $result = $qb->getQuery()->getResult();
         
        $arrayRetorno = array();
        foreach($result as $reg){
            $arrayRetorno[$reg['UF']] = $reg['UF'];
        }
        return $arrayRetorno;
    }
    
    /**
     * Busca a Mesorregião de acordo com a Cidade selecionada
     * @author Gabriel Henrique <gabrielh@softwar.com.br>
     * @param string $CodCidade
     */
    public function findMesorregiao($CodCidade){

    	$qb = $this->_em->createQueryBuilder();
    	$qb->select('c.Mesorregiao')
    	->from($this->getEntityName(), 'c')
    	->Where('c.CodCidade = ?1')
    	->setParameter('1', $CodCidade);
    	$result = $qb->getQuery()->getResult();
    	return $result;
    }
    
    /**
     * Retorna um array com os registros das mesorregiões da tabela
     * @author Gabriel Henrique <gabrielh@softwar.com.br>
     * @return array
     */
    public function fetchPairsMesorregiao(array $whereParams = null, array $orderBy = null){

    	$qb = $this->getEntityManager()->createQueryBuilder();
    	$qb->select('o.CodMesorregiao, o.Mesorregiao')
    	->from($this->getEntityName(), 'o');
    	 
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
    
    	$arrayRetorno = array();
    	foreach($entities as $entity){
    		$arrayRetorno[$entity['CodMesorregiao']] = $entity['Mesorregiao'];
    	}
    	return $arrayRetorno;
    
    
    }
    
    /**
     * @author Gabriel Henrique <gabrielh@softwar.com.br> - #13598
     * @since 2017-04-10
     * @param unknown $valor
     * Método utilizado para encontrar as cidades a partir do texto informado
     */
    public function findCidadeLike($valor){
    
    	$sql = "SELECT top 20 CodCidade, Cidade from Cidade WHERE Cidade like '%{$valor}%'";
    	$stmt = $this->_em->getConnection()->query($sql);
    	$result = $stmt->fetchAll();
    
    	return $result;
    }
    
}

?>