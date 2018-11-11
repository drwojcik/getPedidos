<?php

namespace Jlib\Log;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class LogException extends AbstractPlugin {
	protected $_config;
	protected $_logDir;
	
	/**
	 *
	 * @var \Exception
	 */
	protected $_exception;
	protected $_dirFileOnDisc;
	protected $_fileName;
	protected $_fileNameFull;
	protected $_mensagem;
	protected $_downloadLink;
	public function __construct(\Exception $e) {
		$this->setConfig ();
		$this->setDir ();
		
		$this->_exception = $e;
		$this->grava ();
		
		if (isset ( $this->_config ['log'] ['notifica_suporte'] ) && $this->_config ['log'] ['notifica_suporte'])
			$this->sendMail ();
	}
	
	/**
	 * Carrega o arquivo de configuração config.local.php
	 */
	private function setConfig() {
		if (! is_file ( getcwd () . '/config/autoload/config.local.php' )) {
			die ( 'Arquivo de configuração não encontrado.' );
		}
		
		$this->_config = include getcwd () . '/config/autoload/config.local.php';
	}
	
	/**
	 * Seta o diretório onde ficaram os arquivos de log
	 */
	private function setDir() {
		if (empty ( $this->_config ['log'] ['path'] )) {
			die ( 'Caminho dos arquivos de log não configurado.' );
		}
		
		if (! is_dir ( $this->_config ['log'] ['path'] )) {
			if (! mkdir ( $this->_config ['log'] ['path'], 0777, true )) {
				die ( 'Erro ao gerar arquivo de log. Falha ao criar o diretório.' );
			}
		}
		
		$this->_logDir = $this->_config ['log'] ['path'];
		$this->_dirFileOnDisc = getcwd () . str_replace ( '/', '\\', $this->_logDir ); // caminho fisico do diretório
	}
	
	/**
	 * Grava a mensagem no arquivo.
	 *
	 * @param \Exception $e        	
	 * @return boolean
	 */
	protected function grava() {
		// formata a mensagem de Log
		$mensagem = "MENSAGEM: " . $this->_exception->getMessage () . "\n";
		$mensagem .= "ARQUIVO: " . $this->_exception->getFile () . " - LINHA: " . $this->_exception->getLine () . "\n";
		$mensagem .= "TRACE: " . $this->_exception->getTraceAsString () . "\n";
		
		// define os dados do Log
		$this->_fileName = date ( 'Ymd_His' );
		$this->_fileNameFull = $this->_fileName . '.txt';
		$this->_mensagem = $mensagem;
		$this->_downloadLink = '/log/' . $this->_fileNameFull;
		
		// cria o objeto para gravar o Log
		$logger = new \Zend\Log\Logger ();
		
		// stream writer
		$writerStream = new \Zend\Log\Writer\Stream ( $this->_dirFileOnDisc . $this->_fileNameFull );
		$logger->addWriter ( $writerStream );
		
		// grava o arquivo
		$logger->crit ( $mensagem );
	}
	
	/**
	 * Envia email para o suporte informando sobre o erro gerado
	 */
	private function sendMail() {
		// ----------------------------------------------------------------------------------------
		// Configura os parâmetros para envio de email de acordo com o arquivo de configuração
		// ----------------------------------------------------------------------------------------
		foreach ( $this->_config ['email'] ['email_from'] as $email => $nome ) {
			$mailFromEmail = $email;
			$mailFromName = $nome;
		}
		foreach ( $this->_config ['email'] ['email_suporte_softwar'] as $email => $nome ) {
			$mailToEmail = $email;
			$mailToName = $nome;
		}
		
		// ----------------------------------------------------------------------------------------
		// Configura e envia o email para o suporte
		// ----------------------------------------------------------------------------------------
		
		// Mail Body
		$text = '<p>Ocorreu um erro em um código do ERP SCAM WEB. Segue abaixo os detalhes do erro:</p><br>';
		$text .= "<p><b>Mensagem: </b>{$this->_exception->getMessage()}</p>";
		$text .= "<p><b>Arquivo: </b>{$this->_exception->getFile()}</p>";
		$text .= "<p><b>Linha: </b>{$this->_exception->getLine()}</p>";
		$text .= "<p><b>Url Acessada: </b>{$_SERVER['REQUEST_URI']}</p>";
		$text .= "<p><b>Trace: </b>{$this->_exception->getTraceAsString()}</p>";
		
		$html = new \Zend\Mime\Part ( $text );
		$html->type = 'text/html';
		$html->charset = 'UTF-8';
		
		$body = new \Zend\Mime\Message ();
		$body->setParts ( array (
				$html 
		) );
		
		// mail writer
		$message = new \Zend\Mail\Message ();
		$message->setFrom ( $mailFromEmail, $mailFromName );
		$message->addTo ( $mailToEmail, $mailToName );
		$message->setSubject ( "ERP SCAM WEB - Erro Php" );
		$message->setBody ( $body );
		
		$transport = new \Zend\Mail\Transport\Smtp ();
		$smtpOptions = new \Zend\Mail\Transport\SmtpOptions ( array (
				'host' => $this->_config ['email'] ['smtp'] ['host'],
				'port' => $this->_config ['email'] ['smtp'] ['port'],
				'connection_class' => 'login',
				'connection_config' => array (
						'username' => $this->_config ['email'] ['smtp'] ['user'],
						'password' => $this->_config ['email'] ['smtp'] ['pass'] 
				) 
		) );
		$transport->setOptions ( $smtpOptions );
		$transport->send ( $message );
	}
	
	/**
	 * Retorna o nome do arquivo SEM a extensão.
	 * 
	 * @return string
	 */
	public function getFileName() {
		return $this->_fileName;
	}
	
	/**
	 * Retorna o nome do arquivo COM a extensão.
	 * 
	 * @return string
	 */
	public function getFileNameFull() {
		return $this->_fileNameFull;
	}
	
	/**
	 * Retorna a mensagem gerada pela exception juntamente com um html do link para baixar o arquivo de Log.
	 * 
	 * @return string
	 */
	public function getMessage() {
		$msg = "Ocorreu um erro ao realizar o processamento da página. 
                Nosso suporte técnico já foi notificado sobre o erro ocorrido, mas caso deseje
                você pode <a href='{$this->_downloadLink}'>clicar aqui</a> 
                para baixar o arquivo que contém detalhes sobre o erro ocorrido
                e nos enviar através do e-mail 
                <a href='mailto:suporte@softwar.com.br'>suporte@softwar.com.br</a>";
		
		return $msg;
	}
}

?>