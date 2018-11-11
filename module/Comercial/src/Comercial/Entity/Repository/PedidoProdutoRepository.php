<?php

namespace Comercial\Entity\Repository;

use Basico\Entity\Repository\AbstractEntityRepository;

use Jlib\View\Html\Tabela\Tabela;

class PedidoProdutoRepository extends AbstractEntityRepository
{

    /**
     * Retorna um array com os registros da tabela
     *
     * @return array
     * @example
     * 		array(1 => 'contato x', 2 => 'contato y')
     */
    public function fetchPairs(array $whereParams = null, array $orderBy = null){
        $entities = $this->findBy($whereParams, $orderBy);
         
        $arrayRetorno = array();
        foreach($entities as $entity){
            $arrayRetorno[$entity->getIdSeq()] = $entity->getNome();
        }
        return $arrayRetorno;
    }        
    
    /**
     * @see Basico\Entity\Repository.AbstractEntityRepository::dataToHtmlTable()
     */
    public function dataToHtmlTable ($data)
    {
        //cria uma nova Tabela
        $tabela = new Tabela($data);
        $tabela->addCampo('idPedidoProduto', 'Cód');
        $tabela->addCampo('Produto', 'Produto');
        $tabela->addCampo('QtdeProduto', 'Qtde');
        $tabela->addCampo('Preco', 'Valor Unitário');
        $tabela->addCampo('SubTotal', 'SubTotal');
        $tabela->setAllowEdit(false);
        $tabela->setEditLink('/comercial/pedido-produto/get-form');
        $tabela->addEditParam('idPedido', false);	//Parent ID
        $tabela->addEditParam('idPedidoProduto', false);			//Child ID
        $tabela->setEditHrefClass('showform_prpctPedido linkOpcoes');
        $tabela->setAllowDelete(true);
        $tabela->setDeleteLink('/comercial/pedido-produto/delete-ajax');
        $tabela->addDeleteParam('idPedido', false);
        $tabela->addDeleteParam('idPedidoProduto', false);
        $tabela->setDeleteHrefClass('linkExcluir_prpctPedido');
    
        return $tabela;
    }
}

?>