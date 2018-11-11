<?php

namespace Basico\Entity\Repository;

use Basico\Entity\Repository\AbstractEntityRepository;

class ViewMensagemInternaCaixaEntradaRepository extends AbstractEntityRepository {	
    
    /**
     * @see Basico\Entity\Repository.AbstractEntityRepository::dataToHtmlTable()
     */
    public function dataToHtmlTable ($data)
    {
        throw new \Exception('Método não implementado.');        
    }
}