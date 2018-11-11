<?php
namespace Basico\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class CodigoCidadesRepository extends EntityRepository {

    /**
     * Retorna um array no qual o indice é o CodAdvocaciaRito a e valor é a Rito.
     *
     * @return array
     */
    public function fetchPairs(array $whereParams = null, array $orderBy = null) {
        try {
            $qb = $this->getEntityManager()->createQueryBuilder();
            $qb->select('o.Cod, o.Uf')->from($this->getEntityName(), 'o');
            
            // seta o where na query
            $paramCount = 0;
            foreach ($whereParams as $campo => $valor) {
                $paramCount ++;
                $qb->andWhere("o.{$campo} = ?{$paramCount}");
                $qb->setParameter($paramCount, $valor);
            }
            
            // seta o order by
            foreach ($orderBy as $order => $value) {
                $qb->addOrderBy('o.' . $order, $value);
            }
            $qb->groupBy("o.Cod,o.Uf");
            
            $entities = $qb->getQuery()->getResult();
            
            $arrayRetorno = array();
            foreach ($entities as $entity) {
                // $arrayObra[$entity->getCodObra()] = $entity->getObra();
                $arrayRetorno[$entity['Cod']] = $entity['Uf'];
            }
            return $arrayRetorno;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Retorna o Municipio referente ao código
     *
     * @return mixed
     * @author Matheus Lutero <matheus@softwar.com.br>
     * @since 2017-10-23
     */
    public function findCidade($codigoCidade = null) {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('e.Cod, e.Municipio')
            ->from($this->getEntityName(), 'e')
            ->where('1=1');
        
        if ($codigoCidade) {
            $qb->andWhere('e.Cod = :Cod')->setParameter('Cod', $codigoCidade);
            $sql = $qb->getQuery();
            $entities = $qb->getQuery()->getResult();
            
            if ($entities) {
                foreach ($entities as $entity) {
                    $cidade = array(
                        'Cod' => $entity['Cod'],
                        'Municipio' => $entity['Municipio']
                    );
                }
            } else {
                $cidade = array();
            }
        } else {
            $cidade = array();
        }
        
        return $cidade;
    }

    // public function getDadosUf(array $whereParams = null, array $orderBy = null){
    // $qb = $this->getEntityManager()->createQueryBuilder();
    // $qb->select('p.Codigo, p.Uf')
    // ->from($this->_entityName,'p');
    // $paramCount = 0;
    // if(!empty($whereParams)){
    // foreach ($whereParams as $campo => $valor){
    // $paramCount++;
    // $qb->andWhere("p.{$campo} = ?{$paramCount}");
    // $qb->setParameter($paramCount, $valor);
    // }
    // }
    // $qb->addGroupBy('CodigoUf');
    // // $qb->orderBy('UF ASC');
    // /*//seta o order by
    // if(!empty($orderBy)){
    // foreach ($orderBy as $order => $value){
    // $qb->addOrderBy('p.'.$order,$value);
    // }
    // }*/
    // $entities = $qb->getQuery()->getResult();
    // $arrayRetorno = array();
    // foreach($entities as $entity){
    // $arrayRetorno[$entity['Codigo']] = $entity['Uf'];
    // }
    // return $arrayRetorno;
    // }
    public function dataToHtmlTable($data) {
        throw new \Exception('Método não implementado.');
    }
}