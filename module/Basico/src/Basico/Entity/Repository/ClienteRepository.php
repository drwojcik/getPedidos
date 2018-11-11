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

class ClienteRepository extends AbstractEntityRepository 
{

    /**
     * Retorna um array com os registros da tabela
     *
     * @return array
     * @example
     * 		array('1' => 'cliente 1', '2' => 'cliente 2')
     * Alterado por Gabriel Henrique em 02/05/2017 - Ordena alfabeticamente o array da lista de clientes
     */
    public function fetchPairs(array $whereParams = null, array $orderBy = null){
        $entities = $this->findBy($whereParams, $orderBy);
        
        $arrayRetorno = array();
        foreach($entities as $entity){
            $entNome = get_class($entity);
            if ($entNome == 'Basico\Entity\PessoaFisica'){
                $nome = $entity->getNome();
            } else {
                $nome = $entity->getNomeFantasia();
            }

            $arrayRetorno[$entity->getCodCliente()] = $nome;
        }
        natcasesort($arrayRetorno);
        return $arrayRetorno;
    }
    
    public function findClienteComboLike($valor, $ativo = 'S'){
    	if ($ativo != 'S' && $ativo != 'N'){
    		$ativo = 'S';
    	}
    
//     	$qb = $this->_em->createQueryBuilder();
//     	$qb->select('m')
//     	->from($this->getEntityName(), 'm')
//     	->where('m.Ativo = :ativo')
//     	->andWhere('m.NomeFantasia LIKE :material')
//     	->setParameter('ativo', $ativo)
//     	->setParameter(':material', '%'.$valor.'%')
//     	->setMaxResults(20);
    	
    	//$result = $qb->getQuery()->getResult();
    	$sql = "SELECT top 20 CodCliente, Nome_Fantasia, Contato from Cliente WHERE Ativo = 'S'
		AND (Representada = 'N' OR Representada is null)
    	AND Nome_Fantasia like '%{$valor}%'";
    	$stmt = $this->_em->getConnection()->query($sql);
    	$result = $stmt->fetchAll();
    	
    	
    	return $result;
    }
    public function findClienteSindicoComboLike($valor, $ativo = 'S'){
        if ($ativo != 'S' && $ativo != 'N'){
            $ativo = 'S';
        }

//     	$qb = $this->_em->createQueryBuilder();
//     	$qb->select('m')
//     	->from($this->getEntityName(), 'm')
//     	->where('m.Ativo = :ativo')
//     	->andWhere('m.NomeFantasia LIKE :material')
//     	->setParameter('ativo', $ativo)
//     	->setParameter(':material', '%'.$valor.'%')
//     	->setMaxResults(20);

        //$result = $qb->getQuery()->getResult();
        $sql = "SELECT top 20 CodCliente, Nome_Fantasia, Contato from Cliente WHERE Ativo = 'S'
		AND (Representada = 'N' OR Representada is null) AND Sindico = 'S'
    	AND Nome_Fantasia like '%{$valor}%'";
        $stmt = $this->_em->getConnection()->query($sql);
        $result = $stmt->fetchAll();


        return $result;
    }
    public function findClienteGeralComboLike($valor, $ativo = 'S'){
    	if ($ativo != 'S' && $ativo != 'N'){
    		$ativo = 'S';
    	}
    
//     	$qb = $this->_em->createQueryBuilder();
//     	$qb->select('m')
//     	->from($this->getEntityName(), 'm')
//     	->where('m.Ativo = :ativo')
//     	->andWhere('m.NomeFantasia LIKE :material')
//     	->setParameter('ativo', $ativo)
//     	->setParameter(':material', '%'.$valor.'%')
//     	->setMaxResults(20);
    	
    	//$result = $qb->getQuery()->getResult();
    	$sql = "SELECT top 20 CodCliente, Nome_Fantasia, Contato from Cliente WHERE Ativo = 'S'
    	AND Nome_Fantasia like '%{$valor}%'";
    	$stmt = $this->_em->getConnection()->query($sql);
    	$result = $stmt->fetchAll();
    	
    	
    	return $result;
    }
    
