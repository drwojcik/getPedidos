<?php

namespace Jlib\Util\Crypt;

class CryptRC4 {
	protected $_password;
	protected $_passwordEncrypted;
	protected $_sPassPhrase = "aF8fjgXFn8376Yh";
	protected $_sInitVector = "dkjfn834ngofQ";
	public function __construct($password) {
		$this->setPassword ( $password );
	}
	
	/**
	 * Retorna uma string com o password encriptado
	 *
	 * @throws \Exception
	 * @return string
	 */
	public function encript() {
		if (empty ( $this->_password ))
			throw new \Exception ( 'Password não informado.' );
		if (empty ( $this->_sInitVector ))
			throw new \Exception ( 'A variável sInitVector não pode ser vazia.' );
		if (empty ( $this->_sPassPhrase ))
			throw new \Exception ( 'A variável sPassPhrase não pode ser vazia.' );
		
		$cripto = $this->RC4 ( $this->_password, $this->_sPassPhrase, $this->_sInitVector );
		$cripto_encode = $this->criptoEncode ( $cripto );
		$this->_passwordEncrypted = strtoupper ( $cripto_encode );
		
		return $this->_passwordEncrypted;
	}
	protected function RC4($sData, $sPassPhrase, $sInitVector) {
		// make key by appending initVector to passPhrase
		$sKey = $sPassPhrase . $sInitVector;
		
		// get an array of byte from key string
		$KeyArray = $this->GetByteArray ( $sKey );
		
		// get the state array
		$stateArray = $this->State ( $KeyArray );
		
		// get an array of byte from data (message) string
		$dataArray = $this->GetByteArray ( $sData );
		
		// get the (en/de)crypted results
		$sResultArray = $this->Crypt ( $stateArray, $dataArray );
		
		// return results
		$ret = '';
		foreach ( $sResultArray as $element ) {
			$ret .= chr ( $element );
		}
		
		return $ret;
	}
	protected function GetByteArray($myString) {
		// get the string length
		$iStringLength = strlen ( $myString );
		
		// Fill the byte array with each byte in the string
		for($i = 0; $i < $iStringLength; $i ++) {
			$sub = substr ( $myString, $i, 1 );
			$byteArray [$i] = ord ( $sub );
		}
		
		return $byteArray;
	}
	protected function State($KeyArray) {
		// Get the keylength
		$keyLength = count ( $KeyArray );
		
		// Initialize counter to zero
		
		// Fill the state array with 256 values from 0 to 255 in ascending order.
		// Can't use a for loop here because it overflows on the last iteration.
		for($n = 0; $n <= 255; $n ++) {
			$stateArray [$n] = $n;
		}
		
		// Initialize A to zero.
		$a = 0;
		
		// The state array now undergoes 256 mixing operations.
		for($n = 0; $n <= 255; $n ++) {
			// to the variable a, add the value of the n'th element of the key
			// array mod keylength and the n'th element of the State array modulo 256
			$a = ($a + ($stateArray [$n] + $KeyArray [$n % $keyLength])) % 256;
			
			// swap the values of the n'th element of the state array and the
			// a'th element of the state array
			$swap = $stateArray [$n];
			$stateArray [$n] = $stateArray [$a];
			$stateArray [$a] = $swap;
		}
		
		// return results
		return $stateArray;
	}
	protected function Crypt($stateArray, $dataArray) {
		$dataArrayTemp = $dataArray;
		
		// Get data length
		$dataLength = count ( $dataArrayTemp ) - 1;
		
		// Initialize both A and B to zero.
		$a = 0;
		$b = 0;
		
		// Encrypt each element
		for($n = 0; $n <= $dataLength; $n ++) {
			// increment a by one modulo 256
			$a = ($a + 1) % 256;
			
			// increment b by the a'th element in statearray modulo 256
			$b = ($b + $stateArray [$a]) % 256;
			
			// swap the values of the a'th element in stateArray and the
			// b'th element in stateArray
			$swap = $stateArray [$a];
			$stateArray [$a] = $stateArray [$b];
			$stateArray [$b] = $swap;
			
			// index gets the value of the a'th and b'th values of stateArray
			// modulo 256
			$Index = ($stateArray [$a] + $stateArray [$b]) % 256;
			
			// the current data byte gets the value of itself X'ored with
			// the value of the index'th value of stateArray
			$temp = $stateArray [$Index] ^ $dataArrayTemp [$n];
			$dataArrayTemp [$n] = $temp;
		}
		
		// return results
		return $dataArrayTemp;
	}
	protected function criptoEncode($str) {
		$re = "";
		for($i = 0; $i < strlen ( $str ); $i ++) {
			$strHEx = dechex ( ord ( substr ( $str, $i, 1 ) ) );
			
			if (strlen ( $strHEx ) < 2)
				$strHEx = "0" . $strHEx;
				
				// re = re & "%" & strHex
			$re = $re . $strHEx;
		}
		return $re;
	}
	
	/**
	 * Getters e Setters
	 */
	public function getPassword() {
		return $this->_password;
	}
	public function getSPassPhrase() {
		return $this->_sPassPhrase;
	}
	public function getSInitVector() {
		return $this->_sInitVector;
	}
	public function setPassword($_password) {
		$this->_password = $_password;
	}
	public function setSPassPhrase($_sPassPhrase) {
		$this->_sPassPhrase = $_sPassPhrase;
	}
	public function setSInitVector($_sInitVector) {
		$this->_sInitVector = $_sInitVector;
	}
}

?>