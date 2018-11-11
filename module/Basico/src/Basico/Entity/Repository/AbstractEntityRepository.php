<?php
namespace Basico\Entity\Repository;

use Doctrine\ORM\EntityRepository;

abstract class AbstractEntityRepository extends EntityRepository
{

    /**
     * Valor da option da combo
     */
    protected $_fetchPairsId;
    
    /**
     * Descrição da option da combo
     */
    protected $_fetchPairsDescription;

    
	/**
     * Getters e Setters
     */
    public function getFetchPairsId ()
    {
        return $this->_fetchPairsId;
    }
    
    public function getFetchPairsDescription ()
    {
        return $this->_fetchPairsDescription;
    }
    
    public function setFetchPairsId ($_fetchPairsId)
    {
        $this->_fetchPairsId = $_fetchPairsId;
    }
    
    public function setFetchPairsDescription ($_fetchPairsDescription)
    {
        $this->_fetchPairsDescription = $_fetchPairsDescription;
    }
    
    
    /**
     * Retorna um array com os registros da tabela
     *
     * @return array
     * @example
     * 		array(id => 'description', id => 'description')
     */
    public function fetchPairs(array $whereParams = null, array $orderBy = null){
        if (empty($this->_fetchPairsId) || empty($this->_fetchPairsDescription))
            throw new \Exception('Parâmetros fetchPairsId e fetchPairsDescription não foram definidos.');
        
        $entities = $this->findBy($whereParams, $orderBy);         
        $arrayRetorno = array();
        
        $getId = 'get'.$this->_fetchPairsId;
        $getDescription = 'get'.$this->_fetchPairsDescription;
        foreach($entities as $entity){
            $arrayRetorno[$entity->$getId()] = $entity->$getDescription();
        }
        return $arrayRetorno;
    }
    
    /**
     * Faz uma busca na tabela de acordo com o where informado.
     * Retorna uma tabela em formato html.
     * 
     * @param array $where
     */
    public function getTableHtmlByWhere(array $where = array(), array $order = array()){
        if (count($where)){
            $registros 	= $this->findBy($where,$order);            
        } else {
            $registros 	= $this->findAll();
        }
        
        $data = array();
        foreach ($registros as $registro){
            $data[] = $registro->toTable();
        }
    
        return $this->dataToHtmlTable($data);
    }
    
    /**
     * Recebe um array com os objetos da tabela.
     * Retorna uma tabela em formato html.
     * 
     */    
    public function getTableHtmlByCollection($ObjectList){
        $data = array();
        foreach ($ObjectList AS $object) {
            $data[] = $object->toTable();
        }
            
        return $this->dataToHtmlTable($data);
    }
    
    /**
     * Recebe um array com os objetos da tabela.
     * Retorna uma tabela em formato html.
     *
     */
    public function getTableHtmlByLista($ObjectList){
    	$data = array();
    	foreach ($ObjectList AS $object) {
    		$data[] = $object;
    	}
    
    	return $this->dataToHtmlTable($data);
    }
    
    /**
     * Formata os dados e retorna uma tabela em html.
     * 
     * @param array $data
     */
    public abstract function dataToHtmlTable($data);
}

?>