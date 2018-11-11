<?php

/**
 * Classe responsável por conter os métodos
 * de consulta no banco
 * 
 * @author Jonathan Fernando <jonathan@softwar.com.br>
 * @since 2013-10-30
 */
namespace Basico\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Jlib\View\Html\Tabela\Tabela;
use Jlib\Util\FormatValue;
use Jlib\Util\Crypt\CryptRC4;

class FuncionarioRepository extends EntityRepository
{
/**
 * Consulta listagem de funcionarios da tela de funcionario
 * @author Diego Wojcik <diego@softwar.com.br>
 * @since 2016-05-11
 * @param array $whereParams
 * @param array $orderBy
 * @return \Jlib\View\Html\Tabela\Tabela
 */
public function findLista(array $whereParams = null, array $orderBy = null){
    	$qb = $this->getEntityManager()->createQueryBuilder();
		$qb->select('p')
		->from($this->_entityName,'p');
		$paramCount = 0;
		$qb->where("p.Nome is not null");
		$qb->andwhere("p.CodFuncionario <> 1");
		if(!empty($whereParams)){
			if($whereParams['Demissao'] == 'S'){
				$qb->andwhere("p.Demissao is not null");
				unset($whereParams['Demissao']);
				
			}else{
				$qb->andwhere("p.Demissao is null");
				unset($whereParams['Demissao']);
			}
			if(!empty($whereParams['Nome'])){
				$qb->andwhere("p.Nome like '%{$whereParams['Nome']}%'");
				unset($whereParams['Nome']);
				
			}
			foreach ($whereParams as $campo => $valor){
				$paramCount++;
				$qb->andWhere("p.{$campo} = ?{$paramCount}");
				$qb->setParameter($paramCount, $valor);
			}
		}
		//seta o order by
		if(!empty($orderBy)){
			foreach ($orderBy as $order => $value){
				$qb->addOrderBy('p.'.$order,$value);
			}
		}
		
		$entities = $qb->getQuery()->getResult();
		$arrayRetorno = array();
		
    	foreach($entities as $entity){
    		$dados = $entity->toTable();
    		$arrayRetorno[] = $dados;
    	}
    	
    	
    	return $this->dataToHtmlTable($arrayRetorno);
    	//return $arrayRetorno;
    }
    
    public function dataToHtmlTable($data)
    {
        $tab = new Tabela($data);
		$tab->addCampo('CodFuncionario', 'Código');
		$tab->addCampo('Nome', 'Nome');
		#$tab->addCampo('Login', 'Login');
		$tab->addCampo('Cargo', 'Cargo');
		$tab->addCampo('Especializacao', 'Especialização');
		$tab->addCampo('Equipe', 'Equipe');
		$tab->addCampo('Restricao', 'Restrição');
		$tab->setHtmlId('user-list');
		$tab->setAllowEdit(true);
		$tab->setEditLink('/basico/funcionario/edit');
		$tab->addEditParam('CodFuncionario', false);
		$tab->setAllowDelete(true);
		$tab->setDeleteLink('/basico/funcionario/delete');
		$tab->setDeleteHrefClass('btnExcluir');
		$tab->addDeleteParam('CodFuncionario', false);
		
		return $tab;  
    }    
	
/**
 * Consulta listagem de funcionarios da tela de controle de acesso
 * @author Diego Wojcik <diego@softwar.com.br
 * @since 2016-04-25
 * @param array $whereParams
 * @param array $orderBy
 * @return \Jlib\View\Html\Tabela\Tabela
 */
public function findListaUser(array $whereParams = null, array $orderBy = null){
    	$qb = $this->getEntityManager()->createQueryBuilder();
		$qb->select('p')
		->from($this->_entityName,'p');
		$paramCount = 0;
		$qb->where("p.Nome is not null");
		$qb->andwhere("p.CodFuncionario <> 1");
		$qb->andwhere("p.Demissao is null");
		if(!empty($whereParams)){
			foreach ($whereParams as $campo => $valor){
				$paramCount++;
				$qb->andWhere("p.{$campo} = ?{$paramCount}");
				$qb->setParameter($paramCount, $valor);
			}
		}
		//seta o order by
		if(!empty($orderBy)){
			foreach ($orderBy as $order => $value){
				$qb->addOrderBy('p.'.$order,$value);
			}
		}
		
		$entities = $qb->getQuery()->getResult();
		$arrayRetorno = array();
		
    	foreach($entities as $entity){
    		$dados = $entity->toTable();
    		$arrayRetorno[] = $dados;
    	}
    	
    	
    	return $this->dataToHtmlTableUser($arrayRetorno);
    	//return $arrayRetorno;
    }
    
