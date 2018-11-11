<?php

namespace Jlib\View\Html\Tabela;

use Jlib\View\Html\HtmlAbstract;

class Tabela extends HtmlAbstract {
	
	// configuração dos campos da tabela
	public $_campoBd = array ();
	public $_campoTitulo = array ();
	public $_campoValor = array ();
	public $_campoWidth = array ();
	protected $_campoWidthMedida = '%';
	public $_campoOpcoes = array ();
	protected $_showHeader = true;
	protected $_showFooter = false;
	protected $_showFooterEmpty = false;
	protected $_footerConfig = array ();
	
	// demais configurações
	protected $_editLink;
	protected $_deleteLink;
	protected $_downloadLink;
	protected $_editHrefClass;
	protected $_deleteHrefClass;
	protected $_downloadHrefClass;
	protected $_editParams = array ();
	protected $_deleteParams = array ();
	protected $_dowloadParams = array ();
	protected $_editIcon;
	protected $_deleteIcon;
	protected $_downloadIcon;
	protected $_showEditIcon = true;
	protected $_showDeleteIcon = true;
	protected $_showDownloadIcon = true;
	protected $_allowEdit = false;
	protected $_allowDelete = false;
	protected $_allowDownload = false;
	
	/**
	 * Cria uma tabela html a partir de um array de dados
	 *
	 * @param array $data
	 *        	(Valores da tabela)
	 * @param array $campos
	 *        	Campos que farão parte da tabela.
	 *        	Ex: array('NomeNoBD', 'Título do Campo a ser exibido', 'Array de opções')
	 */
	public function __construct(array $data, array $campos = array(), array $tamanhoColunas = array(), array $rodape = array()) {
		if (count ( $campos )) {
			foreach ( $campos as $campo ) {
				$this->_campoBd [] = $campo [0];
				$this->_campoTitulo [] = $campo [1];
				if (isset ( $campo [2] ))
					$this->_campoOpcoes [$campo [0]] = $campo [2];
			}
		}
		
		if (count ( $tamanhoColunas ) && count ( $campos )) {
			if (count ( $tamanhoColunas ) != count ( $campos )) {
				$msg = 'Não foram especificados tamanho para todas as colunas das tabelas.';
				$msg = " A quantidade de itens dos parâmetros campos e tamanhoColunas deve ser a mesma.";
				throw new \Exception ( $msg );
			}
			
			for($i = 0; $i <= count ( $tamanhoColunas ); $i ++) {
				// associa o tamanho da coluna ao nome do campo no BD
				$nomeCampoBD = $this->_campoBd [$i];
				$this->_campoWidth [$nomeCampoBD] = $tamanhoColunas [$i];
			}
		}
		
		$this->_campoValor = $data;
		$this->_htmlClass = 'table';
		$this->_editIcon = 'fa fa-pencil';
		$this->_deleteIcon = 'fa fa-times';
		$this->_downloadIcon = 'fa fa-download';
	}
	public function __toString() {
		return $this->render ();
	}
	public function render() {
		if (empty ( $this->_campoBd )) {
			throw new \Exception ( 'ERRO: Informe quais campos farao partes da tabela. Para isso utilize o metodo addCampo().' );
		}
		
		if (! empty ( $this->_campoValor )) {
			$table = '<table id="' . $this->_htmlId . '" class="' . $this->_htmlClass . '" style="' . $this->cssToString () . '">';
			
			if ($this->_showHeader) {
				// Monta o cabecalho da tabela
				$table .= ' <thead><tr>';
				for($i = 0; $i < count ( $this->_campoTitulo ); $i ++) {
					$titulo = $this->_campoTitulo [$i];
					$nomeBD = $this->_campoBd [$i];
					
					$colWidth = isset ( $this->_campoWidth [$nomeBD] ) ? 'style="width: ' . $this->_campoWidth [$nomeBD] . $this->_campoWidthMedida . '"' : '';
					$table .= "<th {$colWidth}>{$titulo}</th>";
				}
				if ($this->_allowEdit || $this->_allowDelete)
					$table .= '<th class="center">Opções</th>';
				$table .= ' </tr></thead>';
			}
			
			// Monta o corpo da tabela
			$table .= ' <tbody>';
			
			// Loop nos registros
			foreach ( $this->_campoValor as $linha ) {
				$table .= ' <tr>';
				// varre o array dos campos que irão compor a tabela
				foreach ( $this->_campoBd as $campo ) {
					$value = $linha [$campo];
					
					// verifica se foram setadas opções para o campo
					$options = (! empty ( $this->_campoOpcoes [$campo] ) ? $this->_campoOpcoes [$campo] : null);
					
					$colWidth = null;
					// se não estiver mostrando o cabeçalho, verifica se foi definida uma largura para o campo
					if (! $this->_showHeader) {
						$colWidth = isset ( $this->_campoWidth [$campo] ) ? 'style="width: ' . $this->_campoWidth [$campo] . '%"' : '';
					}
					
					$cell = $this->formatCell ( $value, $options, $colWidth );
					$table .= $cell;
				}
				
				// verifica se esta habilitado editar
				$option = '';
				$optCount = 0;
				if ($this->_allowEdit) {
					$optCount ++;
					$params = $this->editParamsToUrl ( $linha );
					$dataTag = $this->paramsToUrlAsDataTag ( $linha );
					
					$class = 'btn btn-default btn-rounded btn-condensed btn-sm';
					$option .= '<a href="' . $this->_editLink . '/' . $params . '" ' . $dataTag . ' class="'.$class.' btn-option ' . $this->_editHrefClass . '">';
					if ($this->_showEditIcon) {
						$option .= '<i class="' . $this->_editIcon . '"></i>';						
					} else {
						$option .= 'Alterar';
					}
					$option .= '</a>';
				}
				// verifica se esta habilitado excluir
				if ($this->_allowDelete) {
					$optCount ++;
					$params = $this->deleteParamsToUrl ( $linha );
					$dataTag = $this->paramsToUrlAsDataTag ( $linha );
					
					$class = 'btn btn-danger btn-rounded btn-condensed btn-sm';
					$option .= '<a href="' . $this->_deleteLink . '/' . $params . '" class="'.$class.' btn-option ' . $this->_deleteHrefClass . '" ' . $dataTag . ' >';
					if ($this->_showDeleteIcon) {
						$option .= '<i class="' . $this->_deleteIcon . '"></i>';
					} else {
						$option .= 'Excluir';
					}
					$option .= '</a>';
				}
				// verifica se esta habilitado realizar download
				if ($this->_allowDownload) {
					$optCount ++;
					$params = $this->downloadParamsToUrl ( $linha );
					
					$class = 'btn btn-default btn-rounded btn-condensed btn-sm';
					$option .= '<a href="' . $this->_downloadLink . '/' . $params . '" class="'.$class.' btn-option ' . $this->_downloadHrefClass . '">';
					if ($this->_showDownloadIcon) {
						$option .= '<i class="' . $this->_downloadIcon . '"></i>';
					} else {
						$option .= 'Download';
					}
					$option .= '</a>';
				}
				
				// calcula o tamanho da coluna de opções da tabela
				$cellWidth = $optCount * 45;
				
				if ($this->_allowEdit || $this->_allowDelete || $this->_allowDownload)
					$table .= '<td class="center" width="' . $cellWidth . '">' . $option . '</td>';
				$table .= ' </tr>';
			}
			$table .= ' </tbody>';
			
			// Monta o footer da tabela
			if ($this->_showFooter || $this->_showFooterEmpty) {
				$table .= '<tfoot>';
				$table .= '	<tr>';
				$fcount = 0;
				foreach ( $this->_campoTitulo as $value ) {
					// verifica se foi configurado valores e opções para o campo
					if (isset ( $this->_footerConfig [$fcount] )) {
						$table .= "<th class='{$this->_footerConfig[$fcount]['htmlClass']}'>
			                         {$this->_footerConfig[$fcount]['valor']}
		                          </th>";
					} else {
						if ($this->_showFooterEmpty)
							$table .= '<th>&nbsp;</th>';
						else
							$table .= '<th>' . $value . '</th>';
					}
					
					$fcount ++;
				}
				$table .= '	</tr>';
				$table .= '</tfoot>';
			}
			
			$table .= ' </table>';
		} else {
			$table = 'Nenhum registro encontrado.';
		}
		return $table;
	}
	
