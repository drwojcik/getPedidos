<?php
/**
 * Repository da entidade 'ViewConsultarMateriais'
 * @author Gabriel Henrique <gabrielh@softwar.com.br>
 * @since 2016-12-01
 */
namespace Basico\Entity\Repository;

use Jlib\Util\FormatValue;
use Jlib\View\Html\Tabela\Tabela;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\DBAL\Types\DateTimeType;

class ViewConsultarMateriaisRepository extends AbstractEntityRepository {

	public function dataToHtmlTable ($data) {
		
		$tab = new Tabela($data);

		$tab->addCampo('CodMaterial', 'Código');
		$tab->addCampo('Material', 'Material');
		$tab->addCampo('Unidade', 'Unidade');
		$tab->addCampo('Classificacao', 'Classificação');
		$tab->addCampo('CodConsultarMateriais_Link', 'Opção');
		$tab->setHtmlId('ConsultarMateriais-list');
		 
		return $tab;
    }

	
	/**
	 * Busca as classificações ativas de acordo com o nome pesquisado.
	 *
	 * @param string $valor
	 * @param string $ativo
	 */
	public function findClassificacaoLike($valor, $ativo = 'S'){
		if ($ativo != 'S' && $ativo != 'N'){
			$ativo = 'S';
		}

		$qb = $this->getEntityManager()->createQueryBuilder();
		$qb->select('m')
		->from('Suprimento\Entity\Classificacao', 'm')
		->where('m.Ativo = :ativo')
		->andWhere('m.Classificacao LIKE :classificacao')
		->setParameter('ativo', $ativo)
		->setParameter(':classificacao', '%'.$valor.'%')
		->setMaxResults(20);
		$result = $qb->getQuery()->getResult();
		return $result;
	}
	
	// Verifica se há permissão para o usuário visualizar os registros
	public function verificaPermissao($codFornecedor){
		$qb = $this->getEntityManager()->createQueryBuilder();
		$qb->select('m')
		->from('Basico\Entity\Fornecedor', 'm')
		->where('m.CodFornecedor = :codfornecedor')
		->andWhere("m.VisualizarMateriais = 'S'")
		->setParameter(':codfornecedor', $codFornecedor);
		
// 		$sql = $qb->getQuery()->getSQL();
// 		$parameters = $qb->getQuery()->getParameters()->toArray();
		
		$dados = $qb->getQuery()->getResult();
		return $dados;
		
	}

	//Pesquisa os dados no banco baseado nas informações enviadas pelo form no portal do fornecedor
	public  function findMateriaisPesq(array $whereParams = null){
		
		$qb = $this->_em->createQueryBuilder();
		$qb->select('e')
		->from('Basico\Entity\ViewConsultarMateriais', 'e');
		$qb->Where("e.Ativo = 'S'");
	
		//Codigo
		if(isset($whereParams['Material'])){
			$qb->andWhere('e.Material LIKE :Material')
			->setParameter('Material', '%'.$whereParams['Material'].'%' );
		}
	
		//Nome
		if(isset($whereParams['CodClassificacao'])){
			$qb->andWhere('e.CodClassificacao = :CodClassificacao')
			->setParameter('CodClassificacao',$whereParams['CodClassificacao']);
		}
	
		$qb->addOrderBy('e.Material');
		$sql = $qb->getQuery()->getSQL();
		$entities = $qb->getQuery()->getResult();
	
	
		$arrayRetorno = array();
		foreach($entities as $entity){
	
			$arrayRetorno[] = $entity->toTable();
	
		}
		return $this->dataToHtmlTable($arrayRetorno);
	
	
	}
	
	//Alterado por Gabriel Henrique em 07/07/2017 - #15811
	//Função de pesquisa para a página inicial do menu de Materiais
	public  function findMateriaisMenu(array $whereParams = null, $acessoLiberado){
	
		$qb = $this->_em->createQueryBuilder();
		$qb->select('e')
		->from('Basico\Entity\ViewConsultarMateriais', 'e');
		$qb->Where("1=1");
		
		//Ativo
		if(isset($whereParams['Ativo'])){
			$qb->andWhere('e.Ativo = :Ativo')
			->setParameter('Ativo', $whereParams['Ativo'] );
		}
		
		//CodMaterial
		if(isset($whereParams['CodMaterial'])){
			$qb->andWhere('e.CodMaterial = :CodMaterial')
			->setParameter('CodMaterial', $whereParams['CodMaterial'] );
		}
		
		//CodExterno (Código de Barras)
		if(isset($whereParams['Cod_Externo'])){
			$qb->andWhere('e.Cod_Externo = :Cod_Externo')
			->setParameter('Cod_Externo',$whereParams['Cod_Externo']);
		}
	
		//Material
		if(isset($whereParams['Material'])){
			$qb->andWhere('e.Material LIKE :Material')
			->setParameter('Material', '%'.$whereParams['Material'].'%' );
		}
	
		//Nome
		if(isset($whereParams['CodClassificacao'])){
			$qb->andWhere('e.CodClassificacao = :CodClassificacao')
			->setParameter('CodClassificacao', $whereParams['CodClassificacao']);
		}
		
		//NCM
		if(isset($whereParams['NCM'])){
			$qb->andWhere('e.NCM = :NCM')
			->setParameter('NCM', $whereParams['NCM']);
		}
	
		$qb->setMaxResults(100);
		$qb->addOrderBy('e.Material', 'DESC');
		$entities = $qb->getQuery()->getResult();
	
	
		$arrayRetorno = array();
		foreach($entities as $entity){
	
			$arrayRetorno[] = $entity->toTable();
	
		}
		
		$arrayRetorno['Privilegio'] = $acessoLiberado;
		
		return $this->dataMenuMaterialToHtmlTable($arrayRetorno);
	
	}
	
	public function dataMenuMaterialToHtmlTable($data){
		
		$acesso = $data['Privilegio'];
		unset($data['Privilegio']);
		
		$tab = new Tabela($data);
		$tab->addCampo('CodMaterial', 'Código');
		$tab->addCampo('Material', 'Material');
		$tab->addCampo('Unidade', 'Unidade');
		$tab->addCampo('Classificacao', 'Classificação');
		if ($acesso){
			$tab->setAllowDelete(true);
		}else{
			$tab->setAllowDelete(false);
		}
		$tab->setDeleteLink('/suprimento/material/delete');
		$tab->setDeleteHrefClass('btnExcluir');
		$tab->addDeleteParam('CodMaterial',false);
		$tab->setShowDeleteIcon('fa fa-trash');
		$tab->setAllowEdit(true);
		$tab->setEditLink('/suprimento/material/edit');
		$tab->addEditParam('CodMaterial',false);
		$tab->setShowEditIcon('fa fa-pencil');
		
		$tab->setHtmlId('ConsultarMateriais-list');
		
		return $tab;
		
	}

}