    public function dataToHtmlTableUser($data)
    {
        $tab = new Tabela($data);
		$tab->addCampo('CodFuncionario', 'Código');
		$tab->addCampo('Nome', 'Nome');
		$tab->addCampo('Login', 'Login');
		//$tab->addCampo('Setor', 'Setor');
		$tab->addCampo('Email', 'Email');
		$tab->setHtmlId('user-list');
		$tab->setAllowEdit(true);
		$tab->setEditLink('/controle-acesso/acesso-usuario/edit');
		$tab->addEditParam('CodFuncionario', false);
// 		$tab->setAllowDelete(true);
// 		$tab->setDeleteLink('/advocacia/advogado/delete');
// 		$tab->setDeleteHrefClass('btnExcluir');
// 		$tab->addDeleteParam('Codigo', false);
		
		return $tab;  
    }    
	
	
	/**
	 * @author Diego Wojcik - Criada Combo Selecionando quem precisa, pois da maneira antiga estava 
	 * fazendo SELECT * FORM
	 * @since 2015-10-09 
	 * @param array $whereParams
	 * @param array $orderBy
	 * @return multitype:unknown
	 */
	public function getDadosCombo(array $whereParams = null, array $orderBy = null){
		$qb = $this->getEntityManager()->createQueryBuilder();
		$qb->select('p.CodFuncionario, p.Nome')
		->from($this->_entityName,'p');
		$paramCount = 0;
		if(!empty($whereParams)){
			foreach ($whereParams as $campo => $valor){
				$paramCount++;
				$qb->andWhere("p.{$campo} = ?{$paramCount}");
				$qb->setParameter($paramCount, $valor);
			}
		}
		//seta o order by
		if(!empty($orderBy)){
			foreach ($orderBy as $order => $value){
				$qb->addOrderBy('p.'.$order,$value);
			}
		}
		
		$entities = $qb->getQuery()->getResult();
		$arrayRetorno = array();
		foreach($entities as $entity){
			$arrayRetorno[$entity['CodFuncionario']] = $entity['Nome'];
		}
		return $arrayRetorno;
	}
	
    /**
     * Retorna um array com os registros da tabela
     *
     * @return array
     * @example
     * 		array(1 => 'funcionario x', 2 => 'funcionario y')
     */
    public function fetchPairs(array $whereParams = null, array $orderBy = null){
      /*  $entities = $this->findBy($whereParams, $orderBy);
         
        $arrayRetorno = array();
        foreach($entities as $entity){
            $arrayRetorno[$entity->getCodFuncionario()] = $entity->getNome();
        }
        return $arrayRetorno;*/
    	$qb = $this->getEntityManager()->createQueryBuilder();
    	$qb->select('p.CodFuncionario, p.Nome')
    	->from($this->_entityName,'p');
    	$paramCount = 0;
    	if(!empty($whereParams)){
    		foreach ($whereParams as $campo => $valor){
    			$paramCount++;
    			$qb->andWhere("p.{$campo} = ?{$paramCount}");
    			$qb->setParameter($paramCount, $valor);
    		}
    	}
    	//seta o order by
    	if(!empty($orderBy)){
    		foreach ($orderBy as $order => $value){
    			$qb->addOrderBy('p.'.$order,$value);
    		}
    	}
    	
    	$entities = $qb->getQuery()->getResult();
    	$arrayRetorno = array();
    	foreach($entities as $entity){
    		$arrayRetorno[$entity['CodFuncionario']] = $entity['Nome'];
    	}
    	return $arrayRetorno;
    }
    
    public function findByLoginAndPassword($login, $password){
        $funcionario = $this->findOneBy(array('Login' => $login));
        if ($funcionario){
            $encPassword = $funcionario->encryptPassword($password);
            
            if ($funcionario->getSenha() == $encPassword){
                return $funcionario;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    /**
     * 
     * @author Diego Wojcik <diego@softwar.com.br>
     * @since 27/07/2016
     * @param unknown $password
     * @return Ambigous <object, NULL>|boolean
     */
    public function findByPassword($password){
    	$cryptRC4 = new CryptRC4($password);
    	$encPassword =  $cryptRC4->encript();
        $funcionario = $this->findOneBy(array('Senha' => $encPassword));
        if ($funcionario){
        	return $funcionario;
        } else {
            return false;
        }
    }
    
    
    public function findAdvogadoComValor($where){
    	$Uf = $whereParams['UF'];
    	$ato = $whereParams['AtoTramitacao'];
    	$sql = "SELECT fornecedor.codFornecedor, fornecedor.Nome_Fantasia FROM fornecedor
			INNER JOIN Advocacia_Advogado_Valor ad ON ad.codfornecedor = fornecedor.codfornecedor
			WHERE Cod_Advocacia_Ato_Tramitacao = $ato
			AND UF = $Uf		
				GROUP BY fornecedor.codFornecedor, fornecedor.Nome_Fantasia";
    	
    	$stmt = $this->_em->getConnection()->query($sql);
    	$result = $stmt->fetchAll();
    	return $result;
    	
    }
    
    /**
     * @author Gabriel Henrique <gabrielh@softwar.com.br>
     * @since 30/03/2017
     * Busca os funcionarios ativos de acordo com o nome pesquisado.
     *
     * @param string $valor
     */
    public function findFuncionarioLike($valor){
    
    	$qb = $this->_em->createQueryBuilder();
    	$qb->select('m')
    	->from($this->getEntityName(), 'm')
    	->Where('m.Nome LIKE :funcionario')
    	->setParameter(':funcionario', '%'.$valor.'%')
    	->setMaxResults(20);
    	$result = $qb->getQuery()->getResult();
    	return $result;
    }
   
}

?>