<?php
namespace Basico\Auth;

use Zend\Authentication\Adapter\AdapterInterface,
	Zend\Authentication\Result;

use Doctrine\ORM\EntityManager;

class Adapter implements AdapterInterface {

    /**
     * @var EntitiManager
     */
    protected $_em;
    
    protected $username;
    
    protected $password;
    
    
    public function __construct(EntityManager $em){
        $this->_em = $em;
    }
    
	public function authenticate(){
	    $repository = $this->_em->getRepository('Basico\Entity\Usuario');
	    $funcionario = $repository->findByLoginAndPassword($this->getUsername(), $this->getPassword());
	    
	    if ($funcionario){
	        return new Result(Result::SUCCESS, array('funcionario' => $funcionario), array('OK'));
	    } else {
	        return new Result(Result::FAILURE_CREDENTIAL_INVALID, null, array());
	    }
	}
    
	/**
	 * Getters e Setters
	 */	
	public function getUsername ()
    {
        return $this->username;
    }

	public function getPassword ()
    {
        return $this->password;
    }

	public function setUsername ($username)
    {
        $this->username = $username;
    }

	public function setPassword ($password)
    {
        $this->password = $password;
    }
    
}

?>