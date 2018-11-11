<?php
namespace Basico\Auditoria;

use Basico\Auditoria\AbstractLogger;
use Doctrine\DBAL\Logging\SQLLogger as LogInterface;

use Doctrine\ORM\EntityManager;
/**
 * Recupera todos os Logs para a Auditoria e Grava, Adaptado de http://leandrosilva.info/log-para-doctrine2-e-zf2/
 *
 * @author 	Diego Wojcik <diego@softwar.com.br>
 * @since   2016-02-11
 */
class SqlLogger extends AbstractLogger implements LogInterface
//class Auditoria extends AbstractLogger implements LogInterface
{
    /*
     * Executada sempre antes que um SQL for executado
     *
     * @see \Doctrine\DBAL\Logging\SQLLogger::startQuery()
     */
    public function startQuery ($sql, array $params = null, array $types = null)
    {
    	$oper = substr($sql, 0, 6);
    	$vazio = '';
    	$arraytabela = explode(" " , $sql);
    	if($oper == 'INSERT'){
    		$tabela = $arraytabela[2];
    		$operacao = 'I';
    		$sql = $sql. ' - '.$params;
    		$this->gravaAuditoria($tabela, $operacao, $sql, null, 'Registro Inserido pelo SCAM WEB');
    		//INSERT INTO
    	}elseif($oper == 'UPDATE'){
    		$tabela = $arraytabela[1];
    		$operacao = 'U';
    		
    		//gravaAuditoria Teste Gravando em Arquivo
    		$msg = 'SQL: ' . $sql;
    		if ($params) {
    			$msg .= PHP_EOL . "\tPARAMS: " . json_encode($params);
    		}
    		if ($types) {
    			$msg .= PHP_EOL . "\tTIPOS: " . json_encode($types);
    		}
    		
    		$sql = $sql. ' - '.$msg;
    		$this->gravaAuditoria($tabela, $operacao, $sql, null, 'Registro Alterado pelo SCAM WEB');
    	}elseif($oper == 'DELETE'){
    		//DELETE FROM
    		$tabela = $arraytabela[2];
    		$operacao = 'D';
    		$sql = $sql. ' - '.$params;
    		$this->gravaAuditoria($tabela, $operacao, $sql, null, 'Registro Excluído pelo SCAM WEB');
    	}
//     	//gravaAuditoria Teste Gravando em Arquivo
//         $msg = 'SQL: ' . $sql;
//         if ($params) {
//             $msg .= PHP_EOL . "\tPARAMS: " . json_encode($params);
//         }
//         if ($types) {
//             $msg .= PHP_EOL . "\tTIPOS: " . json_encode($types);
//         }
//         $this->debug($msg);
    }
 
    /*
     * (non-PHPdoc)
     * @see \Doctrine\DBAL\Logging\SQLLogger::stopQuery()
     */
    public function stopQuery ()
    {}
    
    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEm(){
    	if (null === $this->em){
    		$this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
    	}
    
    	return $this->em;
    }
    
    /**
     * Função que Insere a auditoria
     * Padrão para parâmetro Operacao -> Inglês
     * I = Insert , U = Update, D = Delete
     * @author Diego Wojcik <diego@softwar.com.br>
     * @since 2015-02-11
     * @param string
     */
    public function gravaAuditoria($tabela, $operacao , $sql, $chave = null, $descricao = null ){
    	$sql = str_replace("'", "''", $sql);
    	$dt = new \DateTime();
    	$data = $dt->format('Y-m-d H:i:s.u'); //$dtIni->format('Y-m-d').' 00:00:00.000'
    	$querycodigo = 'SELECT MAX(ID_Auditoria) + 1 FROM Auditoria';
    	$stmt = $this->getEm()->getConnection()->query($querycodigo);
    	$novocodigo = $stmt->fetch();
    
    	$repoLogado = $this->getServiceLocator()->get('ViewHelperManager')->get('UserInfo');
    	$codfuncionario = $repoLogado->getObject()->getcodFuncionario();
    	$query = "INSERT INTO Auditoria (ID_Auditoria , Data, Tabela, Usuario, Operacao, SQL, Chave, Descricao) ";
    	"   VALUES ($novoodigo, $data, '$tabela', $codfuncionario, $operacao, '$sql', '$chave', '$descricao')" ;
    	//$this->getEm()->getConnection()->exec($query);
    	$novocodigo = '';
    	 
    }
    
}

?>