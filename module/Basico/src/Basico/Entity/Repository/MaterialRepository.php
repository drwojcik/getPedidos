<?php

namespace Basico\Entity\Repository;

use Basico\Entity\Repository\AbstractEntityRepository;
use Jlib\View\Html\Tabela\Tabela;

class MaterialRepository extends AbstractEntityRepository {

	public function dataToHtmlTable ($data) {
        throw new \Exception('M�todo n�o implementado.');
    }
	
	/**
	 * Busca os materiais ativos de acordo com o nome pesquisado.
	 * 
	 * @param string $valor
	 * @param string $ativo
	 */
	public function findMaterialLike($valor, $ativo = 'S',$RepCadastradas = null){
		if ($ativo != 'S' && $ativo != 'N'){
			$ativo = 'S';
		}


		$qb = $this->_em->createQueryBuilder();
		$qb->select('m')
			->from($this->getEntityName(), 'm')
			->where('m.Ativo = :ativo')
            ->andWhere('m.Material LIKE :material');
		//Alterado em 18/05/2018 por Diego Wojcik #17901 Corrigo o nome da variável
        if($RepCadastradas != null){
            //adicionado por mateusc <mateusc@softwar.com.br> #17901
            //tira da consulta materiais ja cadastrados na representada
		    $qb->andWhere('m.CodMaterial not in (:CodMaterialF )')
            ->setParameter('CodMaterialF',$RepCadastradas);

        }

        $qb->setParameter('ativo', $ativo)
			->setParameter(':material', '%'.$valor.'%')
			->setMaxResults(20);
		$result = $qb->getQuery()->getResult();
		return $result; 
	}
	
	
	/**
	 * @see \Basico\Entity\Repository\AbstractEntityRepository::fetchPairs()
	 */
	public function fetchClassificacoesAtivas(array $whereParams = null, array $order = null){
		//query builder
		$qb = $this->getEntityManager()->createQueryBuilder();
		$qb->select('c.CodClassificacao, c.Classificacao')
		->from('Producao\Entity\Classificacao','c')
		->where("c.Ativo = 'S'")
		//->andWhere('c.Tipo = ?2')
		//->setParameter(2, 'P')
		->addOrderBy('c.Classificacao', 'ASC');
		$entities = $qb->getQuery()->getResult();
			
		$arrayRetorno = array();
		foreach($entities as $entity){
			//$arrayRetorno[$entity->getCodPagamento()] = $entity->getCondicao();
			$arrayRetorno[$entity['CodClassificacao']] = $entity['Classificacao'];
		}
		return $arrayRetorno;
	}

}