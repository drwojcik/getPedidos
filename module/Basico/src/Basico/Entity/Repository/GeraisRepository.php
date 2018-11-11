<?php

/**
 * Classe responsável por conter os métodos
 * de consulta no banco
 * 
 * @author Jonathan Fernando <jonathan@softwar.com.br>
 * @since 2014-02-07
 */
namespace Basico\Entity\Repository;


class GeraisRepository extends AbstractEntityRepository
{

    /**
     * Retorna um array com os registros da tabela
     * Alterado para pegar apenas os campso necessários
     * @return array
     * @example
     * 		array('1' => 'empresa 1', '2' => 'empresa 2')
     */
    public function fetchPairs(array $whereParams = null, array $orderBy = null, $empresaHabilitada = null){
    	$qb = $this->getEntityManager()->createQueryBuilder();
    	$qb->select('o.CodEmpresa, o.Empresa')
    	->from('Basico\Entity\Gerais', 'o');
    	
    	//seta o where na query
    	$paramCount = 0;
    	foreach ($whereParams as $campo => $valor){
    		$paramCount++;
    		$qb->andWhere("o.{$campo} = ?{$paramCount}");
    		$qb->setParameter($paramCount, $valor);
    	}
    	if($empresaHabilitada){
    		$qb->andWhere("o.CodEmpresa IN ($empresaHabilitada)");
    	}
    	//seta o order by
    	foreach ($orderBy as $order => $value){
    		$qb->addOrderBy('o.'.$order,$value);
    	}
    	
    	$entities = $qb->getQuery()->getResult();
    		
    	$arrayGerais = array();
    	foreach($entities as $entity){
    		//$arrayObra[$entity->getCodObra()] = $entity->getObra();
    		$arrayGerais[$entity['CodEmpresa']] = $entity['Empresa'];
    	}
    	return $arrayGerais;
    	
        /*$entities = $this->findBy($whereParams, $orderBy);
         
        $arrayRetorno = array();
        foreach($entities as $entity){
            $arrayRetorno[$entity->getCodEmpresa()] = $entity->getEmpresa();
        }
        return $arrayRetorno;*/
    }
    
 

	/**
	 * Retorna Campo Informado
	 * @return array
	 * @author Diego Wojcik <diego@softwar.com.br>
	 * @since 2015-07-23
	 */
    public function findCampo($campo, $id = null, $limit){
    
    
    	$qb = $this->_em->createQueryBuilder();
    	$qb->select('c.'.$campo.'')
    	->from('Basico\Entity\Gerais', 'c')
    	->where('1 = 1');
    	//->where('c.Parcela = :Parcela');
    	
    	if($id){
    		$qb->andwhere('c.CodEmpresa = :CodEmpresa');
    		$qb->setParameter('CodEmpresa', $id);
    	}
    	if ($limit > 0){
    		$qb->setFirstResult($offset)
    		->setMaxResults($limit);
    	}
    	#$sql = $qb->getQuery()->getSQL();
    	return $qb->getQuery()->getResult();
    }
    
	public function dataToHtmlTable ($data) {
        throw new \Exception('Método não implementado.');
    }

    
    
}

?>