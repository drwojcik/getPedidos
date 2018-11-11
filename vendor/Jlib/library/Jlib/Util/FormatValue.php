<?php

namespace Jlib\Util;

class FormatValue {
	
	// Currency
	public $_currencySymbol = 'R$';
	public $_currencyNumDecimals = 2;
	public $_currencySeparatorDec = ',';
	public $_currencySeparatorMil = '.';
	
	// Date
	public $_dateFormatToView = 'd/m/Y';
	public $_dateFormatToSave = 'Y-m-d';
	
	// Hour
	public $_hourFormatToView = 'H:i';
	public $_hourFormatToSave = 'H:i:s';
	
	// DateTime
	public $_dateTimeFormatToView = 'd/m/Y H:i:s';
	public $_dateTimeFormatToSave = 'Y-m-d H:i:s';
	
	public function mask($val, $mask){
		 $maskared = '';
		 $k = 0;
		 for($i = 0; $i<=strlen($mask)-1; $i++)
		 {
			 if($mask[$i] == '#')
			 {
			 if(isset($val[$k]))
				 $maskared .= $val[$k++];
			 }
			 else
			 {
			 if(isset($mask[$i]))
			 	$maskared .= $mask[$i];
			 }
		 }
		 return $maskared;
	}


	
	
	public function formatCurrencyToView($value) {
		if (empty ( $value ))
			$value = 0;
	
		return $this->_currencySymbol . ' ' . number_format ( $value, $this->_currencyNumDecimals, $this->_currencySeparatorDec, $this->_currencySeparatorMil );
	}
	
	public function formatCurrencyToSave($value) {
		if (empty ( $value )) {
			return $value;
		} else {
			$value = str_replace ( $this->_currencySymbol, '', $value );
			$value = str_replace ( '.', '', $value );
			$value = str_replace ( ',', '.', $value );
			return $value;
		}
	}
	public function formatDateToView($value) {
		if (empty ( $value )) {
			return '';
		} else {
			if ($value instanceof \DateTime) {
				return $value->format ( $this->_dateFormatToView );
			} else {
				throw new \Exception ( 'A data informada precisa ser um objeto do tipo DateTime.' );
			}
		}
	}
	
	/**
	 * Converte a data de dd/mm/yyyy para DateTime
	 *
	 * @param
	 *        	$value
	 * @return \DateTime
	 */
	public function formatDateToSave($value) {
		$value = str_replace ( ' ', '', $value );
		$date = substr ( $value, 6, 4 );
		$date .= '-' . substr ( $value, 3, 2 );
		$date .= '-' . substr ( $value, 0, 2 );
		return new \DateTime ( $date );
	}
	
	/**
	 * Converte uma string d/m/Y H:i:s para Datetime
	 *
	 * @param string $value
	 *        	d/m/Y H:i:s
	 * @return \DateTime
	 */
	public function formatDateTimeToSave($value) {
		if (! empty ( $value )) {
			$date = substr ( $value, 6, 4 );
			$date .= '-' . substr ( $value, 3, 2 );
			$date .= '-' . substr ( $value, 0, 2 );
			$date .= ' ' . substr ( $value, 11, 8 );
			return new \DateTime ( $date );
		} else {
			return $value;
		}
	}
	/**
	 * Converte uma string d/m/Y H:i:s para Y-m-d H:i:s
	 *
	 * @param string $value
	 *        	d/m/Y H:i:s
	 * @return \DateTime
	 */
	public function formatDateTimeToSaveString($value) {
		if (empty ( $value )) {
			return '';
		} else {
			if ($value instanceof \DateTime) {
				return $value->format ( $this->_dateTimeFormatToSave );
			} else {
				throw new \Exception ( 'A data informada precisa ser um objeto do tipo DateTime.' );
			}
		}
	}
	
	/**
	 * Converte um objeto datetime para o formato de visualização d/m/Y H:i:s
	 *
	 * @param \Datetime $value        	
	 * @throws \Exception
	 */
	public function formatDateTimeToView($value) {
		if (empty ( $value )) {
			return '';
		} else {
			if ($value instanceof \DateTime) {
				return $value->format ( $this->_dateTimeFormatToView );
			} else {
				throw new \Exception ( 'A data informada precisa ser um objeto do tipo DateTime.' );
			}
		}
	}
	
