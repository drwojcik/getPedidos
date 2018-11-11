<?php

namespace Jlib\Util\PivotTable;

class PivotTable {
	private $_fixedColumn;
	private $_fixedColumnName;
	private $_topColumns;
	private $_extraFixedColumn = array ();
	private $_arrayFixedColumns;
	private $_arrayTopColumns;
	private $_data;
	private $_dataReturn;
	private $_dataRepeated;
	private $_dataCount;
	private $_dataArrayByFixedColumn;
	public function __construct(array $data = array()) {
		if (count ( $data ) == 0)
			throw new \Exception ( 'Array inválido.' );
		$this->_data = $data;
	}
	public function addExtraFixedColumn($columnIndex, $columnName = null) {
		$this->_extraFixedColumn [] = array (
				'ColumnIndex' => $columnIndex,
				'ColumnName' => $columnName 
		);
	}
	public function getExtraFixedColumn() {
		return $this->_extraFixedColumn;
	}
	public function getDataArray() {
		return $this->_data;
	}
	public function setFixedColumn($columnIndex, $columnName = null) {
		$this->_fixedColumn = $columnIndex;
		$this->_fixedColumnName = $columnName;
		$this->setArrayFixedColumns ( $columnIndex );
	}
	public function setTopColumns($columnIndex) {
		$this->_topColumns = $columnIndex;
		$this->setArrayTopColumns ( $columnIndex );
	}
	public function getFixedColumn() {
		return $this->_arrayFixedColumns;
	}
	public function getTopColumns() {
		return $this->_arrayTopColumns;
	}
	private function setArrayFixedColumns($columnName) {
		$fixedColumns = array ();
		foreach ( $this->_data as $row ) {
			if (! in_array ( $row [$columnName], $fixedColumns ))
				$fixedColumns [] = $row [$columnName];
		}
		
		$this->_arrayFixedColumns = $fixedColumns;
	}
	private function setArrayTopColumns($columnName) {
		$topColumns = array ();
		foreach ( $this->_data as $row ) {
			if (! in_array ( $row [$columnName], $topColumns ))
				$topColumns [] = $row [$columnName];
		}
		
		$this->_arrayTopColumns = $topColumns;
	}
	private function prepare() {
		if (null === $this->_arrayTopColumns)
			throw new \Exception ( 'Coluna do top não definida.' );
		if (null === $this->_arrayFixedColumns)
			throw new \Exception ( 'Coluna fixa não definida.' );
	}
	public function pivot() {
		$this->prepare ();
		
		foreach ( $this->_data as $data ) {
			$fixedColumn = $data [$this->_fixedColumn];
			
			if (! isset ( $this->_dataArrayByFixedColumn [$fixedColumn] )) {
				$this->_dataArrayByFixedColumn [$fixedColumn] = $data;
			}
			
			$topColumn = $data [$this->_topColumns];
			
			if (isset ( $this->_dataReturn [$fixedColumn] [$topColumn] )) {
				$this->_dataRepeated [$fixedColumn] [$topColumn] [] = $data;
			} else {
				$this->_dataReturn [$fixedColumn] [$topColumn] = $data;
			}
		}
		
		return $this->_dataReturn;
	}
	public function getDataArrayByFixedColumn() {
		return $this->_dataArrayByFixedColumn;
	}
	
	/**
	 * Conta a ocorrência dos agrupamentos
	 */
	public function dataCount() {
		$this->prepare ();
		
		foreach ( $this->_data as $data ) {
			$fixedColumn = $data [$this->_fixedColumn];
			$topColumn = $data [$this->_topColumns];
			if (isset ( $this->_dataCount [$fixedColumn] [$topColumn] )) {
				$this->_dataCount [$fixedColumn] [$topColumn] += 1;
			} else {
				$this->_dataCount [$fixedColumn] [$topColumn] = 1;
			}
		}
		
		return $this->_dataCount;
	}
	public function getDataRepeated() {
		return $this->_dataRepeated;
	}
	public function getTableHeaderHtml() {
		$topColumns = $this->getTopColumns ();
		$thead = '<thead>';
		$thead .= '	<tr>';
		
		// Coluna Principal
		$thead .= '	<th>' . (empty ( $this->_fixedColumnName ) ? ' ' : $this->_fixedColumnName) . '</th>';
		
		// Colunas Secundárias
		foreach ( $this->_extraFixedColumn as $extraFixedColumn ) {
			$thead .= '	<th>' . (empty ( $extraFixedColumn ['ColumnName'] ) ? $extraFixedColumn ['ColumnIndex'] : $extraFixedColumn ['ColumnName']) . '</th>';
		}
		
		// Colunas Variáveis
		foreach ( $topColumns as $cellHead ) {
			$thead .= '<th>' . $cellHead . '</th>';
		}
		$thead .= '	</tr>';
		$thead .= '</thead>';
		
		return $thead;
	}
	public function getDistinctDataFromColumn($columnIndex) {
		$dataReturn = array ();
		foreach ( $this->_data as $data ) {
			if (isset ( $data [$columnIndex] )) {
				if (! in_array ( $data [$columnIndex], $dataReturn )) {
					$dataReturn [] = $data [$columnIndex];
				}
			}
		}
		
		return $dataReturn;
	}
}
