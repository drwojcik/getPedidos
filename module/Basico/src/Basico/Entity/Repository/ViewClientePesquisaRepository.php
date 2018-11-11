<?php

/**
 * Classe responsável por conter os métodos
 * de consulta no banco
 * 
 * @author Gabriel Henrique <gabrielh@softwar.com.br>
 * @since 2016-11-22
 */
namespace Basico\Entity\Repository;

use Jlib\Util\FormatValue;
use Jlib\View\Html\Tabela\Tabela;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\DBAL\Types\DateTimeType;

class ViewClientePesquisaRepository extends AbstractEntityRepository
{
 
    public function findCliente(array $whereParams = null, array $orderBy = null, $limit = null){
    	if($whereParams['DataNascimento']){
    		
    		$dataNasc  = get_object_vars($whereParams['DataNascimento']);
    		$dataAux   = explode('00:00:00.000000', $dataNasc['date']);	
    		$dataNiver = explode('-', $dataAux['0']);
    		$diaNiver  = $dataNiver[2];
    		$mesNiver  = $dataNiver[1];
    		
    	}
    	//Alterado em 01/12/2016 - Diego Wojcik - #13466 Adicionado filtro por Nome like e Colocado limite de resultado em 500
    	//Alterado em 26/04/2017 - Gabriel Henrique - #14313 Adicionado filtro para Cleintes com e sem CPF
    	//Refeito o código, pois dava muita manutenção
 	 		  	$qb =$this->_em->createQueryBuilder();
    			$qb->select('e')
    			->from('Basico\Entity\ViewClientePesquisa', 'e')
    			->where('1=1');
    			if($whereParams['DataNascimento']){
    				$qb->where('e.Mes = ?1 AND e.Dia = ?2')
    				->setParameter(1, $mesNiver)
    				->setParameter(2, $diaNiver);
    			}
    			if($whereParams['CGC']){
    				$qb->andwhere('e.CGC = ?3')
    				->setParameter(3, $whereParams['CGC']);
    			}
    			if($whereParams['NomeFantasia']){
    				$qb->andwhere('e.NomeFantasia LIKE ?4')
    				->setParameter(4, '%'.$whereParams['NomeFantasia'].'%');
    			}
    			if($whereParams['Representada']){
    				$qb->andwhere('e.Representada = ?5')
    				->setParameter(5, $whereParams['Representada']);
    			}
    			if($whereParams['CodSegmento']){
    				$qb->andwhere('e.CodSegmento = ?6')
    				->setParameter(6, $whereParams['CodSegmento']);
    			}
    			if($whereParams['Estado']){
    				$qb->andwhere('e.Estado = ?7')
    				->setParameter(7, $whereParams['Estado']);
    			}
    			if($whereParams['Ativo']){
    				$qb->andwhere('e.Ativo = ?8')
    				->setParameter(8, $whereParams['Ativo']);
    			}
    			if($whereParams['ComCPF']){
    				if($whereParams['ComCPF'] == 'S'){
    					$qb->andwhere('e.CGC IS NOT NULL');
    					$qb->andwhere("e.CGC != '11111111111111'");
    					$qb->andwhere("e.CGC != '11111111111'");
    				} else if ($whereParams['ComCPF'] == 'N'){
    					$qb->andwhere("e.CGC IS NULL OR e.CGC = '11111111111111' OR e.CGC = '11111111111'");
    				}
    			}
    			//Alterado em 01/12/2016 - Diego Wojcik - #13466
                //ALterado em 07/05/2018 - Diego Wojcik #18035 - Aumentando limite de pesquisa
    			$qb->setMaxResults($limit);
		  		//seta o order by
				if(!empty($orderBy)){
					foreach ($orderBy as $order => $value){
						$qb->addOrderBy('e.'.$order,$value);
					}
				}
    			#$sql = $qb->getQuery()->getSQL();
    			$entities = $qb->getQuery()->getResult();

    	
    	
    	$format = new FormatValue();
    	
    	//$dados = array();
    	if($entities){
    		foreach($entities as $entity){
    			
    			$nome = $entity->getNomeFantasia();
    			$razao = $entity->getRazaoSocial();
    			$tipopessoa= $entity->getTipoPessoa();
    			$cgc = $entity->getCGC();
    			$ativo = $entity->getAtivo();
    			$segmento = $entity->getCodSegmento();
    			$estado = $entity->getEstado();
    			$cidade = $entity->getCidade();
    			
    			//Busca o nome do registro de Segmento através do código
    			$qb =$this->_em->createQueryBuilder();
    			$qb->select('e.Segmento')
    			->from('Cadastro\Entity\Segmento', 'e')
    			->where('e.CodSegmento = ?1')
    			->setParameter(1, $segmento);
    			 
    			 
    			$entitySeg = $qb->getQuery()->getResult();
				$nomesegmento = $entitySeg[0]['Segmento'];
    			
    			if($tipopessoa == 'J'){
    				if($cgc){
    					$cgc = $format->mask($cgc, '##.###.###/####-##');
    				}
    			}
    			else if($tipopessoa == 'F'){
    				if($cgc){
    					$cgc = $format->mask($cgc, '###.###.###-##');
    				}
    			}
    			
    			if($ativo == 'S'){
    				$ativo = 'Sim';
    			}
    			else {
    				$ativo = 'Não';
    			}
    			
    			$dados[] = array('CodCliente' => $entity->getCodCliente(),
    							'NomeFantasia'=> $nome,
    							'RazaoSocial' => $razao,
    							'CGC'         => $cgc,
    							'Ativo'       => $ativo,
    							'TipoPessoa'  => $tipopessoa,
    							'Segmento'    => $nomesegmento,
    							'Estado'	  => $estado,
    							'Cidade'      => $cidade,
    					);
    			
    			
    		}
    	}
    	else{
    		$dados = array();
    	}
    	 
    	return $this->dataToHtmlTable($dados);
    }
    
