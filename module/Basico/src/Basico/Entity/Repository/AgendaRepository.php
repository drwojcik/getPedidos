<?php

/**
 * Classe responsável por conter os métodos
 * de consulta no banco
 * 
 * @author Jonathan Fernando <jonathan@softwar.com.br>
 * @since 2013-10-30
 */
namespace Basico\Entity\Repository;

use Jlib\Util\FormatValue;
use Jlib\View\Html\Tabela\Tabela;
class AgendaRepository extends AbstractEntityRepository
{
    /**
     * @see Basico\Entity\Repository.AbstractEntityRepository::dataToHtmlTable()
     */
	public function dataToHtmlTable ($data)
    {
        throw new \Exception('Método não implementado.');
    }
   
public function findAgendaUsuario($usuario){
	    $date = date('Y-m-d', strtotime( ' + 7 days'));
        $status = "C";

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('e')
            ->from('Basico\Entity\Agenda', 'e')
            ->innerJoin('Basico\Entity\AgendaAviso', 'a', 'WITH', 'a.CodAgenda = e.CodAgenda')
           // ->innerJoin('e.CodAgenda', 've')
            ->where('a.CodUsuario = ?1')


            ->andWhere('e.DataAgenda <= :Data')
            ->setParameter('Data',$date)
            ->andWhere('e.Status != :Status')
            ->setParameter('Status',$status)
            //->andWhere('ve.codSubEtapa = ?3')
           // ->setParameter(1, $ObraAlbum)
            //->setParameter(2, $EtapaCodOrcamento)
            ->setParameter(1, $usuario);


        $qb->orderBy('e.DataAgenda','DESC');
        #$sql = $qb->getQuery()->getSQL();
        #$parameters = $qb->getQuery()->getParameters()->toArray();
    
        $dados = $qb->getQuery()->getResult();
        return $dados;
    }
public function findAgendaUsuarioPendentes($usuario){
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('e')
            ->from('Basico\Entity\Agenda', 'e')
            ->innerJoin('Basico\Entity\AgendaAviso', 'a', 'WITH', 'a.CodAgenda = e.CodAgenda')
           // ->innerJoin('e.CodAgenda', 've')
            ->where('a.CodUsuario = ?1')
            ->andWhere('e.Status <> ?2')
            //->andWhere('ve.codSubEtapa = ?3')
           // ->setParameter(1, $ObraAlbum)
            //->setParameter(2, $EtapaCodOrcamento)
            ->setParameter(1, $usuario)
            ->setParameter(2, 'C');
    
        #$sql = $qb->getQuery()->getSQL();
        #$parameters = $qb->getQuery()->getParameters()->toArray();
    
        $dados = $qb->getQuery()->getResult();
        return $dados;
    }
}

?>