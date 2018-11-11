<?php
/**
 * Created by PhpStorm.
 * User: softwar
 * Date: 30/07/2018
 * Time: 10:30
 */

namespace Basico\Entity\Repository;

use Basico\Entity\Repository\AbstractEntityRepository;
use Jlib\View\Html\Tabela\Tabela;

class ClienteAnexoRepository  extends AbstractEntityRepository
{

    public function tabelaArquivo($CodClientes)
    {

        $sql = "select ID_Anexo, Arquivo, Descricao from Cliente_Anexo WHERE CodCliente = {$CodClientes}";
        $stmt = $this->getEntityManager()->getConnection()->query($sql);
        $result = $stmt->fetchAll();


        foreach ($result as $resultado){



            $link = $resultado['Arquivo'];




            ///upload/tramitacao-itens/14
            $resultado['Link'] = '<a href="'.$link.'"  target="_blank" class="btn btn-primary btn-rounded btn-condensed btn-sm btn-option"
    	  								data-toggle="tooltip" data-placement="top" title="Clique para baixar o arquivo.">
    	    								<i class="fa fa-paperclip" aria-hidden="true"></i> Abrir
    	    							 </a>';


            $data[] = $resultado;
        }

        if ($data != null) {
            $tabela = new Tabela($data);
            $tabela->addCampo('ID_Anexo', 'Cod');
            $tabela->addCampo('Descricao', 'Descricao');
            $tabela->addCampo('Link', 'Link');
            $tabela->setHtmlId('obra-anexo-list');
            $tabela->setAllowDelete(true);
            $tabela->setDeleteHrefClass('linkExcluir_Arquivo');
            $tabela->setDeleteLink('/basico/basico-cliente-anexo/delete-ajax');
            $tabela->addDeleteParam('ID_Anexo', false);
            $tabela->setShowDeleteIcon('fa fa-trash');
        return $tabela;
        }
    }


    /**
     * Formata os dados e retorna uma tabela em html.
     *
     * @param array $data
     */
    public function dataToHtmlTable($data)
    {
        // TODO: Implement dataToHtmlTable() method.
    }
}