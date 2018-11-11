<?php

/**
 * Classe responsável por conter os métodos
 * de consulta no banco
 * 
 * @author Diego Wojcik <diego@softwar.com.br>
 * @since 2015-12-24
 */
namespace Basico\Entity\Repository;

use Basico\Entity\Repository\AbstractEntityRepository;

use Jlib\View\Html\Tabela\Tabela;

class ClienteContatoRepository extends AbstractEntityRepository
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
            $arrayRetorno[$entity->getID()] = $entity->getNome();
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
        $tabela->addCampo('Nome', 'Nome');
        $tabela->addCampo('Fone1', 'Fone', array('class' => 'mask_fone_grid'));
        $tabela->addCampo('Cargo', 'Cargo');
        $tabela->addCampo('Celular', 'Celular', array('class' => 'mask_fone_grid'));
        $tabela->addCampo('Aniversario', 'Aniversário', array('class' => 'mask_aniversario center'));
        $tabela->setAllowEdit(true);
        $tabela->setEditLink('/basico/cliente-contato/get-form');
        $tabela->addEditParam('Cliente', false);	//Parent ID
        $tabela->addEditParam('ID', false);			//Child ID
        $tabela->setEditHrefClass('showform_prpctContato showform_clienteContato linkOpcoes');
        $tabela->setAllowDelete(true);
        $tabela->setDeleteLink('/basico/cliente-contato/delete-ajax');
        $tabela->addDeleteParam('Cliente', false);
        $tabela->addDeleteParam('ID', false);
        $tabela->setDeleteHrefClass('linkExcluir_prpctContato linkOpcoes');

        
        return $tabela;
    }
}

?>