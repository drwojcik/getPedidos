<?php

/**
 * Entidade da tabela "usuario"
 * @author Diego Wojcik <diegorafael85@gmail.com>
 * @since 2018-11-11
 */
namespace Basico\Entity;

use Jlib\Util\Crypt\CryptRC4;

use Basico\Entity\Configurator;

use Doctrine\ORM\Mapping As ORM;
use Jlib\Util\FormatValue;

/**
 * @ORM\Entity
 * @ORM\Table(name="usuario")
 * @ORM\Entity(repositoryClass="Basico\Entity\Repository\UsuarioRepository")
 */
class Usuario
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    protected $idUsuario;
    
    /**
     * @ORM\Column(type="text",name="nome")
     */
    protected $Nome;

    /**
     * @ORM\Column(type="text", name="login")
     */
    protected $Login;
    
    /**
     * @ORM\Column(type="text", name="senha")
     */
    protected $Senha;
    
   

    /**
     * Configura automaticamente os getters e setters
     * @param string $options
     */
    public function __construct($options = null){
        Configurator::configure($this, $options);
    }
    
    public function __toString(){
        return $this->getNome();
    }
    
    public function toArray(){
        return array(
            'idUsuario'	=> $this->getidUsuario(),
                'Nome'				=> $this->getNome(),
                'Login'				=> $this->getLogin(),
                'Senha'				=> $this->getSenha(),
               
        		
		);
    }
	public function toForm(){
    	$dados = $this->toArray();
    	
    	$formatter = new FormatValue();
    
    	return $dados;
    	
    }
    public function toTable(){
    	$data =  $this->toArray();
    	
    	return $data;
    }
    
    public function encryptPassword($password){
        $cryptRC4 = new CryptRC4($password);
        return $cryptRC4->encript();
    }    
	
    /**
     * Getters e Setters
     */

    
    /**
     * @return mixed
     */
    public function getIdUsuario()
    {
        return $this->idUsuario;
    }
    
    /**
     * @return mixed
     */
    public function getNome()
    {
        return $this->Nome;
    }
    
    /**
     * @return mixed
     */
    public function getLogin()
    {
        return $this->Login;
    }
    
    /**
     * @return mixed
     */
    public function getSenha()
    {
        return $this->Senha;
    }
    
    /**
     * @param mixed $idUsuario
     */
    public function setIdUsuario($idUsuario)
    {
        $this->idUsuario = $idUsuario;
    }
    
    /**
     * @param mixed $Nome
     */
    public function setNome($Nome)
    {
        $this->Nome = $Nome;
    }
    
    /**
     * @param mixed $Login
     */
    public function setLogin($Login)
    {
        $this->Login = $Login;
    }
    
    /**
     * @param mixed $Senha
     */
    public function setSenha($Senha)
    {
        $this->Senha = $Senha;
    }
	
	
	
    
    
}

?>