<?php
/**
 * Classe criada contendo funções para auxiliar a manipulação de arquivos de texto
 * @author Diego Wojcik <diego@softwar.com.br>
 * @since 2015-06-22
 */

namespace Jlib\Util;

class UtilFile {
	

	/**
	 * Limita Strings para preencher campos com caracteres contados, e preenche com brancos(' ') ou zeros(0) o que faltar
	 * @param unknown $palavra
	 * @param unknown $limite
	 * @return string
	 * @example limit("Softwar", 30, "brancos")
	 */
	public function limit($palavra,$limite, $tipo, $ladoEsquerdo = false)	{
		$tamanho = mb_strlen ($palavra, 'utf8');
		$tamanhoa = strlen($palavra);
		if($tamanho >= $limite)	{
			$var = substr($palavra, 0,$limite);
		}
	
		else {
			$max = (int)($limite-$tamanho);
			if($ladoEsquerdo){
				$var = $this->complementoRegistro($max, $tipo).$palavra;
			}else{
				$var = $palavra.$this->complementoRegistro($max, $tipo);
			}
			
		}
	
		return $var;
	
	}
	
	/**
	 * Auxiliar que preenche o faltante dos espaços
	 * @param unknown $int
	 * @param unknown $tipo
	 * @return string
	 */
	public function complementoRegistro($int, $tipo){
		if($tipo == "zeros"){
			$space = '';
			for($i = 1; $i <= $int; $i++){
				$space .= '0';
			}
		} else if($tipo == "brancos"){
				$space = '';
				for($i = 1; $i <= $int; $i++){
					$space .= ' ';
				}
		
		}
		return $space;
	}
	/**
	 * Gera Numero Sequencial com zeros na frente
	 * @param unknown $i
	 * @return string
	 */
	public function sequencial($i){
		if($i < 10)		
			return $this->zeros(0,4).$i;
		else if($i >= 10 && $i < 100)
			return $this->zeros(0,3).$i;
		else if($i >= 100 && $i < 1000)
			return $this->zeros(0,2).$i;
		else if($i >= 1000 && $i < 10000)
			return $this->zeros(0,1).$i;
		else if($i >= 10000 && $i < 100000)
			return $this->zeros(0,0).$i;
	}
	
	/**
	 * Gera Numero Sequencial de Lotecom zeros na frente
	 * @param unknown $i
	 * @return string
	 */
	public function sequencialLote($i){
		if($i < 10)
			return $this->zeros(0,4).$i;
		else if($i >= 10 && $i < 100)
			return $this->zeros(0,3).$i;
		else if($i >= 100 && $i < 1000)
			return $this->zeros(0,2).$i;
		else if($i >= 1000 && $i < 10000)
			return $this->zeros(0,1).$i;
		else if($i >= 10000 && $i < 100000)
			return $this->zeros(0,0).$i;
	}
	/**
	 * Preenche com Zeros (Usado na função sequencial)
	 * @param unknown $min
	 * @param unknown $max
	 * @return string
	 */
	
	public function zeros($min,$max){
		$zeros = '';
		$x = ($max - strlen($min));
		
		for($i = 0; $i < $x; $i++){
			$zeros .= '0';
		}
				return $zeros.$min;
		
	}
	/**
	 * 
	 * @author Diego Wojcik <diego@softwar.com.br>
	 * @since 04/11/2016
	 * @param unknown $str
	 * @return mixed
	 */
	function limpaString($str) {
		$str = preg_replace('/[áàãâä]/ui', 'a', $str);
		$str = preg_replace('/[ÁÀÂÃÄ]/ui', 'A', $str);
		$str = preg_replace('/[éèêë]/ui', 'e', $str);
		$str = preg_replace('/[ÉÈÊË]/ui', 'E', $str);
		$str = preg_replace('/[íìîï]/ui', 'i', $str);
		$str = preg_replace('/[ÍÌÎÏ]/ui', 'I', $str);
		$str = preg_replace('/[óòõôö]/ui', 'o', $str);
		$str = preg_replace('/[ÓÒÕÔÖ]/ui', 'O', $str);
		$str = preg_replace('/[úùûü]/ui', 'u', $str);
		$str = preg_replace('/[ÚÙÛÜ]/ui', 'U', $str);
		$str = preg_replace('/[ç]/ui', 'c', $str);
		$str = preg_replace('/[Ç]/ui', 'C', $str);
		$str = preg_replace('/[´]/ui', ' ', $str);
		$str = preg_replace('/[°]/ui', ' ', $str);
		$str = preg_replace('/[ª]/ui', ' ', $str);
		$str = preg_replace('/[\']/ui', ' ', $str);
		// $str = preg_replace('/[,(),;:|!"#$%&/=?~^><ªº-]/', '_', $str); //remove outros caracteres
		//$str = preg_replace('/[^a-z0-9]/i', '_', $str); //remove numeros
		//$str = preg_replace('/_+/', '_', $str); // remove brancos :)
		return $str;
	}
	/**
	 * Funções que convertem de barras para linhas digitavel(IPTE ) e VICE VERSA
	 * @author Diego Wojcik <diego@sofwar.com.br>
	 * @param unknown $barras
	 * @return string
	 * 
	 */
	
