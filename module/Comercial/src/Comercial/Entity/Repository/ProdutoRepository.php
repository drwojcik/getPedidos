<?php

/**
 * Classe responsável por conter os métodos
 * de consulta no banco
 * 
 * @author Diego Wojcik <diego@softwar.com.br>
 * @since 2016-12-29
 */
namespace Comercial\Entity\Repository;

use Jlib\Util\FormatValue;

use Jlib\View\Html\Tabela\Tabela;

use Basico\Entity\Repository\AbstractEntityRepository;

use Doctrine\ORM\Query\ResultSetMapping;

class ProdutoRepository extends AbstractEntityRepository
{

  
    /**
     * @see Basico\Entity\Repository.AbstractEntityRepository::dataToHtmlTable()
     */
	public function dataToHtmlTable($data)
    {
        throw new \Exception('Método não implementado!');        
    }
    public function fetchPairs(array $whereParams = null, array $orderBy = null){
        $entities = $this->findBy($whereParams, $orderBy);
        
        $arrayRetorno = array();
        foreach($entities as $entity){
            $arrayRetorno[$entity->getidProduto()] = $entity->getNome();
        }
        return $arrayRetorno;
    }
    
    
}

?>