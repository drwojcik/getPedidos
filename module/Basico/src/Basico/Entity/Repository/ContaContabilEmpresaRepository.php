<?php

/**
 * Classe responsável por conter os métodos
 * de consulta no banco
 * 
 * @author Diego Wojcik <diego@softwar.com.br>
 * @since 2015-01-14
 */
namespace Basico\Entity\Repository;


class ContaContabilEmpresaRepository extends AbstractEntityRepository
{

    /**
     * Retorna um array com os registros da tabela
     * @return array
     */
    public function fetchPairs(array $whereParams = null, array $orderBy = null){
        $entities = $this->findBy($whereParams, $orderBy);
         
        $arrayRetorno = array();
        foreach($entities as $entity){
            $arrayRetorno[$entity->getCodConta()] = $entity->getConta();
        }
        return $arrayRetorno;
    }
    
    /**
     * Retorna um array com as contas a pagar ativas
     * @return array
     */
    public function fetchPairsAtivas(){
    	//query builder
    	$qb = $this->getEntityManager()->createQueryBuilder();
    	$qb->select('c')
    	->from($this->_entityName,'c')
    	->where('c.Ativa <> ?1')
    	->setParameter(1, 0)
    	->addOrderBy('c.Conta', 'ASC');
    	$sql = $qb->getQuery()->getSQL();
    	$entities = $qb->getQuery()->getResult();
    	 
    	$arrayRetorno = array();
    	foreach($entities as $entity){
    		$arrayRetorno[$entity->getCodConta()] = $entity->getConta().' - ('.$entity->getCodigoPlanoContas().')';
    	}
    	return $arrayRetorno;
    }
    
    /**
     * Retorna um array com as contas a pagar ativas
     * @return array
     */
    public function fetchPairsAPagarAtivas(){
        //query builder
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('c')
	        ->from($this->_entityName,'c')
	        ->where('c.Ativa <> ?1')
	        ->andWhere('c.Tipo = ?2')
	        ->setParameter(1, 0)
	        ->setParameter(2, 'P')
       		->addOrderBy('c.Conta', 'ASC');
        $entities = $qb->getQuery()->getResult();
         
        $arrayRetorno = array();
        foreach($entities as $entity){
            $arrayRetorno[$entity->getCodConta()] = $entity->getConta();
        }
        return $arrayRetorno;
    }
    
    
    /**
     * @see Basico\Entity\Repository.AbstractEntityRepository::dataToHtmlTable()
     */
	public function dataToHtmlTable($data) {
		throw new \Exception('Método não implementado.');   
    }

    
    
}

?>