    /**
     * @see Basico\Entity\Repository.AbstractEntityRepository::dataToHtmlTable()
     */
    public function dataToHtmlTable ($data)
    {
    	$tab = new Tabela($data);
    	$tab->addCampo('CodCliente', 'Código');
    	$tab->addCampo('NomeFantasia', 'Nome');
    	$tab->addCampo('RazaoSocial', 'Razão Social');
    	$tab->addCampo('CGC', 'CPF/CNPJ');
    	$tab->addCampo('Estado', 'UF');
    	$tab->addCampo('Cidade', 'Cidade');
    	$tab->addCampo('Segmento', 'Segmento');
    	$tab->addCampo('Ativo', 'Ativo');
    	$tab->setHtmlId('cliente-list');
    	$tab->setAllowEdit(true);
    	$tab->setEditLink('/basico/cliente/edit');
    	$tab->addEditParam('CodCliente', false);
    	$tab->setAllowDelete(true);
    	$tab->setDeleteLink('/basico/cliente/delete');
    	$tab->setDeleteHrefClass('btnExcluir');
    	$tab->addDeleteParam('CodCliente', false);
    	
    	return $tab;
    }
    
    /**
     * @author Gabriel Henrique <gabrielh@softwar.com.br>
     * @since 30/06/2017
     * Função utilizada para encontrar o cliente a partir do filtro de busca específico
     */
    public function findClienteFiltro($whereParams){
    	
    	//Cria o query builder
    	$qb = $this->_em->createQueryBuilder();
    	$qb->select('e.CodCliente, e.NomeFantasia, e.CGC')
        ->from('Basico\Entity\ViewClientePesquisa', 'e')
        ->where("1=1");
        				  
        //Codigo Vendedor
        if(isset($whereParams['CGC'])){
        	$qb->andWhere('e.CGC = :CGC')
        	->setParameter('CGC',$whereParams['CGC']);
        }
        				  
        //Codigo Cliente
        if(isset($whereParams['NomeFantasia'])){
        	$qb->andWhere('e.NomeFantasia LIKE :NomeFantasia')
        	->setParameter('NomeFantasia','%'.$whereParams['NomeFantasia'].'%');
        }
        				  
        //Codigo Pedido
        if(isset($whereParams['RazaoSocial'])){
        	$qb->andWhere('e.RazaoSocial LIKE :RazaoSocial')
           	->setParameter('RazaoSocial','%'.$whereParams['RazaoSocial'].'%');
        }
        
        $qb->setMaxResults(5);
        $qb->addOrderBy('e.NomeFantasia','ASC');
    
        $dados = array();
        $dados = $qb->getQuery()->getResult();
    
        return $dados;
    }
}

?>