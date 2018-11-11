<?php

namespace Jlib\Util\CrossTab;

class CrossTabHtml {
	
	/**
	 *
	 * @var CrossTab
	 */
	private $CrossTab;
	private $_thead;
	private $_tbody;
	private $_tfoot;
	private $_table;
	public function __construct(CrossTab $CrossTab) {
		$this->CrossTab = $CrossTab;
	}
	public function getTableHeaderHtml() {
		if (empty ( $this->_thead )) {
			$topColumns = $this->CrossTab->getVariableColumns ();
			
			$thead = '<thead>';
			$thead .= '	<tr>';
			
			// Coluna Principal
			$fieldRowName = $this->CrossTab->getFieldRowName ();
			$thead .= '	<th>' . (empty ( $fieldRowName ) ? ' ' : $fieldRowName) . '</th>';
			
			// Colunas Extras
			$ExtraColumns = $this->CrossTab->getExtraFieldColumn ();
			foreach ( $ExtraColumns as $extraColumn ) {
				$ColumnTitle = empty ( $extraColumn ['ColumnTitle'] ) ? '&nbsp;' : $extraColumn ['ColumnTitle'];
				$thead .= " <th>{$ColumnTitle}</th>";
			}
			
			// Colunas Vari�veis
			foreach ( $topColumns as $cellHead ) {
				$thead .= '<th>' . $cellHead . '</th>';
			}
			$thead .= '	</tr>';
			$thead .= '</thead>';
			
			$this->_thead = $thead;
		}
		
		return $this->_thead;
	}
	public function getTable() {
		if (empty ( $this->_table )) {
			// ========================================================
			// Monta o html da tabela da CrossTable
			// ========================================================
			
			$CrossTab = $this->CrossTab->getCrossTab ();
			$CrossTabColumns = $this->CrossTab->getVariableColumns ();
			$GrandTotalRow = $this->CrossTab->getGrandTotalRow ();
			
			// header da tabela
			$tableHead = $this->getTableHeaderHtml ();
			// ====================================================
			
			// body da tabela
			$tableBody = '<tbody>';
			foreach ( $CrossTab as $rowName => $CellValues ) {
				$tableBody .= '<tr>';
				// coluna principal
				$tableBody .= "<td>{$rowName}</td>";
				// loop nas colunas extras
				$ExtraColumns = $this->CrossTab->getExtraFieldColumn ();
				foreach ( $ExtraColumns as $extraColumn ) {
					$tableBody .= "<td>{$extraColumn['ColumnValue'][$rowName]}</td>";
				}
				
				// faz o loop nas colunas da CrossTab
				foreach ( $CrossTabColumns as $column ) {
					$tableBody .= '<td>';
					$tableBody .= '<table style="width: 100%;">';
					
					// conte�do da célula Linha x Coluna
					foreach ( $CellValues as $CellIndex => $CellValue ) {
						$CellInfo = $this->CrossTab->getFieldDataByIndex ( $CellIndex );
						$CellTitle = empty ( $CellInfo ['CellTitle'] ) ? 'Valor' : $CellInfo ['CellTitle'];
						if (isset ( $CellValue [$column] )) {
							$tableBody .= "<tr>
                            				<td style='font-size:10px; padding:3px;'><b>{$CellTitle}</b></td>
                            				<td style='text-align:right; padding:3px;'>{$CellValue[$column]}</td>
                            			  </tr>";
						} else {
							$tableBody .= "<tr><td>&nbsp;</td><td>&nbsp;</td></tr>";
						}
					}
					$tableBody .= '</table>';
					$tableBody .= '</td>';
				}
				$tableBody .= '</tr>';
			}
			$tableBody .= '</tbody>';
			// ====================================================
			
			// footer da tabela
			$GrandTotalColumns = $this->CrossTab->getGrandTotalColumn ();
			$tableFoot = '<tfoot>';
			// $tableFoot.= '<td><b>Total</b></td>'; //criar parametro para contar qtd de colunas fixas
			foreach ( $GrandTotalColumns as $GrandTotalColumn ) {
				$tableFoot .= '<tr>';
				foreach ( $GrandTotalColumn as $key => $value ) {
					if ($key == 'RowLabel') {
						// conta a qtde de colunas extras
						$TotExtraCols = count ( $this->CrossTab->getExtraFieldColumn () );
						$Colspan = $TotExtraCols + 1; // +1 devido � coluna fixa
						
						$tableFoot .= "<td colspan='{$Colspan}' style='text-align:right;' ><b>{$value}</b></td>";
					} else {
						foreach ( $value as $dataSumColumn => $dataSumValue ) {
							$tableFoot .= "<td style='text-align:right;' >{$dataSumValue}</td>";
						}
					}
				}
				$tableFoot .= '</tr>';
			}
			$tableFoot .= '</tfoot>';
			// ========================================================
			
			$this->_table = '<table border="1">' . $tableHead . $tableBody . $tableFoot . '</table>';
		}
		
		return $this->_table;
	}
}

?>