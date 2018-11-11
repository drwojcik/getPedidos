<?php

namespace Jlib\View\Html\Tela;

class Tela {
	
	/**
	 *
	 * @var string
	 */
	protected $_titulo;
	
	/**
	 *
	 * @var array
	 */
	protected $_barraFerramentas = array ();
	public function __construct($titulo = null) {
		$this->_titulo = (empty ( $titulo ) ? 'Nova Tela' : $titulo);
	}
	public function getTitulo() {
		return $this->_titulo;
	}
	public function getBarraFerramentas() {
		$ret = '';
		foreach ( $this->_barraFerramentas as $botao ) {
			$ret .= $botao->render ();
		}
		return $ret;
	}
	
	/**
	 * Adiciona um botao à Barra de Ferramentas
	 * 
	 * @param Botao $botao        	
	 */
	public function addButton(Botao $botao) {
		$this->_barraFerramentas [] = $botao;
	}
}