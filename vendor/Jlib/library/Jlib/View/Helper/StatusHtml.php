<?php

namespace Jlib\View\Helper;

class StatusHtml {
	CONST INICIADO = 'I';
	CONST NAO_INICIADO = 'NI';
	CONST FINALIZADO = 'F';
	private $_iconIniciado = '/img/icons/packs/diagona/16x16/154.png';
	private $_iconNaoIniciado = '/img/icons/packs/diagona/16x16/151.png';
	private $_iconFinalizado = '/img/icons/packs/diagona/16x16/152.png';
	public function getStatusProducao($status) {
		switch ($status) {
			case self::INICIADO :
				return $this->_iconIniciado;
				break;
			case self::NAO_INICIADO :
				return $this->_iconNaoIniciado;
				break;
			case self::FINALIZADO :
				return $this->_iconFinalizado;
				break;
			default :
				;
				break;
		}
	}
	public function getIconIniciado() {
		return $this->_iconIniciado;
	}
	public function getIconNaoIniciado() {
		return $this->_iconNaoIniciado;
	}
	public function getIconFinalizado() {
		return $this->_iconFinalizado;
	}
	public function setIconIniciado($_iconIniciado) {
		$this->_iconIniciado = $_iconIniciado;
	}
	public function setIconNaoIniciado($_iconNaoIniciado) {
		$this->_iconNaoIniciado = $_iconNaoIniciado;
	}
	public function setIconFinalizado($_iconFinalizado) {
		$this->_iconFinalizado = $_iconFinalizado;
	}
}