<?php
namespace Basico\Auditoria;

use Zend\Log\Writer\Stream;
use Zend\Log\Logger;

use Doctrine\ORM\EntityManager;
/**
 * Classe abstrata para Auditoria, Adaptado de http://leandrosilva.info/log-para-doctrine2-e-zf2/
 *
 * @author 	Diego Wojcik <diego@softwar.com.br>
 * @since   2016-02-11
 */
abstract class AbstractLogger extends Logger
{
	/**
	 * Directório onde o log será salvo
	 *
	 * @var string
	 */
	private $_logDir;

	/**
	 * Nome do arquivo de log
	 *
	 * @var string
	 */
	private $_logFile;

	/**
	 * Construtor
	 *
	 * Define o logDir e logFile e cria o writter. Se o logDir for nulo
	 * irá usar o diretório temporário do sistema
	 *
	 * @param string $logFile
	 * @param string $logDir
	 */
	public function __construct($logFile, $logDir = null)
	{
		parent::__construct();

		if (null == $logDir) {
			$logDir = sys_get_temp_dir();
		}
		$this->setLogDir($logDir);
		$this->setLogFile($logFile);

		$writer = new Stream($logDir . DIRECTORY_SEPARATOR . $logFile);
		$this->addWriter($writer);
	}

	/**
	 * Retorna o logDir
	 *
	 * @return string
	 */
	public function getLogDir()
	{
		return $this->_logDir;
	}

	/**
	 * Define o logDir
	 *
	 * @param string $logDir
	 * @throws \InvalidArgumentException
	 */
	public function setLogDir($logDir)
	{
		$logDir = trim($logDir);
		if (!file_exists($logDir) || !is_writable($logDir)) {
			throw new \InvalidArgumentException("Diretório inválido!");
		}

		$this->_logDir = $logDir;
	}
	/**
	 * @return the $_logFile
	 */
	public function getLogFile()
	{
		return $this->_logFile;
	}

	/**
	 * @param string $_logFile
	 */
	public function setLogFile($logFile)
	{
		$logFile = trim($logFile);
		if (null === $logFile || '' == $logFile) {
			throw new \InvalidArgumentException("Arquivo inválido!");
		}
		$this->_logFile = $logFile;
	}
}

?>