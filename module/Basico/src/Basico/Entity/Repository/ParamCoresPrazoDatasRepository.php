<?php

/**
 * Classe responsável por conter os métodos
 * de consulta no banco
 * 
 * @author Jonathan Fernando <jonathan@softwar.com.br>
 * @since 2013-12-06
 */
namespace Basico\Entity\Repository;

class ParamCoresPrazoDatasRepository extends AbstractEntityRepository
{                

    public function findByTipoParametroAndPrazo($tipoParametro, $diasPrazo){
        $repPrazoCores = $this->getEntityManager()->getRepository('Basico\Entity\ParamCores');
        $entPrazoCores = $repPrazoCores->findOneByDescricao($tipoParametro);
        
        if ($entPrazoCores){	        
            //query builder
            $qb = $this->getEntityManager()->createQueryBuilder();
            $qb->select('p')
            	->from($this->_entityName,'p')
                ->where('p.ParamCores = ?1')
            	->andWhere('p.NumDiasAntes >= ?2')
            	->andWhere('p.NumDiasDepois <= ?2')
            	->setParameter(1, $entPrazoCores->getId())
            	->setParameter(2, $diasPrazo);
            $result = $qb->getQuery()->getOneOrNullResult();
            
            return $result;
        } else {
            throw new \Exception("O parâmetro '{$tipoParametro}' não foi encontrado.");
        }
    }    
    
	/**
     * @see Basico\Entity\Repository.AbstractEntityRepository::dataToHtmlTable()
     */
	public function dataToHtmlTable ($data)
    {
        throw new \Exception('Método não implementado.');
    }    
}

?>