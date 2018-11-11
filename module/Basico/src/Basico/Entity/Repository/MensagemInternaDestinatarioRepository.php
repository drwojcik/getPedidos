<?php

/**
 * Classe responsável por conter os métodos
 * de consulta no banco
 * 
 * @author Jonathan Fernando <jonathan@softwar.com.br>
 * @since 2014-09-11
 */
namespace Basico\Entity\Repository;


class MensagemInternaDestinatarioRepository extends AbstractEntityRepository
{    
    
    /**
     * @see Basico\Entity\Repository.AbstractEntityRepository::dataToHtmlTable()
     */
	public function dataToHtmlTable($data) {
		throw new \Exception('Método não implementado.');   
    }
      
}

?>