<?php

namespace Jlib\Util\CrossTab;

class CrossTab {
	
	/**
	 * Calcula o valor médio do campo.
	 */
	CONST OPER_AVG = 'AVG';
	
	/**
	 * Calcula a quantidade de repetições do campo.
	 */
	CONST OPER_COUNT = 'COUNT';
	
	/**
	 * Calcula o valor máximo do campo.
	 */
	CONST OPER_MAX = 'MAX';
	
	/**
	 * Calcula o valor mínimo do campo.
	 */
	CONST OPER_MIN = 'MIN';
	
	/**
	 * Calcula a somatória do valor do campo.
	 */
	CONST OPER_SUM = 'SUM';
	
	/**
	 * Retorna o valor original do campo.
	 */
	CONST OPER_NONE = 'NONE';
	
	// Campo que formará as linhas da CrossTab
	private $_fieldRowIndex;
	private $_fieldRowName;
	private $_arrayFieldRow = null;
	
	// Campo que formará as colunas da CrossTab
	private $_fieldColumnIndex;
	private $_arrayFieldColumn = null;
	
	// Colunas extras da CrossTab
	private $_extraFieldColumn = array ();
	
	// Valor da célula da CrossTab
	private $_fieldDataIndex;
	private $_fieldDataOperation;
	private $_fieldData = null;
	
	// Array original dos dados
	private $_dataOriginal;
	
	// Array de retorno dos dados
	private $_dataCrossTab;
	
	// Array com os valores totais de cada coluna
	private $_arrayFieldColumnTotalSum = null;
	
	// Array com os valores totais de cada linha
	private $_arrayFieldRowTotalSum = null;
	public function __construct(array $data = null) {
		if (! empty ( $data ))
			$this->_dataOriginal = $data;
	}
	
	/**
	 * Seta os dados que serão utilizados na CrossTab.
	 *
	 * @param array $data        	
	 */
	public function setData(array $data) {
		$this->_dataOriginal = $data;
	}
	
	/**
	 * Valor que será transformado em linha da CrossTab.
	 *
	 * @param string|int $ColumnIndex
	 *        	(Índice no array de dados referente ao valor desejado)
	 * @param string $ColumnTitle
	 *        	(Título da coluna na tabela)
	 */
	public function setFieldRow($ColumnIndex, $ColumnTitle = null) {
		$this->_fieldRowIndex = $ColumnIndex;
		$this->_fieldRowName = $ColumnTitle;
		$this->setArrayFieldRow ( $ColumnIndex );
	}
	
	/**
	 * Cria um array com os valores que irão compor as linhas da CrossTab.
	 *
	 * @param string|int $ColumnIndex
	 *        	(Índice no array de dados referente ao valor desejado)
	 */
	private function setArrayFieldRow($ColumnIndex) {
		$fixedRow = array ();
		foreach ( $this->_dataOriginal as $row ) {
			if (! in_array ( $row [$ColumnIndex], $fixedRow ))
				$fixedRow [] = $row [$ColumnIndex];
		}
		
		$this->_arrayFieldRow = $fixedRow;
	}
	
	/**
	 * Valor que será transformado em coluna da CrossTab.
	 *
	 * @param string|int $ColumnIndex
	 *        	(Índice no array de dados referente ao valor desejado)
	 */
	public function setFieldColumn($ColumnIndex) {
		$this->_fieldColumnIndex = $ColumnIndex;
		$this->setArrayFieldColumn ( $ColumnIndex );
	}
	
	/**
	 * Cria um array com os valores que irão compor as colunas da CrossTab.
	 *
	 * @param string|int $ColumnIndex
	 *        	(Índice no array de dados referente ao valor desejado)
	 */
	private function setArrayFieldColumn($ColumnIndex) {
		$fieldColumns = array ();
		foreach ( $this->_dataOriginal as $row ) {
			if (! in_array ( $row [$ColumnIndex], $fieldColumns ))
				$fieldColumns [] = $row [$ColumnIndex];
		}
		
		$this->_arrayFieldColumn = $fieldColumns;
	}
	
	/**
	 * Valor que será transformado no valor da célula resultante do cruzamento entre linha e coluna.
	 *
	 * @param string|int $ColumnIndex
	 *        	(Índice no array de dados referente ao valor desejado)
	 * @param string $CellTitle
	 *        	(Título da célula)
	 * @param CONST $ColumnValueOperation
	 *        	(Operação que será realizada para obter o valor do campo. Operações disponíveis:
	 *        	OPER_AVG, OPER_COUNT, OPER_MAX, OPER_MIN, OPER_SUM, OPER_NONE)
	 */
	public function addFieldData($ColumnIndex, $CellTitle, $ColumnValueOperation) {
		$this->_fieldData [] = array (
				'ColumnIndex' => $ColumnIndex,
				'CellTitle' => $CellTitle,
				'ColumnValueOperation' => $ColumnValueOperation 
		);
	}
	
