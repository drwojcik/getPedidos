<?php

namespace Basico\Entity\Repository;

use Basico\Entity\Repository\AbstractEntityRepository;

class IssRepository extends AbstractEntityRepository{
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
    public function findByCidadeConta($CodCidade, $TipoConta){
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('ss')
        	->from('Basico\Entity\Iss', 'ss')
        	->innerJoin('ss.Cidade', 'c')
        	->where('c.CodCidade = ?1')
        	->andWhere('ss.TipoConta = ?2')
        	->setParameter(1, $CodCidade)
        	->setParameter(2, $TipoConta);
        
        #$sql = $qb->getQuery()->getSQL();
        #$parameters = $qb->getQuery()->getParameters()->toArray();
        
        $dados = $qb->getQuery()->getOneOrNullResult();
        return $dados;
    }
}