	public function calcIpte($barras) {
		if (!empty($barras)) {
			return $this -> barrasToIpte($this -> calcVerificadorBarras($barras));
	/*	} else if (!empty($this -> codBanco) && !empty($this -> codMoeda) && !empty($this -> fatorVencimento) && !empty($this -> valorDocumento)) {
			return $this -> barrasToIpte($this -> calcVerificadorBarras($this -> montaBarras()));*/
		} else {
			//return "Não existem dados suficientes para calcular o IPTE (Você deve preencher os dados: Código do banco, código da moeda, vencimento e valor do documento, ou o código de barras)";
			return 0;
		}
	}
	
	public function calcBarras($ipte) {
		if ($ipte) {
			return $this -> ipteToBarras($ipte);
		} else {
			$this -> montaBarras();
		}
	}
	
	private function calcVerificadorBarras($barras) {
		$barras = substr_replace($barras, "0", 4, 1);
		$fatores = array(4, 3, 2, 9, 0, 8, 7, 6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2);
		
		$soma = 0;
		for ($i = 0; $i < sizeof($fatores); $i++) {
			$soma += $barras[$i] * $fatores[$i];
		}
		$mod = $soma % 11;
		$resultado = 11 - $mod;
		$this -> codVerificador = $resultado;
		$barras = substr_replace($barras, $resultado, 4, 1);
		
		return $barras;
	}
	
	private function calcDtVencimento($fator) {
		// $base = new Date("07/10/1997");
		$this -> dtVencimento = date('Y-m-d', strtotime("1997-10-07" . ' + ' . $fator . ' days'));
	}
	
	
	/* ================ CONVERSÕES ===================== */
	
	private function barrasToIpte($barras) {
		$campo1 = substr($barras, 0, 4) . substr($barras, 19, 1) . '.' . substr($barras, 20, 4);
		
		$campo2 = substr($barras, 24, 5) . '.' . substr($barras, 24 + 5, 5);
		
		$campo3 = substr($barras, 34, 5) . '.' . substr($barras, 34 + 5, 5);
		
		$campo4 = substr($barras, 4, 1);
		// Digito verificador
		$campo5 = substr($barras, 5, 14);
		// Vencimento + Valor
		//
		if ($campo5 == 0)
			$campo5 = '000';
			//
			$ipte = $campo1 . $this -> verificadorIPTE($campo1) . ' ' . $campo2 . $this -> verificadorIPTE($campo2) . ' ' . $campo3 . $this -> verificadorIPTE($campo3) . ' ' . $campo4 . ' ' . $campo5;
			
			// linha = campo1 + verificadorIPTE(campo1) + ' ' + campo2 + verificadorIPTE(campo2) + ' ' + campo3 + verificadorIPTE(campo3) + ' ' + campo4 + ' ' + campo5;
			//if (form.linha.value != form.linha2.value) alert('Linhas diferentes');
			return $ipte;
	}
	
	private function ipteToBarras($ipte) {
		//$barras = substr($ipte, 0, 4) . substr($ipte, 38, 1) . substr($ipte, 40, 14) . substr($ipte, 4, 1) . substr($ipte, 6, 4) . str_replace(".", "", substr($ipte, 12, 11)) . str_replace(".", "", substr($ipte, 25, 9)) . substr($ipte, 34, 2);
		$barras = substr($ipte, 0, 4).substr($ipte,32,15).substr($ipte,4,5).substr($ipte,10,10).substr($ipte,21,10);
		
		return $barras;
	}
	
	/* ========== MONTAR BARRAS ================ */
	private function montaBarras() {
		if (!empty($this -> codBanco) && !empty($this -> codMoeda) && !empty($this -> fatorVencimento) && !empty($this -> valorDocumento)) {
			$diferenca = 10 - strlen($this -> valorDocumento);
			
			for ($i = 0; $i < $diferenca; $i++) {
				$this -> valorDocumento = "0" . $this -> valorDocumento;
			}
			
			$barras = $this -> codBanco . $this -> codMoeda . "X" . $this -> fatorVencimento . $this -> valorDocumento . "000000" . $this -> nossoNumero . $this -> carteira;
			
			$this -> barras = $this -> calcVerificadorBarras($barras);
		} else {
			return "Não existe código de barras ainda";
		}
		
	}
	
	//Caucula veririficadores do IPTE
	private function verificadorIPTE($numero) {
		$numero = str_replace(".", "", $numero);
		$soma = 0;
		$peso = 2;
		$contador = strlen($numero) - 1;
		while ($contador >= 0) {
			$multiplicacao = substr($numero, $contador, 1) * $peso;
			if ($multiplicacao >= 10) {
				$multiplicacao = 1 + ($multiplicacao - 10);
			}
			$soma = $soma + $multiplicacao;
			if ($peso == 2) {
				$peso = 1;
			} else {
				$peso = 2;
			}
			$contador = $contador - 1;
		}
		$digito = 10 - ($soma % 10);
		if ($digito == 10)
			$digito = 0;
			return $digito;
	}
	//Remove o digito verificador do codigo de barras para enviar os 44 digitos
	public function removeDigitoVerificador($linhadigitavel){
		$parte1 = substr($linhadigitavel, 0, 11);
		$parte2 = substr($linhadigitavel, 12, 11);
		$parte3 = substr($linhadigitavel, 24, 11);
		$parte4 = substr($linhadigitavel, 36, 11);
		$barras = $parte1.$parte2.$parte3.$parte4;
		return $barras;
	}
	
	
	
}