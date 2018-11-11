<?php

/**
 * Classe responsável por conter os métodos
 * de consulta no banco
 * 
 * @author Gabriel Henrique <gabrielh@softwar.com.br>
 * @since 2016-11-24
 */
namespace Basico\Entity\Repository;

use Jlib\Util\FormatValue;
use Jlib\View\Html\Tabela\Tabela;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\DBAL\Types\DateTimeType;

class ViewDesativarPessoasRepository extends AbstractEntityRepository
{
	
	public function findPessoasAtivas(){
		
		$qb = $this->_em->createQueryBuilder();
		$qb->select('e')
		->from('Basico\Entity\ViewDesativarPessoas', 'e')
		->where("e.Ativo = 'S'")
		//->andWhere('e.UltimoPagamento = e.Datapago')
		->addOrderBy('e.CodCliente')
		->setMaxResults(1000);
		$entities = $qb->getQuery()->getResult();
		
		$arrayRetorno = array();
		foreach($entities as $entity){
			
			$arrayRetorno[] = $entity->toTable();
			
		}
		return $this->dataToHtmlTable($arrayRetorno);
		
	}
	
	public  function findPessoasAtivasPesq(array $whereParams = null, \DateTime $dtIni){
		
		$qb = $this->_em->createQueryBuilder();
		$qb->select('e')
		->from('Basico\Entity\ViewDesativarPessoas', 'e');
		$qb->Where("e.Ativo = 'S'");
		
		//Codigo
		if(isset($whereParams['CodCliente'])){
			$qb->andWhere('e.CodCliente = :CodCliente')
			->setParameter('CodCliente',$whereParams['CodCliente']);
		}
		
		if ( (!empty($whereParams['CodClienteIni']) && !empty($whereParams['CodClienteFim']))){
			$qb->andwhere('e.CodCliente BETWEEN :ClienteIni AND :ClienteFim')
			->setParameter('ClienteIni', $whereParams['CodClienteIni'])
			->setParameter('ClienteFim', $whereParams['CodClienteFim']);
		}
		
		//Nome
		if(isset($whereParams['NomeFantasia'])){
			$qb->andWhere('e.NomeFantasia = :NomeFantasia')
			->setParameter('NomeFantasia',$whereParams['NomeFantasia']);
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
		
		//Tipo Pagamento
		if(isset($whereParams['TipoPgto'])){
			$qb->andWhere('e.TipoPgto = :TipoPgto')
			->setParameter('TipoPgto',$whereParams['TipoPgto']);
		}
		
		//Dias sem doação
		if(isset($whereParams['SemDoacao'])){
			
			$qb->andWhere('e.UltimoPagamento < :DtIni')
			->setParameter('DtIni', $dtIni->format('Y-m-d').' 00:00:00.000');
			//->setParameter('DtFim', $dtFim->format('Y-m-d').' 00:00:00.000');
		}
		
		$qb->addOrderBy('e.CodCliente')
		->setMaxResults(1000);
		$sql = $qb->getQuery()->getSQL();
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
    	$tab->addCampo('CodCliente', 'Cod');
    	$tab->addCampo('NomeFantasia', 'Nome');
    	$tab->addCampo('Ativo', 'Ativo');
    	$tab->addCampo('DtNascimento', 'Nascimento');
    	$tab->addCampo('Cidade', 'Cidade');
    	$tab->addCampo('Estado', 'UF');
    	$tab->addCampo('UltimoPagamento', 'Últ. Doação');
    	$tab->setHtmlId('DesativarPessoas-list');
    	
    	
    	return $tab;
    }    
}

