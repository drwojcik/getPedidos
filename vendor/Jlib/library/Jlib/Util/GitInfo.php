<?php

namespace Jlib\Util;

/**
 * Recupera informações sobre o git, tais como:
 * - Branch atual
 * - Versão atual (tag)
 * - Data do último commit
 *
 * @author Jonathan Fernando <jonathan@softwar.com.br>
 * @since 2014-04-26
 */
class GitInfo {
	private $_branchAtual;
	private $_tagAtual;
	
	/**
	 *
	 * @var \Datetime
	 */
	private $_dataUltimoCommit;
	public function __construct() {
		$this->setBranchAtual ();
		$this->setTagAtual ();
		$this->setDataUltimoCommit ();
	}
	public function __toString() {
		return $this->render ();
	}
	public function getBranchAtual() {
		if (empty ( $this->_branchAtual ))
			$this->setBranchAtual ();
		
		return $this->_branchAtual;
	}
	public function getTagAtual() {
		if (empty ( $this->_tagAtual ))
			$this->setTagAtual ();
		
		return $this->_tagAtual;
	}
	public function getDataUltimoCommit() {
		if (empty ( $this->_dataUltimoCommit ))
			$this->setDataUltimoCommit ();
		
		try {
			return $this->_dataUltimoCommit->format ( 'd/m/Y H:i:s' );
		} catch ( \Exception $e ) {
			return 'Erro ao obter a data';
		}
	}
	protected function setBranchAtual() {
		$this->_branchAtual = shell_exec ( 'git rev-parse --abbrev-ref HEAD' );
	}
	protected function setTagAtual() {
		$this->_tagAtual = shell_exec ( 'git describe --abbrev=0 --tags' );
	}
	protected function setDataUltimoCommit() {
		$this->_dataUltimoCommit = new \DateTime ( substr ( shell_exec ( 'git show -s --format=%ci' ), 0, 19 ) );
	}
	protected function render() {
		return '<p><strong>Versão: </strong>' . $this->getTagAtual () . ' - ' . $this->getBranchAtual () . '</p>' . '<p><strong>Data da Versão: </strong>' . $this->getDataUltimoCommit () . '</p>';
	}
}

?>