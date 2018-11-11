<?php

namespace Basico\Controller;

use Jlib\Ajax\AjaxReturn;

class PesquisaGenericaController extends CrudController {
	
	public function __construct(){
		
		$this->controller			= 'pesquisa-generica';
		$this->route				= 'basico';
		
	}
  
	//Realiza a pesquisa dos Registros a partir dos parâmetros passados e cria a tabela através dos dados informados no filtro
	public function PesquisaAction(){
		
		$response = $this->getResponse();
		$request  = $this->getRequest();
		$ajaxReturn = new AjaxReturn ();
		
		//resgata os parametros
		$urlParams 	= $request->getPost();
		
		//Verifica quais filtros foram informados
		if($urlParams['DadosForm']['Campo1']){
			$Campo1 = $urlParams['DadosForm']['Campo1'];
			$NomeCampo1 = $urlParams['DadosForm']['NomeCampo1'];
		}
		
		if($urlParams['DadosForm']['Campo2']){
			$Campo2 = $urlParams['DadosForm']['Campo2'];
			$NomeCampo2 = $urlParams['DadosForm']['NomeCampo2'];
		}
		
		if($urlParams['DadosForm']['Campo3']){
			$Campo3 = $urlParams['DadosForm']['Campo3'];
			$NomeCampo3 = $urlParams['DadosForm']['NomeCampo3'];
		}
		
		if($urlParams['DadosForm']['ValorReferencia']){
			$Referencia = $urlParams['DadosForm']['ValorReferencia'];
			$CampoReferencia = $urlParams['DadosForm']['ColunaReferencia'];
		}
		
		//Seleciona a tabela e select a partir dos parâmtros passados
		$TabelaPesquisa = $urlParams['DadosForm']['Tabela'];		
		$DataSelect = $urlParams['DadosForm']['DataSelect'];
				
		//Monta a query e verifica quais campos foram preenchidos no filtro para realizar a pesquisa
		$sql = "SELECT TOP(10) {$DataSelect} FROM {$TabelaPesquisa} WHERE 1=1";		
		if($Campo1)
			$sql .= " AND {$NomeCampo1} LIKE '%{$Campo1}%'";		
		if($Campo2)
			$sql .= " AND {$NomeCampo2} LIKE '%{$Campo2}%'";			
		if($Campo3)
			$sql .= " AND {$NomeCampo3} LIKE '%{$Campo3}%'";
		
		if($Referencia && $CampoReferencia)
			$sql .= " AND {$CampoReferencia} = {$Referencia}";
		
		$stmt = $this->getEm()->getConnection()->query($sql);
		$resultado = $stmt->fetchAll();
		
		//Caso não encontre nenhum registro através dos dados informados
		if(empty($resultado)){
			$ajaxReturn->setError('Erro', 'Nenhum registro encontrado');
		}
		
		else {
			
			// lista os registros da tabela e envia para a view
			$TabelaFiltro = $this->getTabelaFiltro($resultado);
			$retorno = $TabelaFiltro;
			
			$ajaxReturn->setSuccess('Sucesso', $this->getMessages());
			$ajaxReturn->setHtml($retorno);
		}

		$response->setContent($ajaxReturn->toJson());
			
		return $response;
		
	}
	
	//Cria a tabela dos registros a partir dos resultados obtidos na pesquisa
	public function getTabelaFiltro($dadosItens){
		
		$strHtml  = '<table id="table_Filtro" class="table-responsive">';
		$strHtml .= '<thead>';
		$strHtml .= '<tr>';
		$strHtml .= '    <th></th>';
		$strHtml .= '    <th>Nome</th>';
		$strHtml .= '</tr>';
		$strHtml .= '</thead>';		
		$strHtml .= '<tbody>';
			
		foreach ( $dadosItens as $Itens ) {
			
			//Concatena os valores do código e nome para separar posteriormente e enviar aos respectivos campos da tela principal
			//após a confirmação
			$checkOpcao = '<input type="radio" name="CodFiltro_chkaprovar" 
								 value="'.$Itens['Codigo'].'[&]'.$Itens['Nome'].'" class="iradio" />';
			

			$strHtml .= '<tr>';
			$strHtml .= "    <td>$checkOpcao</td>";
			
			//Verifica se existe um parâmetro para Info ou se existe um registro na pesquisa
			if($Itens['Info']){
				$strHtml .= "    <td style='vertical-align: middle'>{$Itens['Info']} - {$Itens['Nome']}</td>";
			}else {
				$strHtml .= "    <td style='vertical-align: middle'>{$Itens['Nome']}</td>";
			}
			
			$strHtml .= '</tr>';
		}
		
		$strHtml .= '</tbody>';
		$strHtml .= '</table>';
		
		return $strHtml;
								
	}
	
	public function getTabelaHtml ($where = array()) {
		throw new \Exception('');
	}
}