	/**
	 * Formata a data para o formato de saída informado.
	 * Formatos de entrada suportados (d/m/Y, Y-m-d)
	 *
	 * @param string $date        	
	 * @param string $inputFormat        	
	 * @param string $outputFormat        	
	 */
	public function formatDate($date, $inputFormat, $outputFormat) {
		if ($inputFormat == 'd/m/Y') {
			$dataObj = $this->formatDateToSave ( $date );
			return $dataObj->format ( $outputFormat );
		} elseif ($inputFormat == 'Y-m-d') {
			$dataY = substr ( $date, 0, 4 );
			$dataM = substr ( $date, 5, 2 );
			$dataD = substr ( $date, 8, 2 );
			
			$dataObj = new \DateTime ( $dataY . '-' . $dataM . '-' . $dataD );
			return $dataObj->format ( $outputFormat );
		} else {
			throw new \Exception ( 'Formato de entrada não suportado.' );
		}
	}
	
	/**
	 * Retorna uma data no formato de hora.
	 *
	 * @param \DateTime $date        	
	 * @param boolean $showSeconds
	 *        	default false
	 * @throws \Exception
	 */
	public function formatHourToView($date, $suppressSeconds = false) {
		if (empty ( $date )) {
			return '00:00';
		} else {
			if ($date instanceof \DateTime) {
				if ($suppressSeconds)
					return $date->format ( $this->_hourFormatToView );
				else
					return $date->format ( $this->_hourFormatToSave );
			} else {
				throw new \Exception ( 'A data informada precisa ser um objeto do tipo DateTime.' );
			}
		}
	}
	
	/**
	 * Remove os caracteres do campo.
	 * Caracteres removidos: . - / \ ( )
	 *
	 * @param string $field        	
	 * @param array $characters
	 *        	(opcional) Array com os caracteres adicionais que precisam ser removidos
	 * @return string
	 */
	public function cleanField($field, array $characters = array()) {
		$search = array (
				'.',
				'-',
				'/',
				'\\',
				'(',
				')',
				' ' 
		);
		
		if (count ( $characters )) {
			foreach ( $characters as $character ) {
				$search [] = $character;
			}
		}
		
		$field = str_replace ( $search, '', $field );
		return $field;
	}
	
	/**
	 * Retorna o campo em formato decimal.
	 *
	 * @param decimal $value
	 *        	Valor a ser formatado
	 * @param int $numDec
	 *        	Numero de casas decimais
	 * @param char $sepDec
	 *        	Separador de decimal
	 * @param char $sepMil
	 *        	Separador de milhar
	 */
	public function formatDecimal($value, $numDec = null, $sepDec = null, $sepMil = null) {
		if (empty ( $numDec ))
			$numDec = $this->_currencyNumDecimals;
		
		if (empty ( $sepDec ))
			$sepDec = $this->_currencySeparatorDec;
		
		if (empty ( $sepMil ))
			$sepMil = $this->_currencySeparatorMil;
		
		return number_format ( $value, $numDec, $sepDec, $sepMil );
	}
	
	public function formatDecimalNoMil($value, $numDec = null, $sepDec = null) {
		if (empty ( $numDec ))
			$numDec = $this->_currencyNumDecimals;
	
		if (empty ( $sepDec ))
			$sepDec = $this->_currencySeparatorDec;
	
		return number_format ( $value, $numDec, $sepDec ,'');
	}
	
	/**
	 * Prepara um campo decimal para salvar no banco de dados.
	 *
	 * @param string $value
	 *        	(valor decimal à ser formatado)
	 * @return float
	 */
	public function formatDecimalToSave($value) {
		if (strpos ( $value, "R$" ) !== false) {
			// valor monetario
			return $this->formatCurrencyToSave ( $value );
		} else if (strpos ( $value, "," ) !== false) {
			$valueFormat = str_replace ( '.', '', $value );
			$valueFormat = str_replace ( ',', '.', $valueFormat );
			
			return ( float ) $valueFormat;
		} else {
			return ( float ) $value;
		}
	}

