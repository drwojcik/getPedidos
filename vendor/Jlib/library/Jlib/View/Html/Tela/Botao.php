<?php

namespace Jlib\View\Html\Tela;

use Jlib\View\Html\HtmlAbstract;

class Botao extends HtmlAbstract {
	
	/**
	 *
	 * @var TYPE_SPAN | TYPE_BUTTON
	 */
	public $_tipoHtml;
	
	/**
	 *
	 * @var Botao::CONST
	 */
	public $_tipoBotao;
	
	/**
	 *
	 * @var string
	 */
	public $_titulo;
	
	/**
	 *
	 * @var string
	 */
	public $_link = "#";
	
	/**
	 *
	 * @var string
	 */
	protected $_icone;
	
	// Constantes dos tipos HTML
	CONST HTML_SPAN = 'span';
	CONST HTML_BUTTON = 'button';
	
	// Constantes dos tipos de Botões
	CONST TIPO_NOVO = 'novo';
	CONST TIPO_VOLTAR = 'voltar';
	CONST TIPO_PESQUISAR = 'pesquisar';
	CONST TIPO_FORM_NOVO = 'form_novo';
	CONST TIPO_FORM_SALVAR = 'form_salvar';
	CONST TIPO_FORM_CANCELAR = 'form_cancelar';
	CONST TIPO_FORM_EXCLUIR = 'form_excluir';
	CONST TIPO_TABLE_ALTERAR = 'form_alterar';
	CONST TIPO_TABLE_EXCLUIR = 'form_excluir';
	
	// Constantes dos icones
	CONST ICONE_NOVO = '/img/icons/packs/fugue/16x16/document.png';
	CONST ICONE_VOLTAR = '/img/icons/16x16/bended-arrow-left.png';
	CONST ICONE_PESQUISAR = '/img/icons/packs/fugue/16x16/magnifier.png';
	CONST ICONE_FORM_NOVO = '/img/icons/packs/fugue/16x16/document--plus.png';
	CONST ICONE_FORM_SALVAR = '/img/icons/packs/fugue/16x16/tick.png';
	CONST ICONE_FORM_CANCELAR = '/img/icons/packs/diagona/16x16/150.png';
	CONST ICONE_FORM_EXCLUIR = '/img/icons/packs/fugue/16x16/cross.png';
	CONST ICONE_TABLE_ALTERAR = '/img/icons/packs/diagona/16x16/018.png';
	CONST ICONE_TABLE_EXCLUIR = '/img/icons/packs/diagona/16x16/101.png';
	public function __construct($tipoBotao) {
		$this->_tipoBotao = $tipoBotao;
		
		switch ($this->_tipoBotao) {
			case $this::TIPO_NOVO :
				$this->_titulo = 'Novo';
				$this->setIcone ( $this::ICONE_NOVO );
				$this->setTipoHtml ( $this::HTML_SPAN );
				break;
			case $this::TIPO_VOLTAR :
				$this->_titulo = 'Voltar';
				$this->setIcone ( $this::ICONE_VOLTAR );
				$this->setTipoHtml ( $this::HTML_SPAN );
				break;
			case $this::TIPO_PESQUISAR :
				$this->_titulo = 'Pesquisar';
				$this->setIcone ( $this::ICONE_PESQUISAR );
				$this->setTipoHtml ( $this::HTML_SPAN );
				break;
			case $this::TIPO_FORM_NOVO :
				$this->_titulo = 'Novo';
				$this->setIcone ( $this::TIPO_FORM_NOVO );
				$this->setTipoHtml ( $this::HTML_BUTTON );
				break;
			case $this::TIPO_FORM_SALVAR :
				$this->_titulo = 'Salvar';
				$this->setIcone ( $this::TIPO_FORM_SALVAR );
				$this->setTipoHtml ( $this::HTML_BUTTON );
				break;
			case $this::TIPO_FORM_CANCELAR :
				$this->_titulo = 'Cancelar';
				$this->setIcone ( $this::TIPO_FORM_CANCELAR );
				$this->setTipoHtml ( $this::HTML_BUTTON );
				break;
			case $this::TIPO_FORM_EXCLUIR :
				$this->_titulo = 'Excluir';
				$this->setIcone ( $this::TIPO_FORM_EXCLUIR );
				$this->setTipoHtml ( $this::HTML_BUTTON );
				break;
			case $this::TIPO_TABLE_ALTERAR :
				$this->_titulo = 'Alterar';
				$this->setIcone ( $this::TIPO_TABLE_ALTERAR );
				$this->setTipoHtml ( $this::HTML_BUTTON );
				break;
			case $this::TIPO_TABLE_EXCLUIR :
				$this->_titulo = 'Excluir';
				$this->setIcone ( $this::TIPO_TABLE_EXCLUIR );
				$this->setTipoHtml ( $this::HTML_BUTTON );
				break;
			default :
				$this->_titulo = 'Botão';
				$this->setIcone ( $this::ICONE_NOVO );
				$this->setTipoHtml ( $this::HTML_SPAN );
		}
	}
	public function __toString() {
		return $this->render ();
	}
	public function setTitulo($titulo) {
		$this->_titulo = $titulo;
	}
	protected function setTipoHtml($tipoHtml) {
		$this->_tipoHtml = $tipoHtml;
	}
	public function setIcone($icone) {
		$this->_icone = $icone;
	}
	public function getIcone() {
		if (! empty ( $this->_icone )) {
			return '<img src="' . $this->_icone . '">';
		} else {
			return '';
		}
	}
	public function render() {
		$attributes = $this->htmlAttributeToString ();
		
		$ret = '<' . $this->_tipoHtml . ' id="' . $this->_htmlId . '" class="' . $this->_htmlClass . '" style="' . $this->cssToString () . '" ' . $attributes . '>';
		$ret .= '	<a href="' . $this->_link . '">' . $this->getIcone () . ' ' . $this->_titulo . '</a>';
		$ret .= '</' . $this->_tipoHtml . '>';
		
		return $ret;
	}
}