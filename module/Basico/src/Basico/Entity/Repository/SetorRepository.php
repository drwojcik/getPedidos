<?php

/**
 * Classe responsável por conter os métodos
 * de consulta no banco
 * 
 * @author Jonathan Fernando <jonathan@softwar.com.br>
 * @since 2014-06-27
 */
namespace Basico\Entity\Repository;


class SetorRepository extends AbstractEntityRepository
{    
    
	
	/**
	 * Retorna um array no qual o indice
	 * é o CodUnidade e valor é a Unidade
	 * @author Diego Wojcik <diego@softwar.com.br>
	 * @since 2015-11-23
	 * @return array
	 * alterado por Gabriel Henrique em 02/05/2017 - Inserido método de ordenar alfabeticamente os setores do array
	 */
	public function fetchPairs(array $whereParams = null, array $orderBy = null){

		$qb = $this->getEntityManager()->createQueryBuilder();
		$qb->select('p.CodSetor, p.Setor')
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
			$arrayRetorno[$entity['CodSetor']] = $entity['Setor'];
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