	/**
	 * Adiciona o campo na tabela.
	 *
	 * @param string $campoBd
	 *        	Nome do campo no banco de dados
	 * @param string $campoTitulo
	 *        	Nome que será mostrado para o campo
	 * @param array $options
	 *        	array('opcao' => 'valor')
	 * @param float $width
	 *        	Tamanho da coluna em %
	 */
	public function addCampo($campoBd, $campoTitulo, $options = null, $width = null) {
		$this->_campoBd [] = $campoBd;
		$this->_campoTitulo [] = $campoTitulo;
		$this->_campoOpcoes [$campoBd] = $options;
		if (! empty ( $width ) && is_numeric ( $width ))
			$this->_campoWidth [$campoBd] = $width;
	}
	
	/**
	 * O índice começa á partir do 0.
	 *
	 * @param integer $index        	
	 * @param string $value        	
	 * @param string $htmlClass        	
	 */
	public function setFooter($index, $value, $htmlClass) {
		$this->_footerConfig [$index] = array (
				'valor' => $value,
				'htmlClass' => $htmlClass 
		);
	}
	
	/**
	 * Formata o valor da célula de acordo com as opções informadas
	 *
	 * @param
	 *        	$value
	 * @param
	 *        	$options
	 * @param s $colWidth        	
	 * @return string $valor
	 */
	protected function formatCell($value, $options, $colWidth) {
		if (! empty ( $options )) {
			$type = (isset ( $options ['type'] ) ? $options ['type'] : '');
			$class = (isset ( $options ['class'] ) ? 'class="' . $options ['class'] . '"' : '');
			$number_format = (isset ( $options ['number_format'] ) ? $options ['number_format'] : array ());
			$condition = (isset ( $options ['condition'] ) ? $options ['condition'] : array ());
			$width = (isset ( $options ['width'] ) ? 'width="' . $options ['width'] . '"' : '');
			
			switch ($type) {
				case 'currency' :
					$value = 'R$ ' . number_format ( $value, 2, ',', '.' );
					break;
				case 'number_format' :
					$numDecimals = $number_format [0];
					$sepDecimal = $number_format [1];
					$sepMil = $number_format [2];
					$value = number_format ( $value, $numDecimals, $sepDecimal, $sepMil );
					break;
			}
			
			// verifica se existe condição para formatar o valor
			if (count ( $condition ) > 0) {
				// verifica se o valor existe na condição
				if (array_key_exists ( $value, $condition )) {
					$value = $condition [$value];
				}
			}
			
			if (empty ( $width ))
				$width = $colWidth;
			
			return "<td {$width} {$class}>{$value}</td>";
		}
		
		if (empty ( $colWidth ))
			return "<td>$value</td>";
		else
			return "<td {$colWidth} >$value</td>";
	}
	public function showHeader($flag) {
		$this->_showHeader = ( boolean ) $flag;
	}
	public function showFooter($flag) {
		$this->_showFooter = $flag;
	}
	public function showFooterEmpty($flag) {
		$this->_showFooterEmpty = $flag;
	}
	
