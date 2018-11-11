<?php

namespace Basico\Service;

use Doctrine\ORM\EntityManager;
use Basico\Entity\Configurator;

abstract class AbstractService {
	
	/**
	 * @var EntityManager
	 */
	protected $em;

	protected $entity;	
	
	public function __construct(EntityManager $em){
		$this->em = $em;
	}
		
	public function insert($data){
		$data = $this->prepareToSave($data);
		$entity = new $this->entity($data);
		
		$this->em->persist($entity);
		$this->em->flush();
		return $entity;
	}	
	
	/**
	 * Atualiza um registro.
	 * 
	 * @param array $data (Dados da tabela incluindo o(s) id(s))
	 * @param string $id (Nome do campo id da tabela)
	 */
	public function update(array $data, $id = null){
		if (empty($id)){
			$id = 'id'; 
		}
		$data = $this->prepareToSave($data);
		
		//busca a referencia do registro
		$entity = $this->em->getReference($this->entity, $data[$id]);
		$entity = Configurator::configure($entity, $data);

		$this->em->persist($entity);
		$this->em->flush();
		return $entity;
	}
	
	public function delete($id){
		//busca a referencia do registro
		$entity = $this->em->getReference($this->entity, $id);
		if ($entity){
			$this->em->remove($entity);
			$this->em->flush();
			
			return $id;
		}
	}
	
	/**
	 * Busca o novo codigo a ser gravado na tabela
	 * Utilizado em tabelas que não possuem autoincremento ¬¬
	 *
	 * @param $entity
	 * @param $campo
	 * @return int $novoCodigo
	 * @throws \Exception
	 */
	public function getNovoCodigo($entity, $campo, $where = null){
		//query builder
		$qb = $this->em->createQueryBuilder();
		$qb->select('MAX(t.'.$campo.')')
			->from($entity, 't');
		if (!empty($where))
		    $qb->where($where);
		$result = $qb->getQuery()->getSingleScalarResult();
		if (empty($result)){
			return 1;
		} else if ($result === false){
		    throw new \Exception('Falha ao buscar o MAX('.$campo.') da tabela: ' . $entity);
		} else {
			return $result + 1;
		}
	}
	
	// Força a classe que estende essa classe a definir esse método
	/**
	 * Trata os campos antes de enviar para o banco
	 * @param array $dados
	 */
	abstract protected function prepareToSave(array $dados);		
}