    /**
     * @author Gabriel Henrique <gabrielh@softwar.com.br> - #13597
     * @since 2016-12-23
     * @param unknown $valor
     * @param string $ativo
     * Método utilizado para encontrar os clientes que estejam marcados como representáveis
     */
    public function findRepresentadaComboLike($valor, $representada = 'S'){
    	if ($representada != 'S' && $representada != 'N'){
    		$representada = 'S';
    	}
    	
    	$sql = "SELECT top 20 CodCliente, Nome_Fantasia from Cliente WHERE Representada = 'S' AND Nome_Fantasia like '%{$valor}%'";
    	$stmt = $this->_em->getConnection()->query($sql);
    	$result = $stmt->fetchAll();
    

    	return $result;
    
    }
    
    public function findlLista(array $whereParams = null, array $orderBy = null){
    	//se o whre for null 
//     	if($whereParams != null){
//     		//se é pesoa fisca ou juridica
//     		if ($whereParams == 'F'){
//     			$entities = $this->findBy($whereParams, $orderBy);
//     			$format = new FormatValue();
//     			if($entities){
//     				foreach($entities as $entity){
//     					//$entNome = get_class($entity);
//     						$nome = $entity->getNome();
//     						$razao = '';
//     						$cgc = $entity->getCPF();
//     						if($cgc){
//     							$cgc = $format->mask($cgc, '###.###.###-##');
//     						}
    			
//     						$ativo = $entity->getAtivo();
//     						$falecido = $entity->getFalecido();
//     					}
//     					if($ativo == 'S'){
//     						$ativo = 'Sim';
//     					}else{
//     						$ativo = 'Não';
//     					}
//     					if($falecido == 'S'){
//     						$falecido = 'Sim';
//     					}else{
//     						$falecido = 'Não';
//     					}
//     					$arrayRetorno[] = array('Codigo' => $entity->getCodCliente(),
//     							'Nome' => $nome,
//     							'Razao' => $razao,
//     							'Cgc' => $cgc,
//     							'Ativo' => $ativo,
//     							'Falecido' => $falecido,
//     					);
//     				}
//     				else{
//     					$arrayRetorno = array();
//     				}
//     			}
//     			else if($whereParams == 'J'){
//     				$entities = $this->findBy($whereParams, $orderBy);
//     				$format = new FormatValue();
//     				if($entities){
//     					foreach($entities as $entity){
//     						//$entNome = get_class($entity);
//     						$nome = $entity->getNomeFantasia();
//     						$razao = $entity->getRazaoSocial();
//     						$cgc = $entity->getCNPJ();
//     						if($cgc){
//     							$cgc = $format->mask($cgc, '##.###.###/####-##');
//     						}
    						
//     						$ativo = $entity->getAtivo();
//     						$falecido = $entity->getFalecido();
//     					}
//     					if($ativo == 'S'){
//     						$ativo = 'Sim';
//     					}else{
//     						$ativo = 'Não';
//     					}
//     					if($falecido == 'S'){
//     						$falecido = 'Sim';
//     					}else{
//     						$falecido = 'Não';
//     					}
//     					$arrayRetorno[] = array('Codigo' => $entity->getCodCliente(),
//     							'Nome' => $nome,
//     							'Razao' => $razao,
//     							'Cgc' => $cgc,
//     							'Ativo' => $ativo,
//     							'Falecido' => $falecido,
//     					);
//     				}
//     				else{
//     					$arrayRetorno = array();
//     				}
//     			//find by
//     			}
//     		}
    		
//     		else{
    			$entities = $this->findBy($whereParams, $orderBy, 500);
    			$format = new FormatValue();
    			if($entities){
    				foreach($entities as $entity){
    					$entNome = get_class($entity);
    					if ($entNome == 'Basico\Entity\PessoaFisica'){
    						$nome = $entity->getNome();
    						$razao = '';
    						$cgc = $entity->getCPF();
    						if($cgc){
    							$cgc = $format->mask($cgc, '###.###.###-##');
    						}
    					 
    						$ativo = $entity->getAtivo();
    						$falecido = $entity->getFalecido();
    					} else {
    						$nome = $entity->getNomeFantasia();
    						$razao = $entity->getRazaoSocial();
    						$cgc = $entity->getCNPJ();
    						if($cgc){
    							$cgc = $format->mask($cgc, '##.###.###/####-##');
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
    			}else{
    				$arrayRetorno = array();
 			   	}
//     		}
    	return $this->dataToHtmlTable($arrayRetorno);
    	//return $arrayRetorno;
    }

    public function findCliente($Data, $CPF){
    	$format = new FormatValue();
    	$CPF = $format->mask($CPF, '###.###.###-##');
    	$qb = $this->getEntityManager()->createQueryBuilder();
    	$qb->select('e')
    	->from('Basico\Entity\Cliente', 'e')
    	->where('e.DataNascimento = ?1 or e.DataNascimento = ?1 AND e.RCPF = ?2')
    	->setParameter(1, $Data)
    	->setParameter(2, $CPF);
    	
    	$entities = $qb->getQuery()->getResult();
    	$dados = array();
		$dados = $qb->getQuery()->getResult();
		foreach ($entities as $entity){
			$dados[] = $entity->toTable();
		}
		return $this->dataToHtmlTable($dados);
    	
    	
    }
    
    //cmb_Matriz, "Cliente", "Nome_Fantasia", 2, "codCliente", "CodMatriz is null"
    //ALterado em 06/12/2016 - Diego Wojcik - Adicionado parâmetro para confirmar a exclusão do cliente.
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
		$tab->setDeleteHrefClass('btnExcluir');
		$tab->addDeleteParam('Codigo', false);
		
		return $tab;    
    }
    
    //Alterado em 19/01/2017 - Gabriel Henrique #13914 - Adicionado método para encontrar a Qtd de clientes ativos
    public function findQtdClientesAtivos(){
    	
    	$sql = "SELECT COUNT(CodCliente) AS Qtd FROM Cliente WHERE Ativo = 'S' AND (Representada != 'S' OR Representada IS NULL)";
    	$stmt = $this->_em->getConnection()->query($sql);
    	$result = $stmt->fetchAll();
    	$retorno = $result[0]['Qtd'];
    	
    	return $retorno;
    }
    
    public function findQtdRepresentadasAtivas(){
    	 
    	$sql = "SELECT COUNT(CodCliente) AS Qtd FROM Cliente WHERE Ativo = 'S' AND Representada = 'S' ";
    	$stmt = $this->_em->getConnection()->query($sql);
    	$result = $stmt->fetchAll();
    	$retorno = $result[0]['Qtd'];
    	 
    	return $retorno;
    }
    
    /**
     * @author Gabriel Henrique <gabrielh@softwar.com.br> - #13597
     * @since 2017-04-03
     * @param unknown $valor
     * @param string $ativo
     * Método utilizado para encontrar os todos os clientes para a combo do lançamento express do financeiro
     */
    public function findClienteAtivoLike($valor, $ativo = 'S'){
    	if ($ativo != 'S' && $ativo != 'N'){
    		$ativo = 'S';
    	}
    
    	$sql = "SELECT top 20 CodCliente, Nome_Fantasia from Cliente WHERE Ativo = 'S' AND Nome_Fantasia like '%{$valor}%'";
    	$stmt = $this->_em->getConnection()->query($sql);
    	$result = $stmt->fetchAll();
    	     	 
    	return $result;
    }
    
}

?>