	/**
	 * Link para editar o registro
	 * 
	 * @param string $_editLink        	
	 */
	public function setEditLink($_editLink) {
		$this->_editLink = $_editLink;
	}
	
	/**
	 * Altera a classe da tag <a>
	 * 
	 * @param
	 *        	$class
	 */
	public function setEditHrefClass($class) {
		$this->_editHrefClass = $class;
	}
	
	/**
	 * Altera a classe da tag <a>
	 * 
	 * @param
	 *        	$class
	 */
	public function setDeleteHrefClass($class) {
		$this->_deleteHrefClass = $class;
	}
	
	/**
	 * Link para excluir o registro
	 * 
	 * @param string $_deleteLink        	
	 */
	public function setDeleteLink($_deleteLink) {
		$this->_deleteLink = $_deleteLink;
	}
	
	/**
	 * Insere um parametro para a url de alteração.
	 * Para informar um valor de um campo do banco, informe apenas 1º parametro com o nome do campo
	 * Para informar um valor fixo, informe 1º parametro com o valor desejado e o 2º parametro setado como true
	 *
	 * @param string $value        	
	 * @param boolean $static
	 *        	(opcional)
	 * @param string $paramName
	 *        	(opcional) Nome do parametro na url. Ex: 'url/parametro/valor'
	 */
	public function addEditParam($value, $static = false, $paramName = null) {
		$this->_editParams [] = array (
				'value' => $value,
				'static' => $static,
				'paramName' => $paramName 
		);
	}
	
