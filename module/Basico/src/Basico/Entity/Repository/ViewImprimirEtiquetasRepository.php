<?php

/**
 * Classe responsável por conter os métodos
 * de consulta no banco
 * 
 * @author Gabriel Henrique <gabrielh@softwar.com.br>
 * @since 2016-12-12
 */
namespace Basico\Entity\Repository;

use Jlib\Util\FormatValue;
use Jlib\View\Html\Tabela\Tabela;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\DBAL\Types\DateTimeType;

class ViewImprimirEtiquetasRepository extends AbstractEntityRepository
{
	
	public function findClienteComboLike($valor, $ativo = 'S'){
		if ($ativo != 'S' && $ativo != 'N'){
			$ativo = 'S';
		}
	
		$qb = $this->getEntityManager()->createQueryBuilder();
		$qb->select('m')
		->from('Basico\Entity\ViewImprimirEtiquetas', 'm')
		->where('m.Ativo = :ativo')
		->andWhere('m.NomeFantasia LIKE :nomefantasia')
		->setParameter('ativo', $ativo)
		->setParameter(':nomefantasia', '%'.$valor.'%')
		->setMaxResults(20);
		$result = $qb->getQuery()->getResult();
		return $result;
	
	}
	
	//Faz a busca no banco dos registros relacionados com os parâmetros do filtro
	public function findImpressaoCliente(array $whereParams = null, \DateTime $dtIni){
		
// 		if($whereParams['AniversarioIni'] && $whereParams['AniversarioFim']){
			
// 			$iniAux = explode('/', $whereParams['AniversarioIni']);
// 			$diaIni = $iniAux[0];
// 			$mesIni = $iniAux[1];
			
// 			$fimAux = explode('/', $whereParams['AniversarioFim']);
// 			$diaFim = $fimAux[0];
// 			$mesFim = $fimAux[1];
			
// 		}
		
		$qb = $this->_em->createQueryBuilder();
		$qb->select('e')
		->from('Basico\Entity\ViewImprimirEtiquetas', 'e');
		
		//Ativo
		if(isset($whereParams['Ativo'])){
			if($whereParams['Ativo'] == 'S'){
				$qb->Where("e.Ativo = 'S'");
			}
		}
		
		//Inativo
		if(isset($whereParams['Inativo'])){
			if($whereParams['Inativo'] == 'S'){
				$qb->andWhere("e.Ativo = 'N'");
			}
		}
		
		//Codigo
		if(isset($whereParams['CodCliente'])){
			$qb->andWhere('e.CodCliente = :CodCliente')
			->setParameter('CodCliente',$whereParams['CodCliente']);
		}
		
		//Falecido
		if(isset($whereParams['Falecido'])){
			if($whereParams['Falecido'] == 'S'){
				$qb->andWhere('e.Falecido = :Falecido')
				->setParameter('Falecido',$whereParams['Falecido']);
			}
		}
		
		//UF
		if(isset($whereParams['Estado'])){
			$qb->andWhere('e.Estado = :Estado')
			->setParameter('Estado',$whereParams['Estado']);
		}
		
		//Cidade
		if(isset($whereParams['Cidade'])){
			$qb->andWhere('e.Cidade = :Cidade')
			->setParameter('Cidade',$whereParams['Cidade']);
		}
		
		//Dias sem doação
		if(isset($whereParams['SemDoacao'])){
			$qb->andWhere('e.UltimoPagamento < :DtIni')
			->setParameter('DtIni', $dtIni->format('Y-m-d').' 00:00:00.000');
			
		}
		
		if(isset($whereParams['MesNiver'])){
			
			if(isset($whereParams['AniversarioIni']) && isset($whereParams['AniversarioFim'])){
		
				$qb->andWhere('(e.Dia >= :diaIni AND e.Mes = :mes) AND (e.Dia <= :diaFim AND e.Mes = :mes)')
				->setParameter('diaIni',$whereParams['AniversarioIni'])
				->setParameter('diaFim',$whereParams['AniversarioFim'])
				->setParameter('mes',$whereParams['MesNiver']);
			
			}
			else {
				
				$qb->andWhere('e.Mes = :mes')
				->setParameter('mes',$whereParams['MesNiver']);
			}
				
		}
		/**
		 * Alterado por Diego Wojcik 23/12/2016 #13796 - Adicionado Filtro Cod Cliente
		 */
		if(isset($whereParams['CodClienteIni']) && isset($whereParams['CodClienteFim'])){
		
			$qb->andwhere('e.CodCliente BETWEEN :CodClienteIni AND :CodClienteFim')
			->setParameter('CodClienteIni',$whereParams['CodClienteIni'])
			->setParameter('CodClienteFim',$whereParams['CodClienteFim']);
				
		}
		
		//
		
		$qb->addOrderBy('e.CodCliente')
		->setMaxResults(1000);
		#$sql = $qb->getQuery()->getSQL();
		$entities = $qb->getQuery()->getResult();
		
		
		$arrayRetorno = array();
		foreach($entities as $entity){
		
			$arrayRetorno[] = $entity->toTable();
		
		}
		
		return $this->dataToHtmlTable($arrayRetorno);
		
	}
    
    /**
     * @see Basico\Entity\Repository.AbstractEntityRepository::dataToHtmlTable()
     */
    public function dataToHtmlTable ($data)
    {
    	$tab = new Tabela($data);
    	$tab->addCampo('checkbox_aprovar', '&nbsp;');
    	$tab->addCampo('CodCliente', 'Código');
    	$tab->addCampo('NomeFantasia', 'Nome');
    	$tab->setHtmlId('ImprimirEtiquetas-list');
    	
    	return $tab;
    }    
}

