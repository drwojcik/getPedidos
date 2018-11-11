<?php

/**
 * Classe responsável por conter os métodos
 * de consulta no banco
 * @author Diego Wojcik <diegorafael85@gmail.com>
 * @since 2018-11-11
 */
namespace Basico\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Jlib\View\Html\Tabela\Tabela;
use Jlib\Util\FormatValue;
use Jlib\Util\Crypt\CryptRC4;

class UsuarioRepository extends EntityRepository
{
    
    public function findByLoginAndPassword($login, $password){
        $funcionario = $this->findOneBy(array('Login' => $login));
        if ($funcionario){
           // $encPassword = $funcionario->encryptPassword($password);
            
            if ($funcionario->getSenha() == $password){
                return $funcionario;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
  
    public function findByPassword($password){
    	$cryptRC4 = new CryptRC4($password);
    	$encPassword =  $cryptRC4->encript();
        $usuario = $this->findOneBy(array('Senha' => $encPassword));
        if ($usuario){
            return $usuario;
        } else {
            return false;
        }
    }
   
   
}

?>