	/**
	 * Insere um parametro para a url de exclusão.
	 * Para informar um valor de um campo do banco, informe apenas 1º parametro com o nome do campo
	 * Para informar um valor fixo, informe 1º parametro com o valor desejado e o 2º parametro setado como true
	 *
	 * @param string $value        	
	 * @param boolean $static        	
	 */
	public function addDeleteParam($value, $static = false) {
		$this->_deleteParams [] = array (
				'value' => $value,
				'static' => $static 
		);
	}
	
	/**
	 * Retorna uma string com os parametros separados por /
	 * 
	 * @param array $linha        	
	 * @return string
	 */
	public function editParamsToUrl(array $linha) {
		if (count ( $this->_editParams > 0 )) {
			$values = array ();
			foreach ( $this->_editParams as $param ) {
				if ($param ['static']) {
					if (! empty ( $param ['paramName'] )) {
						$values [] = $param ['paramName'] . '/' . $param ['value'];
					} else {
						$values [] = $param ['value'];
					}
				} else {
					if (! empty ( $param ['paramName'] )) {
						$values [] = $param ['paramName'] . '/' . $linha [$param ['value']];
					} else {
						$values [] = $linha [$param ['value']];
					}
				}
			}
			return implode ( '/', $values );
		} else {
			return '';
		}
	}
	
	/**
	 * Retorna uma string com os parametros separados por /
	 * 
	 * @param array $linha        	
	 * @return string
	 */
	public function deleteParamsToUrl(array $linha) {
		if (count ( $this->_deleteParams > 0 )) {
			$values = array ();
			foreach ( $this->_deleteParams as $param ) {
				if ($param ['static']) {
					$values [] = $param ['value'];
				} else {
					$values [] = $linha [$param ['value']];
				}
			}
			return implode ( '/', $values );
		} else {
			return '';
		}
	}
	protected function paramsToUrlAsDataTag(array $linha) {
		if (count ( $this->_deleteParams > 0 )) {
			$values = array ();
			$paramCount = 0;
			foreach ( $this->_deleteParams as $param ) {
				$paramCount ++;
				if ($param ['static']) {
					$values [] = 'data-' . $paramCount . '="' . $param ['value'] . '"';
				} else {
					$values [] = 'data-' . $param ['value'] . '="' . $linha [$param ['value']] . '"';
				}
			}
			return implode ( ' ', $values );
		} else {
			return '';
		}
	}
	
	/**
	 * Icone para editar o registro
	 * 
	 * @param string $_editIcon        	
	 */
	public function setEditIcon($_editIcon) {
		$this->_editIcon = $_editIcon;
	}
	
