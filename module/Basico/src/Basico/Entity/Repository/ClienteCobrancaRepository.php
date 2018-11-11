<?php

namespace Basico\Entity\Repository;
use Jlib\View\Html\Tabela\Tabela;

use Basico\Entity\Repository\AbstractEntityRepository;
class ClienteCobrancaRepository extends AbstractEntityRepository {
	

	/**
	 * @see Basico\Entity\Repository.AbstractEntityRepository::dataToHtmlTable()
	 */
	public function dataToHtmlTable ($data) {
		
		
		$tab = new Tabela($data);
		$tab->addCampo('CodCliente', 'Código');
		$tab->addCampo('CodClienteCobranca', 'Código Cobrança');
		$tab->addCampo('Valor', 'Valor');
		$tab->setAllowEdit(false);
// 		$tab->setEditLink('/evento/evento-preco-tipo-pagamento/edit');
// 		$tab->setEditHrefClass('showform_eventoPrecoTipoPagamento_edit');
// 		$tab->addEditParam('CodObra', false);
// 		$tab->addEditParam('CodEventoPreco', false);
// 		$tab->addEditParam('CodEventoTipoPagamento', false);
		
		return $tab;
		
	}
	
	public function findByQueryBuilder(array $where, $returnAsTable = null){
		//Cria o query builder
		$qb = $this->_em->createQueryBuilder();
		$qb->select('c')
		->from('Basico\Entity\ClienteCobranca', 'c')
		->where("1=1");
	
		if (isset($where['CodClienteCobranca'])){
			$codcobranca = $where['CodClienteCobranca'];
			$qb->andWhere("c.CodClienteCobranca = $codcobranca");
			//'%'.$where['Nome'].'%'
		}
	
		/*if (isset($where['Valor'])){
		 $valor = $where['Valor'];
		 $qb->andWhere("c.Valor = '$valor'");
		 //'%'.$where['Nome'].'%'
			}*/
	
		//Seta o Order by
		$qb->addOrderBy('c.CodCliente', 'ASC');
		$entities = $qb->getQuery()->getResult();
		if ($returnAsTable){
			$dados = array();
			foreach ($entities as $entity){
				$dados[] = $entity->toTable();
			}
			return $this->dataToHtmlTable($dados);
		} else {
			return $entities;
		}
	}
	
}