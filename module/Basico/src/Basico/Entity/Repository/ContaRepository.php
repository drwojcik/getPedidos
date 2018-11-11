<?php

/**
 * Classe responsável por conter os métodos
 * de consulta no banco
 * 
 * @author Jonathan Fernando <jonathan@softwar.com.br>
 * @since 2014-02-07
 */
namespace Basico\Entity\Repository;


use Jlib\View\Html\Tabela\Tabela;
class ContaRepository extends AbstractEntityRepository
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
     * Retorna um array com as contas a pagar ativas
     * @return array
     */
    public function fetchPairsAReceberAtivas(){
        //query builder
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('c')
	        ->from($this->_entityName,'c')
	        ->where('c.Ativa <> ?1')
	        ->andWhere('c.Tipo = ?2')
	        ->setParameter(1, 0)
	        ->setParameter(2, 'R')
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
		$tab = new Tabela($data);
		$tab->addCampo('CodConta', 'Código');
		$tab->addCampo('Conta', 'Conta');
		$tab->addCampo('Tipo', 'Tipo');
		$tab->addCampo('CodSubGrupo', 'SubGrupo');
		$tab->addCampo('CodigoPlanoContas', 'Código Plano de Contas');
		$tab->addCampo('Ativa', 'Ativo');
		$tab->addCampo('Aporte', 'Aporte');
		$tab->addCampo('Patrimonio', 'Patrimônio');
		$tab->addCampo('ContaOcultaContasPagar', 'Conta Oculta');
		$tab->setHtmlId('conta-list');
		$tab->setAllowEdit(true);
		$tab->setEditLink('/cadastro/conta/edit');
		$tab->addEditParam('CodConta', false);
		$tab->setAllowDelete(true);
		$tab->setDeleteLink('/cadastro/conta/delete');
		$tab->addDeleteParam('CodConta', false);
		$tab->setDeleteHrefClass('linkExcluir_Conta linkOpcoes');
	
		return $tab;
    }

    /**
     * Excetuta uma consulta utilizando o Query Builder do Doctrine.
     * Utilizado para consultas mais complexas.
     *
     * @param array $where
     * @param boolean $returnAsTable (opcional)
     * @return array | Jlib\View\Html\Tabela\Tabela
     */
    
    public function findByQueryBuilder(array $where, $returnAsTable = null){
    	//Cria o query builder
    	$qb = $this->_em->createQueryBuilder();
    	$qb->select('c')
    	->from('Basico\Entity\Conta', 'c')
    	->leftJoin('Cadastro\Entity\SubGrupo', 'g', 'WITH', 'c.CodSubGrupo = g.CodSubGrupo')
    	->where("1 = 1");
    
    	foreach ($where as $chave => $reg){
    		if (isset($where[$chave])){
    			$cod = $where[$chave];
    			$qb->andWhere("c.$chave = '$cod'");
    		}
    	}
    	//Seta o Order by
    	$qb->addOrderBy('c.Conta', 'ASC');
    	$entities = $qb->getQuery()->getResult();
    	if ($returnAsTable){
    		$dados = array();
    		foreach ($entities as $entity){
    			
//     			if ($entity['Ativo'] == '1') {
//     				$entity['Ativo'] = 'Sim';
//     			}else {
//     				$entity['Ativo'] = 'Não';
//     			}
    			$dados[] = $entity->toTable();
    		}
    		return $this->dataToHtmlTable($dados);
    	} else {
    		return $entities;
    	}
    }
    
    /**
     * @author Gabriel Henrique <gabrielh@softwar.com.br>
     * @since 30/03/2017
     * Busca as contas de acordo com o tipo desejado.
     *
     * @param string $valor
     */
    public function findContaLike($valor, $tipo){
    
    	$qb = $this->_em->createQueryBuilder();
    	$qb->select('c')
    	->from($this->getEntityName(), 'c')
    	->Where('c.Conta LIKE :conta')
    	->andWhere('c.Tipo = :tipo')
    	->setParameter(':conta', '%'.$valor.'%')
    	->setParameter(':tipo', $tipo)
    	->setMaxResults(20);
    	$result = $qb->getQuery()->getResult();
    	return $result;
    }
    
}

?>