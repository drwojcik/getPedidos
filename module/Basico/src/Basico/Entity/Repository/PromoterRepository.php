<?php

namespace Basico\Entity\Repository;

use Basico\Entity\Repository\AbstractEntityRepository;
use Jlib\View\Html\Tabela\Tabela;
use Jlib\Util\FormatValue;

class PromoterRepository extends AbstractEntityRepository {
	
	public function dataToHtmlTable ($data)
	{
		$tab = new Tabela($data);
		$tab->addCampo('CodPromoter', 'Código');
		$tab->addCampo('Nome', 'Nome');
		$tab->addCampo('CPF', 'CPF');
		$tab->setHtmlId('promoter-list');
		$tab->setAllowEdit(true);
		$tab->setEditLink('/food-services/promoter/edit');
		$tab->addEditParam('CodPromoter', false);
		$tab->setAllowDelete(true);
		$tab->setDeleteLink('/food-services/promoter/delete');
		$tab->setDeleteHrefClass('btnExcluir');
		$tab->addDeleteParam('CodPromoter', false);
		return $tab;
	}
	
	public function findByLoginAndPassword($login, $password){
		$format = new FormatValue();
		$login = $format->cleanField($login);
		$promoter = $this->findOneBy(array('Usuario' => $login));
		if ($promoter){
			if ($promoter->getSenha() == $password){
				return $promoter;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	public function findPromoter(array $where){
		$format = new FormatValue();
		$qb = $this->getEntityManager()->createQueryBuilder();
		$qb->select('v')
		->from('Basico\Entity\Promoter', 'v');
	
		if (!empty($where['Nome'])){
			$nome = $where['Nome'];
			$qb->andWhere("v.Nome LIKE '%{$nome}%'");
	
			unset($where['Nome']);
		}
	
		$entities = $qb->getQuery()->getResult();
		$dados = array();
		foreach ($entities as $entity){
			$cpf = $entity->getCPF();
			if (isset($cpf) && !empty($cpf)){
				$cpf = $format->mask($cpf, '###.###.###-##');
			
				$entity->setCPF($cpf);
			}
			
			$dados[] = $entity->toTable();
		}
		return $this->dataToHtmlTable($dados);
	}
	
	public function findFestaPromoter(array $where){
		$qbNot = $this->getEntityManager()->createQueryBuilder();
		$qbNot->select('p.CodPromoter')->from('FoodServices\Entity\FestaPromoter', 'fp');
		$qbNot->innerJoin('fp.CodPromoter', 'p');
		$qbNot->andWhere("fp.CodFesta = {$where['CodFesta']}");
		
		$qb = $this->getEntityManager()->createQueryBuilder();
		$qb->select('v')->from('Basico\Entity\Promoter', 'v');
		$qb->where($qb->expr()->notIn('v.CodPromoter',$qbNot->getDQL()));
	
		$entities = $qb->getQuery()->getResult();
		return $entities;
	}
}