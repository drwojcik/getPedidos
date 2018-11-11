<?php

namespace Basico\Entity\Repository;

use Basico\Entity\Repository\AbstractEntityRepository;
use Jlib\View\Html\Tabela\Tabela;

class ClientePautaRepository extends AbstractEntityRepository
{



    public function findCliente($id){

        $sql = "SELECT a.CodClientePauta,a.CodCliente, b.Materia,a.TipoProcesso,a.Origem, c.Pauta,d.Nome, a.TipoAto FROM Cliente_Pauta a
                left join Advocacia_Materia b on a.CodAdvocaciaMateria = b.Cod_Advocacia_Materia
                inner join Advocacia_Pauta c on c.Cod_Advocacia_Pauta = a.CodPauta
                left join vw_advocacia_envolvidos d on d.Cod = a.CodEnvolvido and a.Origem = d.Origem
                  where a.CodCliente  = {$id} ";

        $stmt = $this->_em->getConnection()->query($sql);
        $resultados = $stmt->fetchAll();

        $arrayRetorno = array();

        foreach ($resultados as $resultado){
            if ($resultado['TipoProcesso'] == 'P'){
                $resultado['TipoProcesso'] = 'Procon';
            }
            else if ($resultado['TipoProcesso'] == 'J'){
                $resultado['TipoProcesso'] = 'Judicial';
            }
            else if ($resultado['TipoProcesso'] == 'EJ'){
                $resultado['TipoProcesso'] = 'Extra Judicial';
            }
            if ($resultado['TipoAto'] == 'A'){
                $resultado['TipoAto'] = 'Audiência';
            } else if ($resultado['TipoAto'] == 'D'){
                $resultado['TipoAto'] = 'Diligência';
            }

            $arrayRetorno[] = $resultado;
        }

        return $this->dataToHtmlTableC($arrayRetorno);

    }


    /**
     * @see Basico\Entity\Repository.AbstractEntityRepository::dataToHtmlTable()
     */
    public function dataToHtmlTableC ($data) {
        //Alterado em 08/06/2018 por Diego Wojcik #18451
        $tab = new Tabela($data);
        $tab->addCampo('CodClientePauta', 'Código');
        $tab->addCampo('Pauta', 'Pauta');
        $tab->addCampo('TipoAto', 'Tipo de Ato');
        $tab->addCampo('TipoProcesso', 'Tipo de processo');
        $tab->addCampo('Materia', 'Materia');
        $tab->addCampo('Nome', 'Representado');
        $tab->setHtmlId('TabelaClientePauta');
        $tab->setAllowEdit(true);
        $tab->setEditLink('/basico/cliente-pauta/get-cliente-pauta');
        $tab->addEditParam('CodClientePauta', false);
        $tab->setEditHrefClass('btnEditClientePauta');
        $tab->setAllowDelete(true);
        $tab->setDeleteLink('/basico/cliente-pauta/delete-pauta');
        $tab->setDeleteHrefClass('btnExcluir');
        $tab->addDeleteParam('CodClientePauta', false);
        $tab->addDeleteParam('CodCliente', false);
        return $tab;
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

?>