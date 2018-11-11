<?php

namespace Basico\Entity\Repository;

use Basico\Entity\Repository\AbstractEntityRepository;

class IrRepository extends AbstractEntityRepository {
	
	/**
	 * @see Basico\Entity\Repository.AbstractEntityRepository::dataToHtmlTable()
	 */
	public function dataToHtmlTable($data) {
		throw new \Exception('Método não implementado.');
	}
	
	
	/**
	 * Retorna Campo Informado
	 * @return array
	 * @author Diego Wojcik <diego@softwar.com.br>
	 * @since 2015-07-30
	 */
	public function findByUf($uf){
		$qb = $this->getEntityManager()->createQueryBuilder();
		$qb->select('ss')
		->from('Basico\Entity\Ir', 'ss')
		->where('ss.Uf = ?1')
		->setParameter(1, $uf);
	
		#$sql = $qb->getQuery()->getSQL();
		#$parameters = $qb->getQuery()->getParameters()->toArray();
	
		$dados = $qb->getQuery()->getOneOrNullResult();
		return $dados;
	}
}