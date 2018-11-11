<?php

namespace Jlib\View\Html;

abstract class HtmlAbstract {
	protected $_htmlId;
	protected $_htmlClass;
	protected $_cssStyleArray = array ();
	protected $_htmlAtribute = array ();
	
	/**
	 *
	 * @param string $_id        	
	 */
	public function setHtmlId($_id) {
		$this->_htmlId = $_id;
	}
	
	/**
	 *
	 * @param string $_class        	
	 */
	public function setHtmlClass($_class) {
		$this->_htmlClass = $_class;
	}
	
	/**
	 *
	 * @param
	 *        	array: atributo => valor $_cssStyleArray
	 */
	public function setCssStyleArray($_cssStyleArray) {
		$this->_cssStyleArray = $_cssStyleArray;
	}
	protected function cssToString() {
		$style = '';
		foreach ( $this->_cssStyleArray as $key => $value ) {
			$style .= $key . ': ' . $value . '; ';
		}
		return $style;
	}
	public function setHtmlAttribute(array $atributeArray) {
		$this->_htmlAtribute = $atributeArray;
	}
	public function htmlAttributeToString() {
		$strAttribute = '';
		foreach ( $this->_htmlAtribute as $atribute => $value ) {
			$strAttribute .= $atribute . "= '{$value}' ";
		}
		return $strAttribute;
	}
}