	public function valorPorExtenso( $valor = 0, $bolExibirMoeda = true, $bolPalavraFeminina = false )
	{
	
		$valor = self::formatCurrencyToSave( $valor );
	
		$singular = null;
		$plural = null;
	
		if ( $bolExibirMoeda )
		{
			$singular = array("centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
			$plural = array("centavos", "reais", "mil", "milhões", "bilhões", "trilhões","quatrilhões");
		}
		else
		{
			$singular = array("", "", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
			$plural = array("", "", "mil", "milhões", "bilhões", "trilhões","quatrilhões");
		}
	
		$c = array("", "cem", "duzentos", "trezentos", "quatrocentos","quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
		$d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta","sessenta", "setenta", "oitenta", "noventa");
		$d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze","dezesseis", "dezesete", "dezoito", "dezenove");
		$u = array("", "um", "dois", "três", "quatro", "cinco", "seis","sete", "oito", "nove");
	
	
		if ( $bolPalavraFeminina )
		{
	
			if ($valor == 1)
			{
				$u = array("", "uma", "duas", "três", "quatro", "cinco", "seis","sete", "oito", "nove");
			}
			else
			{
				$u = array("", "um", "duas", "três", "quatro", "cinco", "seis","sete", "oito", "nove");
			}
	
	
			$c = array("", "cem", "duzentas", "trezentas", "quatrocentas","quinhentas", "seiscentas", "setecentas", "oitocentas", "novecentas");
	
	
		}
	
	
		$z = 0;
	
		$valor = number_format( $valor, 2, ".", "." );
		$inteiro = explode( ".", $valor );
	
		for ( $i = 0; $i < count( $inteiro ); $i++ )
		{
			for ( $ii = strlen( $inteiro[$i] ); $ii < 3; $ii++ )
			{
				$inteiro[$i] = "0" . $inteiro[$i];
			}
		}
	
		// $fim identifica onde que deve se dar junção de centenas por "e" ou por "," ;)
		$rt = null;
		$fim = count( $inteiro ) - ($inteiro[count( $inteiro ) - 1] > 0 ? 1 : 2);
		for ( $i = 0; $i < count( $inteiro ); $i++ )
		{
			$valor = $inteiro[$i];
			$rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
			$rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
			$ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";
	
			$r = $rc . (($rc && ($rd || $ru)) ? " e " : "") . $rd . (($rd && $ru) ? " e " : "") . $ru;
			$t = count( $inteiro ) - 1 - $i;
			$r .= $r ? " " . ($valor > 1 ? $plural[$t] : $singular[$t]) : "";
			if ( $valor == "000")
				$z++;
			elseif ( $z > 0 )
			$z--;
	
			if ( ($t == 1) && ($z > 0) && ($inteiro[0] > 0) )
				$r .= ( ($z > 1) ? " de " : "") . $plural[$t];
	
			if ( $r )
				$rt = $rt . ((($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " e ") : " ") . $r;
		}
	
		$rt = substr( $rt, 1 );
	
		return($rt ? trim( $rt ) : "zero");
	
	}
	

	/**
	 * Ordena um array com datas (formato brasileiro) asc.
	 *
	 *
	 * @param array $datas
	 * @return array $datas
	 */
	public function ordenaDatasArray($array)
	{
		$udatas = array();
		$datas = array();
			
		foreach ($array as $ar)
		{
			$udatas[] =  $this->formatDateToSave($ar);
		}
			
		asort ($udatas);
			
		foreach ($udatas as $dt)
		{
			$datas[] =  $this->formatDateToView($dt);
		}
		return $datas;
	}
	
	/**
	 * Converte a data de dd/mm/yyyy para  yyyy-mm-dd
	 *
	 * @param $value
	 *        	
	 * @return string
	 */
	public function formatDataBRtoSQL($value) {
		$value = str_replace ( ' ', '', $value );
		$date = substr ( $value, 6, 4 );
		$date .= '-' . substr ( $value, 3, 2 );
		$date .= '-' . substr ( $value, 0, 2 );
		return $date;
	}

	public function validaDataBR($dat){
        $data = explode("/","$dat"); // fatia a string $dat em pedados, usando / como referência
        $d = $data[0];
        $m = $data[1];
        $y = $data[2];

        // verifica se a data é válida!
        // 1 = true (válida)
        // 0 = false (inválida)
        if(!$dat){
            return false;
        }
        $res = checkdate($m,$d,$y);
        if ($res == 1){
            return true;
        } else {
            return false;
        }
    }
  
    public function validaHora($dat){
        $data = explode(":","$dat"); // fatia a string $dat em pedados, usando / como referência
        $h = $data[0];
        $m = $data[1];

        if(strlen($h) == 1){
            $h = '0'.$h;
        }
        $hora = $h.':'.$m;
        return $hora;
    }

    function tituloCamelCase($string, $delimiters = array(" ", "-", ".", "'", "O'", "Mc"), $exceptions = array("de", "da", "dos", "das", "do", "I", "II", "III", "IV", "V", "VI"))
    {
        /*
         * Exceptions in lower case are words you don't want converted
         * Exceptions all in upper case are any words you don't want converted to title case
         *   but should be converted to upper case, e.g.:
         *   king henry viii or king henry Viii should be King Henry VIII
         */
        $string = mb_convert_case($string, MB_CASE_TITLE, "UTF-8");
        foreach ($delimiters as $dlnr => $delimiter) {
            $words = explode($delimiter, $string);
            $newwords = array();
            foreach ($words as $wordnr => $word) {
                if (in_array(mb_strtoupper($word, "UTF-8"), $exceptions)) {
                    // check exceptions list for any words that should be in upper case
                    $word = mb_strtoupper($word, "UTF-8");
                } elseif (in_array(mb_strtolower($word, "UTF-8"), $exceptions)) {
                    // check exceptions list for any words that should be in upper case
                    $word = mb_strtolower($word, "UTF-8");
                } elseif (!in_array($word, $exceptions)) {
                    // convert to uppercase (non-utf8 only)
                    $word = ucfirst($word);
                }
                array_push($newwords, $word);
            }
            $string = join($delimiter, $newwords);
        }//foreach
        return $string;
    }
   
    function DiaSemana($data)
    {  // Traz o dia da semana para qualquer data informada
        $ano =  substr($data,0,4);
        $mes =  substr($data,5,2);
        $dia =  substr($data,8,2);


        $diasemana = date("w", mktime(0,0,0,$ano,$dia,$mes) );
        //TODO phpversion() para validar as versões do php
        switch($diasemana){
            case"0": $diasemana = "Domingo";   break;
            case"1": $diasemana = "Segunda-Feira"; break;
            case"2": $diasemana = "Terça-Feira";   break;
            case"3": $diasemana = "Quarta-Feira";  break;
            case"4": $diasemana = "Quinta-Feira";  break;
            case"5": $diasemana = "Sexta-Feira";   break;
            case"6": $diasemana = "Sábado";   break;
        }
        return $diasemana;
    }


    public function validaSenha($senha){
        $dificuldade = 0;
        /*if(preg_match('/(.*)?([!-//]|[:-@]|[^-`]|~)(.*)?/', $senha){
        // Tem algum caractere especial
        $hard = $hard++;
        }*/
        if(preg_match('/(.*)?[0-9](.*)?/', $senha)){
            // Tem algum número
            $dificuldade++;
        }
        if(preg_match('/(.*)?[a-z](.*)?/', $senha)){
            $dificuldade++;
        }
        if(preg_match('/(.*)?[A-Z](.*)?/', $senha)){
            // Tem alguma letra maiúscula
            $dificuldade++;
        }
        if($dificuldade == 3){
            //Senha permitida
            return true;
        }else{
            //Senha não permitida
            return false;
        }

    }


}