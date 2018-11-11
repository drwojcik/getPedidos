<?php

/**
 * Classe responsável por conter os métodos
 * de consulta no banco
 * 
 * @author Jonathan Fernando <jonathan@softwar.com.br>
 * @since 2014-09-11
 */
namespace Basico\Entity\Repository;


use Doctrine\ORM\Query\Expr;
class MensagemInternaRepository extends AbstractEntityRepository
{    
    
    /**
     * @see Basico\Entity\Repository.AbstractEntityRepository::dataToHtmlTable()
     */
	public function dataToHtmlTable($data) {
		throw new \Exception('Método não implementado.');   
    }
    
    /**
     * Lista as mensagens da caixa de entrada de um determinado usuário. 
     * Não lista as mensagens marcadas como arquivadas.
     * 
     * @param int $CodUsuario
     * @return array
     */
    public function findCaixaEntrada($CodUsuario){        
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('vmi.id, vmi.DataEnvio, vmi.Remetente, vmi.Assunto, vmi.DataLeitura,
                     vmi.idResposta, vmi.DataResposta,vmi.Obra')
            ->from('Basico\Entity\ViewMensagemInternaCaixaEntrada', 'vmi')
            ->where('vmi.DestinatarioCod = :CodUser')
            ->setParameter('CodUser', $CodUsuario);
                             
        $sql = $qb->getQuery()->getSQL();
        
        return $qb->getQuery()->getResult();
    }
    
    /**
     * Lista as mensagens enviadas de um determinado usuário.
     *
     * @param int $CodUsuario
     * @return array
     */
    public function findEnviadas($CodUsuario){
        $qb = $this->getEntityManager()->createQueryBuilder();        
        $qb->select('mi')
            ->from('Basico\Entity\MensagemInterna', 'mi')
            ->where('mi.Remetente = :CodUser')
            ->setParameter('CodUser', $CodUsuario);
        
        $sql = $qb->getQuery()->getSQL();
        
        return $qb->getQuery()->getResult();
    }
    
    /**
     * Lista as mensagens arquivadas de um determinado usuário.
     *
     * @param int $CodUsuario
     * @return array
     */
    public function findArquivadas($CodUsuario){
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('mi')
             ->from('Basico\Entity\MensagemInterna', 'mi')
             ->innerJoin('mi.Destinatarios', 'mid')
             ->where('mid.Arquivada = 1')
             ->andWhere('mid.Destinatario = :CodUser')
             ->setParameter('CodUser', $CodUsuario);
        
        #$sql = $qb->getQuery()->getSQL();
    
        return $qb->getQuery()->getResult();
    }
    
    /**
     * Retorna a quantidade de mensagens não lidas de um determinado usuário.
     * 
     * @param int $CodUsuario
     */
    public function findQtdMensagensNaoLidas($CodUsuario){
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select($qb->expr()->count('mid.id'))
            ->from('Basico\Entity\MensagemInterna', 'mi')
            ->innerJoin('mi.Destinatarios', 'mid')
            ->where('mid.Destinatario = :CodUser')
            ->andWhere('(mid.Arquivada IS NULL OR mid.Arquivada = 0)')
            ->andWhere('mid.DataLeitura IS NULL')
            ->setParameter('CodUser', $CodUsuario);
        
        #$sql = $qb->getQuery()->getSQL();
        
        return $qb->getQuery()->getOneOrNullResult();
    }
    
    /**
     * Retorna as mensagens não lidas de um determinado usuário.
     *  
     * @param int $CodUsuario
     * @param int $limit
     */
    public function findMensagensNaoLidas($CodUsuario, $limit){
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('mi')
            ->from('Basico\Entity\MensagemInterna', 'mi')
            ->innerJoin('mi.Destinatarios', 'mid')
            ->where('mid.Destinatario = :CodUser')
            ->andWhere('(mid.Arquivada IS NULL OR mid.Arquivada = 0)')
            ->andWhere('mid.DataLeitura IS NULL')
            ->setParameter('CodUser', $CodUsuario)
            ->orderBy('mi.DataEnvio', 'DESC')
            ->setMaxResults($limit);
    
        #$sql = $qb->getQuery()->getSQL();
    
        return $qb->getQuery()->getResult();
    }
    
    /**
     * Verifica se o usuário tem permissão para visulizar a mensagem.
     * Constatando se o mesmo é remetente ou destinatário da mensage.
     * 
     * @param int $CodUsuario
     * @param int $MensagemId
     */
    public function findMensagemToConversa($CodUsuario, $MensagemId){
        $qb = $this->getEntityManager()->createQueryBuilder();
        //ALTERADO POR PAULO LAVORATTI NO DIA 17/04/2015 MOTIVO: #4818
        $qb->select('mi.id, mi.DataEnvio, r.Nome As Remetente, mi.Assunto, mid.DataLeitura, 
                     miresp.id As idResposta, miresp.DataEnvio As DataResposta,IDENTITY(mi.ObraCodObra) AS ObraCodObra,oco.Obra')
            ->from('Basico\Entity\MensagemInterna', 'mi')
            ->innerJoin('mi.Remetente', 'r')
            ->leftJoin('mi.Destinatarios', 'mid',  \Doctrine\ORM\Query\Expr\Join::WITH, 'mid.Destinatario = '.$CodUsuario)
            ->leftJoin('mi.MensagemResposta', 'miresp', \Doctrine\ORM\Query\Expr\Join::WITH, 'miresp.Remetente = '.$CodUsuario)
            ->leftJoin('mi.ObraCodObra', 'oco', \Doctrine\ORM\Query\Expr\Join::WITH, 'oco.CodObra = mi.ObraCodObra')
            ->where('mi.id = :MensagemId')
            ->andWhere('(mi.Remetente = :CodUser OR mid.Destinatario = :CodUser)')
            ->setParameter('MensagemId', $MensagemId)
            ->setParameter('CodUser', $CodUsuario)
            ->setMaxResults(1)
            ->orderBy('miresp.DataEnvio', 'DESC');

        $sql = $qb->getQuery()->getSQL();
        
        return $qb->getQuery()->getOneOrNullResult();
    }
    
    
    public function findParent($MensagemInterna, $MensagemAtual, $MensagemAtualList){
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('mi')
            ->from('Basico\Entity\MensagemInterna', 'mi')
            ->where('(mi.MensagemInterna = :MensagemInterna OR mi.MensagemInterna = :MensagemAtual)')
            ->andWhere('mi.id NOT IN (:MensagemAtualList)')
            ->setParameter('MensagemInterna', $MensagemInterna)
            ->setParameter('MensagemAtual', $MensagemAtual)
            ->setParameter('MensagemAtualList', $MensagemAtualList);
        
        #$sql = $qb->getQuery()->getSQL();
        
        return $qb->getQuery()->getResult();
        
    }
}

?>