<?php

/**
 * Classe responsável por conter os métodos
 * de consulta no banco
 * 
 * @author Jonathan Fernando <jonathan@softwar.com.br>
 * @since 2013-12-20
 */
namespace Basico\Entity\Repository;

use Jlib\Util\FormatValue;
use Jlib\View\Html\Tabela\Tabela;

use Doctrine\ORM\EntityRepository;

class PessoaFisicaRepository extends AbstractEntityRepository
{

    public function dataToHtmlTable ($data)
    {
    	$tab = new Tabela($data);
    	$tab->addCampo('Codigo', 'Código');
    	$tab->addCampo('Nome', 'Nome');
    	$tab->addCampo('Razao', 'Razão Social');
    	$tab->addCampo('Cgc', 'CPF/CNPJ');
    	$tab->addCampo('Ativo', 'Ativo');
    	$tab->setHtmlId('cliente-list');
    	$tab->setAllowEdit(true);
    	$tab->setEditLink('/basico/cliente/edit');
    	$tab->addEditParam('Codigo', false);
    	$tab->setAllowDelete(true);
    	$tab->setDeleteLink('/basico/cliente/delete');
    	$tab->addDeleteParam('Codigo', false);
    	 
    	return $tab;
    }
    
    /**
     * Busca os clientes (pessoa fisica) de acordo com o nome pesquisado.
     *
     * @param string $nome
     */
    public function findByNomeLike($nome){    
        $qb = $this->_em->createQueryBuilder();
        $qb->select('pf')
	        ->from($this->getEntityName(), 'pf')
	        ->where('pf.Nome LIKE :nome')
	        ->setParameter(':nome', '%'.$nome.'%')
	        ->setMaxResults(20);
        $result = $qb->getQuery()->getResult();
        return $result;
    }
    
    public function findListaFisica(array $whereParams = null, array $orderBy = null){
    	$entities = $this->findBy($whereParams, $orderBy);
    	$format = new FormatValue();
    	if($entities){
    		foreach($entities as $entity){
    			//$entNome = get_class($entity);
    			$nome = $entity->getNome();
    			$razao = '';
    			$cgc = $entity->getCPF();
    			if($cgc){
    				$cgc = $format->mask($cgc, '###.###.###-##');
    			}
    			 
    			$ativo = $entity->getAtivo();
    			$falecido = $entity->getFalecido();
    		}
    		if($ativo == 'S'){
    			$ativo = 'Sim';
    		}else{
    			$ativo = 'Não';
    		}
    		if($falecido == 'S'){
    			$falecido = 'Sim';
    		}else{
    			$falecido = 'Não';
    		}
    		$arrayRetorno[] = array('Codigo' => $entity->getCodCliente(),
    				'Nome' => $nome,
    				'Razao' => $razao,
    				'Cgc' => $cgc,
    				'Ativo' => $ativo,
    				'Falecido' => $falecido,
    		);
    	}
    	else{
    		$arrayRetorno = array();
    	}
    	
    	return $this->dataToHtmlTable($arrayRetorno);
    	
    	
    }
    
    public function findByCPFAndPassword($login, $password){
        $format = new FormatValue();
        $login = $format->cleanField($login);
        $cliente = $this->findOneBy(array('CPF' => $login));
        if ($cliente){
            if ($cliente->getSenha() == $password){
                return $cliente;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    
}

?>