	/**
	 * Icone para exlcuir o registro
	 * 
	 * @param string $_deleteIcon        	
	 */
	public function setDeleteIcon($_deleteIcon) {
		$this->_deleteIcon = $_deleteIcon;
	}
	
	/**
	 * Habilita mostrar/esconder icone para editar
	 * 
	 * @param boolean $flag        	
	 */
	public function setShowEditIcon($flag) {
		if (is_bool ( $flag ))
			$this->_showEditIcon = $flag;
	}
	
	/**
	 * Habilita mostrar/esconder icone para excluir
	 * 
	 * @param boolean $flag        	
	 */
	public function setShowDeleteIcon($flag) {
		if (is_bool ( $flag ))
			$this->_showDeleteIcon = $flag;
	}
	
	/**
	 * Habilita opção de editar o registro
	 * 
	 * @param boolean $flag        	
	 */
	public function setAllowEdit($flag) {
		if (is_bool ( $flag ))
			$this->_allowEdit = $flag;
	}
	
	/**
	 * Habilita opção de excluir o registro
	 * 
	 * @param boolean $flag        	
	 */
	public function setAllowDelete($flag) {
		if (is_bool ( $flag ))
			$this->_allowDelete = $flag;
	}
	
	/**
	 * Link para baixar arquivo
	 * 
	 * @param string $_downloadLink        	
	 */
	public function setDownloadLink($_downloadLink) {
		$this->_downloadLink = $_downloadLink;
	}
	
	/**
	 * Altera a classe da tag <a>
	 * 
	 * @param
	 *        	$class
	 */
	public function setDownloadHrefClass($class) {
		$this->_downloadHrefClass = $class;
	}
	
	/**
	 * Insere um parametro para a url de download.
	 * Para informar um valor de um campo do banco, informe apenas 1º parametro com o nome do campo
	 * Para informar um valor fixo, informe 1º parametro com o valor desejado e o 2º parametro setado como true
	 *
	 * @param string $value        	
	 * @param boolean $static        	
	 */
	public function addDownloadParam($value, $static = false) {
		$this->_dowloadParams [] = array (
				'value' => $value,
				'static' => $static 
		);
	}
	
	/**
	 * Icone para baixar o arquivo
	 * 
	 * @param string $_downloadIcon        	
	 */
	public function setDownloadIcon($_downloadIcon) {
		$this->$_downloadIcon = $_downloadIcon;
	}
	
	/**
	 * Habilita mostrar/esconder icone para baixar o arquivo
	 * 
	 * @param boolean $flag        	
	 */
	public function setShowDownloadIcon($flag) {
		if (is_bool ( $flag ))
			$this->_showDownloadIcon = $flag;
	}
	
	/**
	 * Habilita opção de baixar o arquivo
	 * 
	 * @param boolean $flag        	
	 */
	public function setAllowDownload($flag) {
		if (is_bool ( $flag ))
			$this->_allowDownload = $flag;
	}
	
	/**
	 * Retorna uma string com os parametros separados por /
	 * 
	 * @param array $linha        	
	 * @return string
	 */
	public function downloadParamsToUrl(array $linha) {
		if (count ( $this->_dowloadParams > 0 )) {
			$values = array ();
			foreach ( $this->_dowloadParams as $param ) {
				if ($param ['static']) {
					$values [] = $param ['value'];
				} else {
					$values [] = $linha [$param ['value']];
				}
			}
			return implode ( '/', $values );
		} else {
			return '';
		}
	}
	public function setCampoWidthMedida($TipoMedida) {
		if ($TipoMedida == '%' || $TipoMedida == 'px') {
			$this->_campoWidthMedida = $TipoMedida;
		} else {
			throw new \Exception ( "Tipo de medida '{$TipoMedida}' não permitido." );
		}
	}
}