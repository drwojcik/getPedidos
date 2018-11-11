<?php
/**
 * Classe criada contendo funções para auxiliar em validações 
 * @author Diego Wojcik <diego@softwar.com.br>
 * @since 2016-05-10
 */

namespace Jlib\Util;

class UtilValidation {
	
	/**
	 * Valida se Email é valido
	 * @param unknown $email
	 * @return boolean
	 */
   	public function validaEmail($email) {
    	$conta = "/^[a-zA-Z0-9\._-]+@";
    	$domino = "[a-zA-Z0-9\._-]+.";
    	$extensao = "([a-zA-Z]{2,4})$/";
    	$pattern = $conta.$domino.$extensao;
    	if (preg_match($pattern, $email))
    		return true;
    	else
    		return false;
    }	
	
	
	
}