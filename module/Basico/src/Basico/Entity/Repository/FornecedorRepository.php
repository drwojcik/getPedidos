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

class FornecedorRepository extends AbstractEntityRepository 
{

    /**
     * Retorna um array com os registros da tabela.
     * @return array
     * Alterado para aumentar o desempenho das consultas nas combos
     * @author Diego Wojcik <diego@softwar.com.br>
     * @issue #8675
     * @since 2015-10-09
     */
    public function fetchPairs(array $whereParams = null, array $orderBy = null){
        /*$entities = $this->findBy($whereParams, $orderBy);        
        $arrayRetorno = array();
        foreach($entities as $entity){
            $arrayRetorno[$entity->getCodFornecedor()] = $entity->getNomeFantasia();
        }
        return $arrayRetorno;*/
    	$qb = $this->getEntityManager()->createQueryBuilder();
    	$qb->select('p.CodFornecedor, p.NomeFantasia')
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
    		$arrayRetorno[$entity['CodFornecedor']] = $entity['NomeFantasia'];
    	}
    	return $arrayRetorno;
    }
    
    /**
     * Retorna um array com os registros da tabela. - Traz apenas os Fornecedores Advogados que possuem valor cadastrados para
     * aquela UF e Comarca
     * WHERE a.CodigoUf AND a.Cod_Advocacia_Comarca = 
     * @return array
     * @author Diego Wojcik <diego@softwar.com.br>
     * @since 2016-03-10
     */
    public function fetchPairsAdvogadoDisponivel(array $whereParams = null, array $orderBy = null){

    	$qb = $this->getEntityManager()->createQueryBuilder();
    	$qb->select('p.CodFornecedor, p.NomeFantasia, a.Ordem')
    	->from($this->_entityName,'p')
    	->innerJoin('Advocacia\Entity\AdvogadoValor', 'a', 'WITH', 'a.CodFornecedor = p.CodFornecedor')
		->where("p.Ativo = 'S'");
    	$paramCount = 0;
    	if(!empty($whereParams)){
    		foreach ($whereParams as $campo => $valor){
    			$paramCount++;
    			$qb->andWhere("a.{$campo} = ?{$paramCount}");
    			$qb->setParameter($paramCount, $valor);
    		}
    	}
    	$qb->addOrderBy('a.Ordem','ASC');
    	//Alterado em 03/04/2018 - Diego Wojcik -#18014 - Adicionado o order by Local do ato. se o clinet reclamar, terá q fitlrar local do ato is null
    	$qb->addOrderBy('a.CodAdvocaciaLocalAto','ASC');
    	//seta o order by
    	if(!empty($orderBy)){
    		foreach ($orderBy as $order => $value){
    			//$qb->addOrderBy('p.'.$order,$value);
    			$qb->addOrderBy('p.'.$order,$value);
    		}
    	}
    	//$sql = $qb->getQuery()->getSQL();
    	$entities = $qb->getQuery()->getResult();
    	
    	$arrayRetorno = array();
    	foreach($entities as $entity){
    		$arrayRetorno[$entity['CodFornecedor']] = $entity['Ordem'].' - '.$entity['NomeFantasia'];
    	}
    	return $arrayRetorno;
    }
    
    /**
     * Retorna um array com as contas a pagar ativas
     * @return array
     */
    public function fetchPairsEmpreiteirosAtivos(){
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('f.CodFornecedor, f.NomeFantasia')
	        ->from($this->_entityName,'f')
	        ->where('f.Ativo = ?1')
	        ->andWhere('f.Empreiteiro = ?2')
	        ->setParameter(1, 'S')
	        ->setParameter(2, 1)
	        ->addOrderBy('f.NomeFantasia', 'ASC');        
        $entities = $qb->getQuery()->getResult();
         
        $arrayRetorno = array();
        foreach($entities as $entity){
           $arrayRetorno[$entity['CodFornecedor']] = $entity['NomeFantasia'];
        }
        return $arrayRetorno;
    }
    
    
    public function findByLoginAndPassword($login, $password){
        $format = new FormatValue();
        $login = $format->cleanField($login);
        $fornecedor = $this->findOneBy(array('Login' => $login));
        if ($fornecedor){
            if ($fornecedor->getSenha() == $password){
                return $fornecedor;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
	 /**
	  *  Encontra o fornecedor, filtrando por material
	  * @param int $material
	  * @return \Jlib\View\Html\Tabela\Tabela
	  * @author Diego Wojcik <diego@softwar.com.br>
	  * @since 2016-02-01
	  */
    public function findListaByMaterial($material){
    	$qb = $this->getEntityManager()->createQueryBuilder();
    	$qb->select('o.CodFornecedor, o.NomeFantasia, o.RazaoSocial, o.CGC, o.Ativo, o.Fone, o.Email, o.Contato, o.DtCadastro')
    	->from('Suprimento\Entity\FornecedorMaterial', 'cla');
    	if($material){
    			$qb = $qb->where('cla.Material = '.$material);
    		}
    	
    	$qb = $qb->innerJoin('cla.Fornecedor', 'o')
    	//->groupBy('o.CodFornecedor, o.Obra')
    	->orderBy('o.NomeFantasia', 'ASC');
    	
    	$dados = $qb->getQuery()->getResult();
    	//$sql = $qb->getQuery()->getSQL();
    	$data = array();
    	$format = new FormatValue();
    	foreach ($dados as $registro){

    		
    		if($registro['Ativo'] == 'S'){
    			$registro['Ativo'] = 'Sim';
    		}else{
    			$registro['Ativo'] = 'Não';
    		}
    		
    		if(!empty($registro['DtCadastro'])){
    			$registro['DtCadastro'] = $format->formatDateToView($registro['DtCadastro']);
    		}
    		
    		if(!empty($registro['Fone'])){
    			$registro['Fone'] = $format->mask($registro['Fone'], '(##) #########');
    		}
    		
    		
    		$data[] = $registro;
    	}
    	
    	$tab = new Tabela($data);
		$tab->addCampo('CodFornecedor', 'Código');
		$tab->addCampo('NomeFantasia', 'Fornecedor');
		$tab->addCampo('RazaoSocial', 'Razão Social');
		$tab->addCampo('CGC', 'CPF/CNPJ');
		$tab->addCampo('Ativo', 'Ativo');
		$tab->addCampo('Fone', 'Fone');
		$tab->addCampo('Email', 'Email');
		$tab->addCampo('Contato', 'Contato');
		$tab->addCampo('DtCadastro', 'Cadastro');
		$tab->setHtmlId('fonecedor-list');
		$tab->setAllowEdit(true);
		$tab->setEditLink('/basico/fornecedor/edit');
		$tab->addEditParam('CodFornecedor', false);
		$tab->setAllowDelete(true);
		$tab->setDeleteLink('/basico/fornecedor/delete');
		$tab->setDeleteHrefClass('btnExcluir');
		$tab->addDeleteParam('CodFornecedor', false);
    	return $tab;
    }
    
    public function findLista(array $whereParams = null, array $orderBy = null){
//	    	$entities = $this->findBy($whereParams, $orderBy);
	    	$format = new FormatValue();

        $qb = $this->_em->createQueryBuilder();
        $qb->select('c')
            ->from('Basico\Entity\Fornecedor', 'c')
            ->where("1 = 1");

        if (isset($whereParams['CodFornecedor'])){
            $qb->andWhere('c.CodFornecedor = :CodFornecedor')
               ->setParameter('CodFornecedor', $whereParams['CodFornecedor']);
        }
        //Alterado em 24/09/2018 por Diego Wojcij estava escrito errado cgc.
        if (isset($whereParams['CGC'])){
            $qb->andWhere('c.CGC = :CGC')
                ->setParameter('CGC', $whereParams['CGC']);
        }


        if($whereParams['NomeFantasia']){
            $qb->andwhere('c.NomeFantasia LIKE ?4')
                ->setParameter(4, '%'.$whereParams['NomeFantasia'].'%');
        }

        if($whereParams['RazaoSocial']){
            $qb->andwhere('c.RazaoSocial LIKE ?5')
                ->setParameter(5, '%'.$whereParams['RazaoSocial'].'%');
        }

        if (isset($whereParams['Cidade'])){
            $qb->andWhere('c.Cidade = :Cidade')
                ->setParameter('Cidade', $whereParams['Cidade']);
        }


        if (isset($whereParams['Ramo'])){
            $qb->andWhere('c.Ramo = :Ramo')
                ->setParameter('Ramo', $whereParams['Ramo']);
        }

//Tirado a pesquisa por obs campo nao era compativel com nvarchar, na tabela está como ntext #18871
//        if (isset($whereParams['Obs'])){
//            $qb->andWhere('c.Obs = :Obs')
//                ->setParameter('Obs', $whereParams['Obs']);
//        }

        if (isset($whereParams['Ativo'])){
            $qb->andWhere('c.Ativo = :Ativo')
                ->setParameter('Ativo', $whereParams['Ativo']);
        }




        $entities = $qb->getQuery()->getResult();


	    if(count($entities) > 0){
	    	foreach($entities as $entity){
	    		
	    		
	    		
	    		$ativo = $entity->getAtivo();
	    		if($ativo == 'S'){
	    			$ativo = 'Sim';
	    		}else{
	    			$ativo = 'Não';
	    		}
	    		$data = $entity-> getDtCadastro();
	    		if(!empty($data)){
	    		$data = $format->formatDateToView($data);
	    		}
	    		
	    		$fone = $entity->getFone();
	    		if(!empty($fone)){
	    			$fone = $format->mask($fone, '(##) #########');
	    		}
                //Alterado em 02/07/2018 por Diego Wojcik #18606 adicionado link na tabela
                $link = "/basico/fornecedor/edit/{$entity->getCodFornecedor()}";
                $dados['CodLink'] = "<a href='$link'>";
                $dados['CodLink'].=   $entity->getCodFornecedor();
                $dados['CodLink'].= "</a>";
                $dados['NomeLink'] = "<a href='$link'>";
                $dados['NomeLink'].=   $entity->getNomeFantasia();
                $dados['NomeLink'].= "</a>";

                $arrayRetorno[] = array('Codigo' =>  $entity->getCodFornecedor(),
	    				'NomeFantasia' => $dados['NomeLink'],
	    				'RazaoSocial' => $entity->getRazaoSocial(),
	    				'Cgc' => $entity->getCgc(),
	    				'Ativo' => $ativo,
	    				'Fone' => $fone,
	    				'Email' => $entity->getEmail(),
	    				'Contato' => $entity->getContato(),
	    				'DtCadastro' => $data,
	    				'Estado' => $entity->getEstado(),
	    				'Cidade' => $entity->getCidade(),
	    				'EnderecoCompleto' => $entity->getEndereco().' '.$entity->getNumeroEndereco().' '.$entity->getComplemento(),
                        'CodLink' => $dados['CodLink']
	    		);
	    	}
	    }else{
	    	$arrayRetorno = array();
	    }
	    	return $this->dataToHtmlTable($arrayRetorno);
	    	//return $arrayRetorno;
    }
    
    public function dataToHtmlTable ($data)
    {
        $tab = new Tabela($data);
		$tab->addCampo('CodLink', 'Código');
		$tab->addCampo('NomeFantasia', 'Fornecedor');
		$tab->addCampo('RazaoSocial', 'Razão Social');
		$tab->addCampo('Cgc', 'CPF/CNPJ');
		$tab->addCampo('Ativo', 'Ativo');
		$tab->addCampo('Fone', 'Fone');
		$tab->addCampo('Email', 'Email');
		$tab->addCampo('Contato', 'Contato');
		$tab->addCampo('DtCadastro', 'Cadastro');
		$tab->addCampo('Estado', 'UF');
		$tab->addCampo('Cidade', 'Cidade');
		$tab->addCampo('EnderecoCompleto', 'End.');
		$tab->setHtmlId('fonecedor-list');
		$tab->setAllowEdit(true);
		$tab->setEditLink('/basico/fornecedor/edit');
		$tab->addEditParam('Codigo', false);
		$tab->setAllowDelete(true);
		$tab->setDeleteLink('/basico/fornecedor/delete');
		$tab->setDeleteHrefClass('btnExcluir');
		$tab->addDeleteParam('Codigo', false);
		
		return $tab;  
    }    
    
    public function findListaAdv(array $whereParams = null, array $orderBy = null){
    	$entities = $this->findBy($whereParams, $orderBy);
    	if(count($entities) > 0){
    		$format = new FormatValue();
    		foreach($entities as $entity){
    		
    			$ativo = $entity->getAtivo();
    			if($ativo == 'S'){
    				$ativo = 'Sim';
    			}else{
    				$ativo = 'Não';
    			}
    			$data = $entity-> getDtCadastro();
    			if(!empty($data)){
    				$data = $format->formatDateToView($data);
    			}
    		
    			$fone = $entity->getFone();
    			if(!empty($fone)){
    				$fone = $format->mask($fone, '(##) #########');
    			}
    		
    			$arrayRetorno[] = array('Codigo' => $entity->getCodFornecedor(),
    					'NomeFantasia' => $entity->getNomeFantasia(),
    					'RazaoSocial' => $entity->getRazaoSocial(),
    					'Cgc' => $entity->getCgc(),
    					'Ativo' => $ativo,
    					'Fone' => $fone,
    					'Email' => $entity->getEmail(),
    					'Contato' => $entity->getContato(),
    					'DtCadastro' => $data,
    			);
    		}
    	}else{
    			$arrayRetorno = array();
    	}
    	
    	return $this->dataToHtmlTableAdv($arrayRetorno);
    	//return $arrayRetorno;
    }
    
    public function dataToHtmlTableAdv($data)
    {
        $tab = new Tabela($data);
		$tab->addCampo('Codigo', 'Código');
		$tab->addCampo('NomeFantasia', 'Fornecedor');
		$tab->addCampo('RazaoSocial', 'Razão Social');
		$tab->addCampo('Cgc', 'CPF/CNPJ');
		$tab->addCampo('Ativo', 'Ativo');
		$tab->addCampo('Fone', 'Fone');
		$tab->addCampo('Email', 'Email');
		$tab->addCampo('Contato', 'Contato');
		$tab->addCampo('DtCadastro', 'Cadastro');
		$tab->setHtmlId('fonecedor-list');
		$tab->setAllowEdit(true);
		$tab->setEditLink('/advocacia/advogado/edit');
		$tab->addEditParam('Codigo', false);
		$tab->setAllowDelete(true);
		$tab->setDeleteLink('/advocacia/advogado/delete');
		$tab->setDeleteHrefClass('btnExcluir');
		$tab->addDeleteParam('Codigo', false);
		
		return $tab;  
    }  
    
    /**
     * Retorna um array com os registros da tabela. - Traz apenas os Fornecedores Advogados que possuem valor cadastrados para
     * aquela UF e Comarca
     * WHERE a.CodigoUf AND a.Cod_Advocacia_Comarca =
     * @return array
     * @author Paulo Lavoratti <paulo@softwar.com.br>
     * @since 2016-10-25
     */
    public function findByFornecedorMobile(array $whereParams = null, array $orderBy = null){

    	$qb = $this->getEntityManager()->createQueryBuilder();
    	$qb->select('p.CodFornecedor, p.NomeFantasia')
    	->from($this->_entityName,'p')
    	->innerJoin('Advocacia\Entity\AdvogadoValor', 'a', 'WITH', 'a.CodFornecedor = p.CodFornecedor')
    	->where("1 = 1");
    	$paramCount = 0;
    	if(!empty($whereParams)){
    		foreach ($whereParams as $campo => $valor){
    			$paramCount++;
    			$qb->andWhere("a.{$campo} = ?{$paramCount}");
    			$qb->setParameter($paramCount, $valor);
    		}
    	}
    	$qb->addOrderBy('a.Ordem','ASC');
    	//seta o order by
    	if(!empty($orderBy)){
    		foreach ($orderBy as $order => $value){
    			//$qb->addOrderBy('p.'.$order,$value);
    			$qb->addOrderBy('p.'.$order,$value);
    		}
    	}
    	$sql = $qb->getQuery()->getSQL();
    	$entities = $qb->getQuery()->getResult();
    	
    	return $entities[0];
    }
    
    /**
     * @author Gabriel Henrique <gabrielh@softwar.com.br> - #13914
     * @since 2017-01-20
     * Método utilizado para encontrar a quantidade de fornecedores ativos
     */
    public function findQtdFornecedoresAtivos(){
    
    	$sql = "SELECT COUNT(CodFornecedor) AS Qtd FROM Fornecedor WHERE Ativo = 'S' ";
    	$stmt = $this->_em->getConnection()->query($sql);
    	$result = $stmt->fetchAll();
    	$retorno = $result[0]['Qtd'];
    
    	return $retorno;
    }
    
    /**
     * @author Gabriel Henrique <gabrielh@softwar.com.br>
     * @since 29/03/2017
     * Busca os fornecedores ativos de acordo com o nome pesquisado.
     *
     * @param string $valor
     * @param string $ativo
     */
    public function findFornecedorLike($valor, $ativo = 'S'){
    	if ($ativo != 'S' && $ativo != 'N'){
    		$ativo = 'S';
    	}
    
    	$qb = $this->_em->createQueryBuilder();
    	$qb->select('m')
    	->from($this->getEntityName(), 'm')
    	->where('m.Ativo = :ativo')
    	->andWhere('m.NomeFantasia LIKE :fornecedor')
    	->setParameter('ativo', $ativo)
    	->setParameter(':fornecedor', '%'.$valor.'%')
    	->setMaxResults(20);
    	$result = $qb->getQuery()->getResult();
    	return $result;
    }
    
    /**
     * Retorna um array com as transportadoras
     * @author Gabriel Henrique - 04/04/2017
     * @return array
     */
    public function fetchPairsTransportadoras(){
    	$qb = $this->getEntityManager()->createQueryBuilder();
    	$qb->select('f.CodFornecedor, f.NomeFantasia')
    	->from($this->_entityName,'f')
    	->where("f.Transportadora = 'S' ")
    	->addOrderBy('f.NomeFantasia', 'ASC');
    	$entities = $qb->getQuery()->getResult();
    	 
    	$arrayRetorno = array();
    	foreach($entities as $entity){
    		$arrayRetorno[$entity['CodFornecedor']] = $entity['NomeFantasia'];
    	}
    	return $arrayRetorno;
    }
}

?>