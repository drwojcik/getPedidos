<?php

namespace Jlib\View\Html\Message;

use Jlib\View\Html\HtmlAbstract;

class Alert extends HtmlAbstract {
	
	CONST ERROR_MSG = 'alert-danger';
	CONST SUCCESS_MSG = 'alert-success';
	CONST INFO_MSG = 'alert-info';
	CONST WARNING_MSG = 'alert-warning';

	public $_type;
	public $_msgTitle;
	public $_msg;
	public $_closeButton;	
	
	public function __construct($tipo, $msgTitle, $msg, $cssArray = null) {
		$this->_type = $tipo;
		$this->_msgTitle = $msgTitle;
		$this->_msg = $msg;
		$this->_closeButton = true;
		
		if (! empty ( $cssArray )) {
			$this->_cssStyleArray = array_merge ( $this->_cssStyleArray, $cssArray );
		}
	}
	
	public function __toString() {
		return $this->render ();
	}
	
	public function render() {
		$html = '';
		$html .= '<div id="'.$this->_htmlId.'" class="alert '.$this->_type.'" role="alert" style="'.$this->cssToString().'">';
		if ($this->_closeButton){
			$html .= '	<button class="close" data-dismiss="alert" type="button">';
			$html .= '		<span aria-hidden="true">x</span>';
			$html .= '		<span class="sr-only">Fechar</span>';
			$html .= '	</button>';
		}
		if (!empty($this->_msgTitle))
			$html .= '	<strong>'.$this->_msgTitle.'</strong> - ';
		$html .= $this->_msg;
		$html .= '</div>';
		
		return $html;
	}
}