<?php

/**
 * Classe responsável por conter os métodos
 * de consulta no banco
 * 
 * @author Jonathan Fernando <jonathan@softwar.com.br>
 * @since 2014-09-02
 */
namespace Basico\Entity\Repository;

use Jlib\View\Html\Tabela\Tabela;

class GeraisEmailEnvioAutomaticoRepository extends AbstractEntityRepository
{
    /**
     * @see Basico\Entity\Repository.AbstractEntityRepository::dataToHtmlTable()
     */
	public function dataToHtmlTable ($data)
    {
        $tabela = new Tabela($data);
        $tabela->setHtmlId('tblEmailEnvioAutomatico');
        $tabela->addCampo('idEmailEnvioAutomatico', 'Cód.');
        $tabela->addCampo('TipoEmail', 'Tipo Email');
        $tabela->addCampo('Ativo', 'Ativo');
        $tabela->setAllowEdit(true);
        $tabela->setEditLink('/basico/email-envio-automatico/edit');
        $tabela->addEditParam('idEmailEnvioAutomatico');
        
        return $tabela;
    }
    
    /**
     * Valida se um TipoEmail é único.
     * Método utilizado antes do insert/update.
     * 
     * @param integer $id
     * @param string $TipoEmail
     */
    public function validaUniqueTipoEmail($id, $TipoEmail){
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('ge')
        	->from('Basico\Entity\GeraisEmailEnvioAutomatico', 'ge')
        	->where('ge.TipoEmail = ?1')        	
        	->setParameter(1, $TipoEmail);
        
        if (!empty($id)){
            $qb->andWhere('ge.idEmailEnvioAutomatico <> ?2')
                ->setParameter(2, $id);
        }
        
        #$sql = $qb->getQuery()->getSQL();
        #$parameters = $qb->getQuery()->getParameters()->toArray();
        
        $dados = $qb->getQuery()->getOneOrNullResult();
               
        return $dados;
    }
}

?>