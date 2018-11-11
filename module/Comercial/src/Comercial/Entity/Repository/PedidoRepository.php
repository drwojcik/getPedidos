<?php


namespace Comercial\Entity\Repository;

use Jlib\Util\FormatValue;

use Jlib\View\Html\Tabela\Tabela;

use Basico\Entity\Repository\AbstractEntityRepository;

use Doctrine\ORM\Query\ResultSetMapping;

class PedidoRepository extends AbstractEntityRepository
{

    
    /**
     * 
     * @param array $dados
     * @param array $nomeDosCampos
     */
    public function arrayToHtmlTable($dados, $nomeDosCampos = array()){
        $table = new Tabela($dados, $nomeDosCampos);
        $table = $table->render();
        return $table;
    }
    
    /**
     * @see Basico\Entity\Repository.AbstractEntityRepository::dataToHtmlTable()
     */
	public function dataToHtmlTable($data)
    {
        throw new \Exception('Método não implementado!');        
    }

    
    
}

?>