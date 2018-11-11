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

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\DBAL\Types\DateTimeType;

class PessoaJuridicaRepository extends AbstractEntityRepository
{

    /**
     * Retorna um array com os empreiteiros ativos
     * @return array
     */
    public function fetchPairsEmpreiteirosAtivos(){
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('p.CodCliente, p.NomeFantasia')
	        ->from($this->_entityName,'p')
	        ->where('p.Ativo = ?1')
	        ->andWhere('p.Empreiteiro = ?2')
	        ->setParameter(1, 'S')
	        ->setParameter(2, 1)
        	->addOrderBy('p.Nome', 'ASC');
        $entities = $qb->getQuery()->getResult();
         
        $arrayRetorno = array();
        foreach($entities as $entity){
            $arrayRetorno[$entity->getCodCliente()] = $entity->getNomeFantasia();
            $arrayRetorno[$entity['CodCliente']] = $entity['NomeFantasia'];
        }
        return $arrayRetorno;
    }
    
    /**
     * Busca os clientes (pessoa juridica) de acordo com o nome pesquisado.
     *
     * @param string $nome
     */
    public function findByNomeFantasiaLike($nome){
        $qb = $this->_em->createQueryBuilder();
        $qb->select('pj')
	        ->from($this->getEntityName(), 'pj')
	        ->where('pj.NomeFantasia LIKE :nome')
	        ->setParameter(':nome', '%'.$nome.'%')
	        ->setMaxResults(20);
        $result = $qb->getQuery()->getResult();
        return $result;
    }
    
    public function findListaJuridica(array $whereParams = null, array $orderBy = null){
    	
    	$dataNasc  = get_object_vars($whereParams['DataNascimento']);
    	$dataAux   = explode('00:00:00.000000', $dataNasc['date']);	
    	$dataNiver = explode('-', $dataAux['0']);
    	$diaNiver  = $dataNiver[2];
    	$mesNiver  = $dataNiver[1];
    	
//     	$qb = $this->getEntityManager()->createQueryBuilder();
//     	$qb->select('e')
//     	->from('Basico\Entity\Cliente', 'e')
//     	->select('DATEPART(mm,Data_Nascimento) AS OrderMonth, DATEPART(dd,Data_Nascimento) AS OrderDay')
//     	->where('e.OrderDay = ?1 AND e.OrderMonth = ?2')
//     	->setParameter(1, $diaNiver)
//     	->setParameter(2, $mesNiver);

//     	$entities = $qb->getQuery()->getResult();

    	$sql = ("SELECT DATEPART(mm,Data_Nascimento) AS OrderMonth, DATEPART(dd,Data_Nascimento) AS OrderDay
    			 WHERE OrderDay = {$diaNiver} AND OrderMonth = {$mesNiver}" );
    	
    	$stmt = $this->_em->getConnection()->query($sql);
    	$result = $stmt->fetchAll();
    	

    	$format = new FormatValue();
    	
    	$arrayRetorno = array();
    	if($result){
    		foreach($result as $entity){
    			
    			$arrayRetorno[$entity['Codigo']] = $entity['Codigo'];
    			$arrayRetorno[$entity['NomeFantasia']] = $entity['NomeFantasia'];
    			$arrayRetorno[$entity['Razao']] = $entity['Razao'];
    			
    			if($entity['Cgc']){
    				$entity['Cgc'] = $format->mask($entity['Cgc'], '##.###.###/####-##');
    			}
    			
    			$arrayRetorno[$entity['Cgc']] = $entity['Cgc'];
    			$arrayRetorno[$entity['Ativo']] = $entity['Ativo'];
    		}
    	}
    	else{
    		$arrayRetorno = array();
    	}
//     	$entities = $this->findBy($whereParams, $orderBy);
//     	$format = new FormatValue();
//     	if($entities){
//     		foreach($entities as $entity){
//     			//$entNome = get_class($entity);
//     			$nome = $entity->getNomeFantasia();
//     			$razao = $entity->getRazaoSocial();
//     			$cgc = $entity->getCNPJ();
//     			if($cgc){
//     				$cgc = $format->mask($cgc, '##.###.###/####-##');
//     			}
    	
//     			$ativo = $entity->getAtivo();
//     			$falecido = $entity->getFalecido();
//     		}
//     		if($ativo == 'S'){
//     			$ativo = 'Sim';
//     		}else{
//     			$ativo = 'Não';
//     		}
//     		if($falecido == 'S'){
//     			$falecido = 'Sim';
//     		}else{
//     			$falecido = 'Não';
//     		}
//     		$arrayRetorno[] = array('Codigo' => $entity->getCodCliente(),
//     				'Nome' => $nome,
//     				'Razao' => $razao, 
//     				'Cgc' => $cgc,
//     				'Ativo' => $ativo,
//     				'Falecido' => $falecido,
//     		);
//     	}
//     	else{
//     		$arrayRetorno = array();
//     	}
    	 
    	return $this->dataToHtmlTable($arrayRetorno);
    }
    
    public function findByCNPJAndPassword($login, $password){
        $format = new FormatValue();
        $login = $format->cleanField($login);
        $cliente = $this->findOneBy(array('CNPJ' => $login));
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
    
    /**
     * @see Basico\Entity\Repository.AbstractEntityRepository::dataToHtmlTable()
     */
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
}

?>