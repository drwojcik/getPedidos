<?php

/**
 * Classe responsável por conter os métodos
 * de consulta no banco
 * 
 * @author Jonathan Fernando <jonathan@softwar.com.br>
 * @since 2013-10-30
 */
namespace Basico\Entity\Repository;

class TipoAgendaRepository extends AbstractEntityRepository
{        
    
    /**
     * @see Basico\Entity\Repository.AbstractEntityRepository::fetchPairs()
     */
    public function fetchPairs (array $whereParams = null, array $orderBy = null)
    {
        $this->setFetchPairsId('CodTipoAgenda');
        $this->setFetchPairsDescription('TipoAgenda');
        return parent::fetchPairs($whereParams, $orderBy);
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