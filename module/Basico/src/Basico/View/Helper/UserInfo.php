<?php
namespace Basico\View\Helper;

use Basico\Entity\Usuario;
use Basico\Entity\PessoaJuridica;
//use Basico\Service\PegaImagem;
use Basico\Entity\Configurator;
use Basico\Service\AbstractService;
use Basico\Controller\CrudAbstractController;

use Doctrine\ORM\EntityManager;

use Zend\View\Helper\AbstractHelper;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session As SessionStorage;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\Controller\ControllerManager;
use Zend\Mvc\ApplicationInterface;
use Zend\ServiceManager\ServiceLocatorInterface;



class UserInfo extends AbstractHelper {

    private $_usuario;
    
    public function ini($namespace = null, $info = null){
        if (empty($namespace))
            $namespace = 'Main';
        
        $sessionStorage = new SessionStorage($namespace);
        
        $authService = new AuthenticationService();
        $authService->setStorage($sessionStorage);
        if ($authService->hasIdentity()){
            $identity = $authService->getIdentity();
            $this->_usuario = $identity;
            return $this;
        } else {
            return false;
        }
    }
    
    public function __construct($namespace = null, $info = null){
        return $this->ini($namespace, $info);
    }
    
    public function __invoke($namespace = null, $info = null){
        return $this->ini($namespace, $info);
    }
    
    /**
     * Retorna o objeto referente ao usuário logado no sistema.
     */
    public function getObject(){
        if (empty($this->_usuario))
            $this->ini();
        
        if (empty($this->_usuario['usuario']))
            throw new \Exception('Erro ao obter a entity do usuário.');
        
        return $this->_usuario['usuario'];
    }
    
    /**
     * Retorna o código do usuário logado no sistema.
     *
     * @throws \Exception
     */
    public function getCodigo(){
        $objUsuario = $this->_usuario['usuario'];
    
        if ($objUsuario instanceof \Basico\Entity\Usuario)
            return $objUsuario->getidUsuario();
            throw new \Exception('Falha so recuperar o nome do usuário.');
    }
    
    public function getCodCliente(){
        return $this->getCodigo();
    }
    
    public function getCodFuncionario(){
        return $this->getCodigo();
    }
    
    /**
     * Retorna o nome do usuário logado no sistema.
     * 
     * @throws \Exception
     */
    public function getNome(){
        $objUsuario = $this->_usuario['usuario'];
        
        if ($objUsuario instanceof \Basico\Entity\Usuario)
            return $objUsuario->getNome();
        else
            throw new \Exception('Falha so recuperar o nome do usuário.');
    }
  

    /**
     * Retorna o perfil do usuário logado no sistema.
     */
    public function getPerfil(){
        if (empty($this->_usuario))
            $this->ini();
        
        if (isset($this->_usuario['perfil']))
        	return $this->_usuario['perfil'];
        else
            throw new \Exception('Falha so recuperar o perfil do usuário.');
    }
    
  


    

   




}

?>