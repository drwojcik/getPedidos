<?php

namespace Jlib\Report;

use Zend\Session\Container;

class JasperReport {
	private $_configServer;
	private $_configPort;
	private $_aplicacao;
	private $_reportPath;
	private $_reportName;
	private $_reportparams;
	private $_reportFormat;
	
	/**
	 * Instancia um novo relatório.
	 *
	 * @param string $reportName        	
	 * @param string $aplicacao
	 *        	(opcional)
	 */
	public function __construct($reportName, $sessionName = null) {
		// Nome do relatório
		$this->_reportName = $reportName;
	}
	protected function setConfig() {
		// Resgata os dados de configuração
		// setados no arquivo
		// config/autoload/jasper_report.local.php OU config/autoload/jasper_report.global.php
		// carregados na session Report
		$session = new Container ( 'Report' );
		if (! $session->offsetExists ( 'host' ))
			throw new \Exception ( 'JaserReporst -> Parâmetro "host" não definido!' );
		if (! $session->offsetExists ( 'port' ))
			throw new \Exception ( 'JaserReporst -> Parâmetro "port" não definido!' );
		if (! $session->offsetExists ( 'mainAplicationName' ))
			throw new \Exception ( 'JaserReporst -> Parâmetro "mainAplicationName" não definido!' );
		if (! $session->offsetExists ( 'reportAplicationName' ))
			throw new \Exception ( 'JaserReporst -> Parâmetro "reportAplicationName" não definido!' );
		if (! $session->offsetExists ( 'formatoPadrao' ))
			throw new \Exception ( 'JaserReporst -> Parâmetro "formatoPadrao" não definido!' );
		
		$this->_configServer = $session->offsetGet ( 'host' );
		$this->_configPort = $session->offsetGet ( 'port' );
		$this->_reportPath = $session->offsetGet ( 'mainAplicationName' );
		$this->_aplicacao = $session->offsetGet ( 'reportAplicationName' );
		$this->_reportFormat = $session->offsetGet ( 'formatoPadrao' );
	}
	
	/**
	 * Retorna a URL do relatório
	 * 
	 * @return string
	 */
	public function getUrl() {
		// Seta a configuração padrão
		$this->setConfig ();
		
		$params = $this->getReportParamsToUrl ();
		$url = "http://" . $this->_configServer . ":" . $this->_configPort;
		$url .= "/" . $this->_reportPath . "/servlets/relatorio";
		$url .= "?aplicacao=" . $this->_aplicacao;
		$url .= "&relatorio=" . $this->_reportName;
		$url .= "&formato=" . $this->_reportFormat;
		$url .= "&" . $this->getReportParamsToUrl ();
		
		return $url;
	}
	
	/**
	 * Adiciona um parâmetro ao relatório
	 * 
	 * @param string $name        	
	 * @param string $value        	
	 */
	public function addParam($name, $value) {
		$this->_reportparams [$name] = $value;
	}
	
	/**
	 * Retorna uma string com os parâmetros, formatada para ser passada à URL
	 * 
	 * @return string
	 */
	protected function getReportParamsToUrl() {
		$paramStr = "";
		$paramCount = 0;
		if (count ( $this->_reportparams )) {
			foreach ( $this->_reportparams as $paramName => $paramValue ) {
				$paramCount ++;
				$paramArray [] = "parametro_" . $paramCount . "=" . $paramName . "&valor_" . $paramCount . "=" . $paramValue;
			}
		}
		$paramStr = implode ( "&", $paramArray );
		return $paramStr;
	}
	
	/**
	 * Getters e Setters
	 */
	protected function getConfigServer() {
		return $this->_configServer;
	}
	protected function getConfigPort() {
		return $this->_configPort;
	}
	protected function getAplicacao() {
		return $this->_aplicacao;
	}
	protected function getReportPath() {
		return $this->_reportPath;
	}
	protected function getReportName() {
		return $this->_reportName;
	}
	protected function getReportparams() {
		return $this->_reportparams;
	}
	protected function getReportFormat() {
		return $this->_reportFormat;
	}
	protected function setConfigServer($_configServer) {
		$this->_configServer = $_configServer;
	}
	protected function setConfigPort($_configPort) {
		$this->_configPort = $_configPort;
	}
	protected function setAplicacao($_aplicacao) {
		$this->_aplicacao = $_aplicacao;
	}
	protected function setReportPath($_reportPath) {
		$this->_reportPath = $_reportPath;
	}
	protected function setReportName($_reportName) {
		$this->_reportName = $_reportName;
	}
	protected function setReportFormat($_reportFormat) {
		$this->_reportFormat = $_reportFormat;
	}
}

?>