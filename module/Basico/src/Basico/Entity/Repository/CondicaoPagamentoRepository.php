<?php

/**
 * Classe responsável por conter os métodos
 * de consulta no banco
 * 
 * @author Jonathan Fernando <jonathan@softwar.com.br>
 * @since 2014-10-13
 */
namespace Basico\Entity\Repository;
use Basico\Entity\Repository\AbstractEntityRepository;
use Doctrine\ORM\EntityRepository;

class CondicaoPagamentoRepository extends AbstractEntityRepository
{
	/**
	 * Retorna um array com os registros da tabela
	 * @return array
	 */
	public function fetchPairs(array $whereParams = null, array $orderBy = null){
		/*$entities = $this->findBy($whereParams, $orderBy);
		 
		$arrayRetorno = array();
		foreach($entities as $entity){
			$arrayRetorno[$entity->getCodPagamento()] = $entity->getCondicao();
		}
		return $arrayRetorno;*/
		$qb = $this->getEntityManager()->createQueryBuilder();
		$qb->select('c.CodPagamento, c.Condicao')
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
		foreach($entities as $entity){
			$arrayRetorno[$entity['CodPagamento']] = $entity['Condicao'];
		}
		return $arrayRetorno;
	}
	
	/**
	 * Retorna um array com as contas a pagar ativas
	 * @return array
	 */
	public function fetchCondicaoAtivas(){
		//query builder
		$qb = $this->getEntityManager()->createQueryBuilder();
		$qb->select('c.CodPagamento, c.Condicao')
		->from($this->_entityName,'c')
		->where('c.Ativo <> ?1')
		//->andWhere('c.Tipo = ?2')
		->setParameter(1, 0)
		//->setParameter(2, 'P')
		->addOrderBy('c.Condicao', 'ASC');
		$entities = $qb->getQuery()->getResult();
		 
		$arrayRetorno = array();
		foreach($entities as $entity){
			//$arrayRetorno[$entity->getCodPagamento()] = $entity->getCondicao();
			$arrayRetorno[$entity['CodPagamento']] = $entity['Condicao'];
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