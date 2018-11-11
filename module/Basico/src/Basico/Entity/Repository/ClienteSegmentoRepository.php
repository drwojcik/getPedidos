<?php
/**
 * Classe responsável por conter os métodos
 * de consulta no banco
 *
 * @author Gabriel Henrique <gabrielh@softwar.com.br>
 * @since 2017-16-01
 */
namespace Basico\Entity\Repository;
use Jlib\View\Html\Tabela\Tabela;
use Basico\Entity\Repository\AbstractEntityRepository;

class ClienteSegmentoRepository extends AbstractEntityRepository {
	

	/**
	 * @see Basico\Entity\Repository.AbstractEntityRepository::dataToHtmlTable()
	 */
	public function dataToHtmlTable ($data) {
		
		
		$tab = new Tabela($data);
		$tab->addCampo('CodCliente', 'Código');
		$tab->addCampo('CodClienteSegmento', 'Código');
		$tab->setAllowEdit(false);
// 		$tab->setEditLink('/evento/evento-preco-tipo-pagamento/edit');
// 		$tab->setEditHrefClass('showform_eventoPrecoTipoPagamento_edit');
// 		$tab->addEditParam('CodObra', false);
// 		$tab->addEditParam('CodEventoPreco', false);
// 		$tab->addEditParam('CodEventoTipoPagamento', false);
		
		return $tab;
		
	}
	
	//Procura pela ID do respectivo cliente e segmento
	public function findCodClienteSegmento($CodSegmento, $CodCliente){
		$qb =$this->_em->createQueryBuilder();
		$qb->select('e.CodClienteSegmento')
		->from('Basico\Entity\ClienteSegmento', 'e')
		->where('e.CodSegmento = ?1 AND e.CodCliente = ?2')
		->setParameter(1, $CodSegmento)
		->setParameter(2, $CodCliente);
		 
		 
		$entity = $qb->getQuery()->getResult();
		$codigo = $entity[0]['CodClienteSegmento'];
		
		return $codigo;
	}
	
}