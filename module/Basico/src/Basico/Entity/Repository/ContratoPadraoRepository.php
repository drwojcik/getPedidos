<?php

/**
 * Classe responsável por conter os métodos
 * de consulta no banco
 * 
 * @author Diego Wojcik <diego@softwar.com.br>
 * @since 2016-01-26
 */
namespace Basico\Entity\Repository;

class ContratoPadraoRepository extends AbstractEntityRepository
{
    /**
     * @see Basico\Entity\Repository.AbstractEntityRepository::dataToHtmlTable()
     */
	public function dataToHtmlTable ($data)
    {
        throw new \Exception('Método não implementado.');
    }

    /**
     * Retorna um array com os registros da tabela
     *
     * @return array
     * @example
     * 		array(1 => 'funcionario x', 2 => 'funcionario y')
     */
    public function fetchPairs(array $whereParams = null, array $orderBy = null){
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('p.CodContratoPadrao, p.Titulo')
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
            $arrayRetorno[$entity['CodContratoPadrao']] = $entity['Titulo'];
        }
        return $arrayRetorno;
    }
}

?>