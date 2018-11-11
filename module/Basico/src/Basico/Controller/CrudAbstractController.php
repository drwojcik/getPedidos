<?php

namespace Basico\Controller;

use Jlib\View\Html\Message\Alert;

use Jlib\View\Html\Tabela\Tabela;
use Zend\Mvc\Controller\Plugin\FlashMessenger;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Jlib\Util\FormatValue;

abstract class CrudAbstractController extends AbstractActionController
{
	
	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	protected $em;
	
	protected $service;
	
	protected $entity;
	
	protected $form;
	
	protected $route;
	
	protected $controller;
	
	protected $objForm;
		
	
	/**
	 * @return \Doctrine\ORM\EntityManager
	 */
	public function getEm(){
		if (null === $this->em){
			$this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
		}
		
		return $this->em;
	}    
    
    /**
     * Adiciona uma mensagem de erro ao FlashMessenger.
     * 
     * @param string $msg
     * @param string $msgTitle (opcional)
     * @return void
     */
    public function addMessageError($msg, $msgTitle = null){
        if (empty($msgTitle))
            $msgTitle = 'Erro';
        $mensagem = new Alert(Alert::ERROR_MSG, $msgTitle, $msg);
        $mensagem->_closeButton = false;
        $this->flashMessenger()->addMessage($mensagem->render());        
    }
    
    /**
     * Adiciona uma mensagem de sucesso ao FlashMessenger.
     *
     * @param string $msg
     * @param string $msgTitle (opcional)
     * @return void
     */
    public function addMessageSuccess($msg, $msgTitle = null){
        if (empty($msgTitle))
            $msgTitle = 'Sucesso';
        $mensagem = new Alert(Alert::SUCCESS_MSG, $msgTitle, $msg);
        $mensagem->_closeButton = false;
        $this->flashMessenger()->addMessage($mensagem->render());
    }
    
    /**
     * Adiciona uma mensagem de informação ao FlashMessenger.
     *
     * @param string $msg
     * @param string $msgTitle (opcional)
     * @return void
     */
    public function addMessageInfo($msg, $msgTitle = null){
        if (empty($msgTitle))
            $msgTitle = 'Informação';
        $mensagem = new Alert(Alert::INFO_MSG, $msgTitle, $msg);
        $mensagem->_closeButton = false;
        $this->flashMessenger()->addMessage($mensagem->render());
    }
    
    /**
     * Adiciona uma mensagem de warning ao FlashMessenger.
     *
     * @param string $msg
     * @param string $msgTitle (opcional)
     * @return void
     */
    public function addMessageWarning($msg, $msgTitle = null){
        if (empty($msgTitle))
            $msgTitle = 'Atenção';
        $mensagem = new Alert(Alert::WARNING_MSG, $msgTitle, $msg);
        $mensagem->_closeButton = false;
        $this->flashMessenger()->addMessage($mensagem->render());
    }
    
    /**
     * Captura as mensagens de erro geradas pelo formulário e envia para o FlashMessenger.
     * 
     * @return void
     */
    public function catchFormErrors(){
        $erroMsg = array();
    	$formMessages = $this->objForm->getMessages();
		foreach ($formMessages as $formElement => $formErrors){	                
        	foreach ($formErrors as $erro){
        	    $this->addMessageError($erro, $formElement);
			}
		}
		
		if (count($erroMsg)){
		    $msg = implode("<br>", $erroMsg);
		    $this->addMessageError($msg);
		}
    }
    
    /**
     * Retorna as mensagens geradas com o FlashMessenger.
     * Após o retorno limpa o FlashMessenger
     * 
     * @return string
     */
    public function getMessages(){
        $mensagens = '';
        $flashMessenger = new FlashMessenger();
        if ($flashMessenger->hasMessages()) {
            foreach ($flashMessenger->getMessages() as $msg) {
                $mensagens.= $msg;
            }
            $flashMessenger->clearMessages();
        }
        return $mensagens;
    }

    

   
	/**
	 * Cria uma tabela html com os registros do banco de dados.
	 * @param array $where
	 */
	public abstract function getTabelaHtml($where = array());
}
