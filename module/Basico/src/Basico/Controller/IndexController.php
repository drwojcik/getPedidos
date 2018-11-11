<?php

namespace Basico\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends CrudAbstractController {	
	
    public function indexAction(){
        $this->redirect()->toRoute('basico', array('controller' => 'dashboard', 'action' => 'index'));
    }
    
    public function getTabelaHtml($where = array()){
        throw new \Exception('Método não implementado');    
    }
}