	/**
	 * Valor que será transformado no valor da célula resultante do cruzamento entre linha e coluna.
	 *
	 * @param string|int $ColumnIndex
	 *        	(Índice no array de dados referente ao valor desejado)
	 * @param CONST $ColumnValueOperation
	 *        	(Operação que será realizada para obter o valor do campo. Operações disponíveis:
	 *        	OPER_AVG, OPER_COUNT, OPER_MAX, OPER_MIN, OPER_SUM, OPER_NONE)
	 */
	private function setFieldData($ColumnIndex, $ColumnValueOperation) {
		$this->_fieldDataIndex = $ColumnIndex;
		$this->_fieldDataOperation = $ColumnValueOperation;
	}
	
	/**
	 * Seta o campo que será totalizado ao final da CrossTab.
	 *
	 * @param string|int $ColumnIndex
	 *        	(opcional) (Seta os valores de uma coluna específica)
	 * @param string $TotalLabel
	 *        	(opcional) (Descrição da label da linha que irá conter o total)
	 *        	
	 * @return array
	 */
	public function setGrandTotalColumn($ColumnIndex = null, $TotalLabel = null) {
		$ColumnIndex = (empty ( $ColumnIndex ) ? $this->_fieldDataIndex : $ColumnIndex);
		$TotalLabel = (empty ( $TotalLabel ) ? 'Total' : $TotalLabel);
		
		if (empty ( $this->_arrayFieldColumnTotalSum [$ColumnIndex] )) {
			$this->_arrayFieldColumnTotalSum [$ColumnIndex] ['RowLabel'] = $TotalLabel;
			
			$dataSum = array ();
			foreach ( $this->_dataOriginal as $data ) {
				$fieldColumnIndex = $data [$this->_fieldColumnIndex];
				$fieldValue = $data [$ColumnIndex];
				
				if (isset ( $dataSum [$fieldColumnIndex] ))
					$dataSum [$fieldColumnIndex] += $fieldValue;
				else
					$dataSum [$fieldColumnIndex] = $fieldValue;
			}
			
			$this->_arrayFieldColumnTotalSum [$ColumnIndex] ['DataSum'] = $dataSum;
		}
	}
	
	/**
	 *
	 * @param
	 *        	string|int (opcional) $ColumnIndex (Retorna os valores de uma coluna específica)
	 * @return array (Ex.: array('Valor' => array(
	 *         'RowLabel' => 'Valor Total',
	 *         'Data' => array(
	 *         '01/02/14' => 100,
	 *         '02/02/14' => 200,
	 *         )
	 *         )
	 *         )
	 *         )
	 */
	public function getGrandTotalColumn($ColumnIndex = null) {
		if (empty ( $ColumnIndex ))
			return $this->_arrayFieldColumnTotalSum;
		else
			return $this->_arrayFieldColumnTotalSum [$ColumnIndex];
	}
	
	/**
	 * Retorna um array com as linha da CrossTab e os totais correspondentes à cada linha.
	 *
	 * @return array
	 */
	public function getGrandTotalRow() {
		if (empty ( $this->_arrayFieldRowTotalSum )) {
			// atribui o array das linhas da cross tab
			foreach ( $this->_arrayFieldRow as $row )
				$this->_arrayFieldRowTotalSum [$row] = 0;
			
			foreach ( $this->_dataOriginal as $data ) {
				$fieldRowIndex = $data [$this->_fieldRowIndex];
				$fieldValue = $data [$this->_fieldDataIndex];
				
				if (isset ( $this->_arrayFieldRowTotalSum [$fieldRowIndex] )) {
					$this->_arrayFieldRowTotalSum [$fieldRowIndex] += $fieldValue;
				}
			}
		}
		
		return $this->_arrayFieldRowTotalSum;
	}
	
	/**
	 * Adiciona colunas extras à CrossTab.
	 * As colunas serão inseridas à direita da coluna principal
	 *
	 * @param string|int $ColumnIndex
	 *        	(Índice no array de dados referente ao valor desejado)
	 * @param string $ColumnTitle
	 *        	(Título da coluna na tabela)
	 */
	public function addExtraFieldColumn($ColumnIndex, $ColumnTitle) {
		if (array_key_exists ( $ColumnIndex, $this->_dataOriginal [0] )) {
			foreach ( $this->_dataOriginal as $data ) {
				$Row = $data [$this->_fieldRowIndex];
				if (! isset ( $ColumnValue [$Row] ))
					$ColumnValue [$Row] = $data [$ColumnIndex];
			}
		} else {
			throw new \Exception ( "Erro ao adicionar a coluna extra '{$ColumnIndex}'. A coluna não existe nos dados informados." );
		}
		$this->_extraFieldColumn [] = array (
				'ColumnIndex' => $ColumnIndex,
				'ColumnTitle' => $ColumnTitle,
				'ColumnValue' => $ColumnValue 
		);
	}
	
	/**
	 * Método de verificação executado antes da criação da CrossTab.
	 *
	 * @throws \Exception
	 */
	private function prepare() {
		if (null === $this->_arrayFieldRow)
			throw new \Exception ( 'Linha da CrossTab n�o definida. Para isso utilize o método setFieldRow().' );
		if (null === $this->_arrayFieldColumn)
			throw new \Exception ( 'Coluna da CrossTab n�o definida. Para isso utilize o método setFieldColumn().' );
		
		$this->setCrossTab ();
	}
	
