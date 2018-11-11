<?php

namespace Basico\Controller;

use Jlib\View\Html\Message\Alert;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\Plugin\FlashMessenger;

abstract class CrudAjaxController extends CrudAbstractController
{			

	/**
	 * Cria um novo formulário
	 */
    public abstract function getFormAction();
    
    /**
     * Salva os dados enviados via ajax
     */
    public abstract function saveAjaxAction();
    
    /**
     * Exclui um registro enviado via ajax
     */
    public abstract function deleteAjaxAction();                
}
