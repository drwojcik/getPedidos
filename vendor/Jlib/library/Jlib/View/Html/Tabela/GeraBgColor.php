<?php

namespace Jlib\View\Html\Tabela;

class GeraBgColor {
	CONST STYLE_DEFAULT = 'default';
	private $_arrayData;
	private $_style;
	
	/**
	 * Gera um array no qual o indice é o dado e o valor é a cor.
	 * Ex: array('XPTO' => '#CCCCCC')
	 */
	public function getBgColorFromDistinctData() {
		if (null == $this->_arrayData)
			throw new \Exception ( 'Array com os dados não informado.' );
		
		$dataUnique = array_unique ( $this->_arrayData );
		$colors = $this->getColorArray ();
		
		$dataReturn = array ();
		for($i = 0; $i < count ( $dataUnique ); $i ++) {
			$dataReturn [$dataUnique [$i]] = $colors [$i];
		}
		
		return $dataReturn;
	}
	public function getColorArray() {
		if (null == $this->_style)
			throw new \Exception ( 'Estilo não suportado' );
		
		if ($this->_style == self::STYLE_DEFAULT)
			return $this->getDefaultColors ();
	}
	private function getDefaultColors() {
		return array (
				"#EAC3B2",
				"#B8CAAA",
				"#FFEAB3",
				"#70B9D1",
				"#E7F1FB",
				"#C4CDD5",
				"#ADB5BC",
				"#82888D",
				"#575B5E",
				"#2B3E42",
				"#747E80",
				"#D3E4FB",
				"#A1C3E1",
				"#A2ADBC",
				"#D5E1DD",
				"#666666" 
		);
	}
	public function getArrayData() {
		return $this->_arrayData;
	}
	public function getStyle() {
		return $this->_style;
	}
	
	/**
	 * Seta o array para configurar as cores.
	 * Para cada informação (não repetida) do array será gerada uma cor.
	 * Ex.: array('ABC', 'XPTO', 'XYZ');
	 *
	 * @param array $_arrayData        	
	 */
	public function setArrayData($_arrayData) {
		$this->_arrayData = $_arrayData;
	}
	
	/**
	 * Seta o estilo de cores
	 * 
	 * @param string $_style        	
	 * @throws \Exception
	 */
	public function setStyle($_style) {
		switch ($_style) {
			case self::STYLE_DEFAULT :
				$this->_style = self::STYLE_DEFAULT;
				;
				break;
			default :
				throw new \Exception ( 'Estilo não suportado' );
				break;
		}
	}
}