	/**
	 * Seta um array com a estrutura da CrossTab.
	 * A composição dos campos sem o valor das células.
	 *
	 * @return array
	 */
	private function setCrossTab() {
		foreach ( $this->_dataOriginal as $data ) {
			$fieldRowIndex = $data [$this->_fieldRowIndex];
			$fieldColumnIndex = $data [$this->_fieldColumnIndex];
			
			foreach ( $this->_fieldData as $fieldData ) {
				$fieldDataIndex = $fieldData ['ColumnIndex'];
				$fieldDataOperation = $fieldData ['ColumnValueOperation'];
				
				if (! isset ( $this->_dataCrossTabStructure [$fieldRowIndex] [$fieldDataIndex] [$fieldColumnIndex] )) {
					$this->_dataCrossTabStructure [$fieldRowIndex] [$fieldDataIndex] [$fieldColumnIndex] = '';
				}
			}
		}
		
		return $this->_dataCrossTabStructure;
	}
	
	/**
	 * Retorna os dados em forma de CrossTab.
	 *
	 * @return array
	 */
	public function getCrossTab() {
		$this->prepare ();
		
		if (empty ( $this->_dataCrossTab )) {
			foreach ( $this->_dataOriginal as $data ) {
				$fieldRowIndex = $data [$this->_fieldRowIndex];
				$fieldColumnIndex = $data [$this->_fieldColumnIndex];
				
				foreach ( $this->_fieldData as $fieldData ) {
					$fieldDataIndex = $fieldData ['ColumnIndex'];
					$fieldDataOperation = $fieldData ['ColumnValueOperation'];
					$fieldValue = $data [$fieldData ['ColumnIndex']];
					
					if ($fieldDataOperation == self::OPER_SUM) {
						if (isset ( $this->_dataCrossTab [$fieldRowIndex] [$fieldDataIndex] [$fieldColumnIndex] )) {
							$this->_dataCrossTab [$fieldRowIndex] [$fieldDataIndex] [$fieldColumnIndex] += $fieldValue;
						} else {
							$this->_dataCrossTab [$fieldRowIndex] [$fieldDataIndex] [$fieldColumnIndex] = $fieldValue;
						}
					} else if ($fieldDataOperation == self::OPER_NONE) {
						$this->_dataCrossTab [$fieldRowIndex] [$fieldDataIndex] [$fieldColumnIndex] = $fieldValue;
					} else {
						throw new \Exception ( "A operação '{$fieldDataOperation}' não está disponível." );
					}
				}
			}
		}
		
		return $this->_dataCrossTab;
	}
	
	/**
	 * Retorna o campo que foi transformado em linha.
	 *
	 * @return string
	 */
	public function getFieldRow() {
		return $this->_fieldRowIndex;
	}
	
	/**
	 * Retorna o título da coluna do campo que foi transformado em linha.
	 *
	 * @return string
	 */
	public function getFieldRowName() {
		return $this->_fieldRowName;
	}
	
	/**
	 * Retorna o campo que foi transformado em coluna.
	 *
	 * @return string
	 */
	public function getFieldColumn() {
		return $this->_fieldColumnIndex;
	}
	
	/**
	 * Retorna um array dos campos que serão transformados no valor da célula resultante do
	 * cruzamento Linha x Colunha.
	 *
	 * @return array
	 */
	public function getFieldData() {
		return $this->_fieldData;
	}
	
	/**
	 * Retorna o campo que foi transformado no valor da célula resultante do
	 * cruzamento Linha x Colunha.
	 *
	 * @param string|int $ColumnIndex
	 *        	(Índice no array de dados referente ao valor desejado)
	 * @return array|NULL
	 */
	public function getFieldDataByIndex($ColumnIndex) {
		foreach ( $this->_fieldData as $data ) {
			if ($data ['ColumnIndex'] == $ColumnIndex) {
				return $data;
			}
		}
		return null;
	}
	
	/**
	 * Retorna as colunas variáveis criadas para a CrossTab.
	 *
	 * @return array
	 */
	public function getVariableColumns() {
		return $this->_arrayFieldColumn;
	}
	
	/**
	 * Retorna as colunas extras adicionadas à CrossTab.
	 *
	 * @return array
	 */
	public function getExtraFieldColumn() {
		return $this->_extraFieldColumn;
	}
	
	/**
	 * Retorna um valor realizando o cruzamento Linha x Coluna
	 * para um determinado campo.
	 *
	 * @param string $RowValue
	 *        	(Valor da linha da CrossTab)
	 * @param string $ColumnValue
	 *        	(Valor da coluna da CrossTab)
	 * @param string $Value
	 *        	(Nome da coluna que cont�m o valor desejado)
	 */
	public function getValueFromData($RowValue, $ColumnValue, $Value) {
		foreach ( $this->_dataOriginal as $linha ) {
			if (($linha [$this->_fieldRowIndex] == $RowValue) && ($linha [$this->_fieldColumnIndex] == $ColumnValue)) {
				return $linha [$Value];
			}
		}
		
		return null;
	}
}

?>