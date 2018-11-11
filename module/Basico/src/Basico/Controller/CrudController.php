<?php

namespace Basico\Controller;

use Jlib\View\Html\Message\Alert;

use Zend\Mvc\Controller\Plugin\FlashMessenger;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

abstract class CrudController extends CrudAbstractController
{		

    public function newAction(){
    	$form = new $this->form();    	
    	$req = $this->getRequest();
    	
    	$erroMsg = array();
    	if ($req->isPost()){
    		$form->setData($req->getPost());
    		if ($form->isValid()){    			
    			//método de inserir    			 			
    			try {
    				$dados = $req->getPost()->toArray();
    				$service = $this->getServiceLocator()->get($this->service);
    				$service->insert($dados);			 
    				return $this->redirect()->toRoute($this->route, array('controller' => $this->controller, 'action' => 'index'));
    			} catch (\Exception $e){
    				$erroMsg[] = $e->getMessage();
    			}
    		}
    		else {    			
    			foreach ($form->getMessages() as $erroArray){
    				foreach ($erroArray as $erro){
    					$erroMsg[] =  '<br>' . $erro;    					
    				}
    			}    			
    		}
    	}
    	
    	return new ViewModel(array('form' => $form, 'errorMsg' => $erroMsg));
    }
    
    public function editAction(){
    	$form = new $this->form();
    	$req = $this->getRequest();
    	
    	$erroMsg = array();
    	$id = $this->params()->fromRoute('id',0);    	    	
    	
    	//Busca o registro no banco
    	$repository = $this->getEm()->getRepository($this->entity);    	
    	$entity		= $repository->find($id);
    	
    	if (null === $entity){
    		$erroMsg[] = 'Registro não encontrado';
    	}
    	else {
    		if ($id){
    			//Popula o formulario com os dados do banco
    			$form->setData($entity->toArray());
    		}
    		 
    		if ($req->isPost()){
    			$dados = $req->getPost()->toArray();
    			$form->setData($dados);
    			if ($form->isValid()){
    				try {
    					$service = $this->getServiceLocator()->get($this->service);
    					$service->update($dados);
    					
    					$msg = new Alert(Alert::SUCCESS_MSG, 'Sucesso', 'Registro alterado com sucesso.');
    					$this->flashMessenger()->addMessage($msg->render());
    					
    					return $this->redirect()->toRoute($this->route, array('controller' => $this->controller, 'action' => 'index'));
    				} catch (\Exception $e) {
    					$erroMsg[] = $e->getMessage();
    				}
    			}
    			else {
    				foreach ($form->getMessages() as $erroArray){
    					foreach ($erroArray as $erro){
    						$erroMsg[] =  '<br>' . $erro;
    					}
    				}
    			}
    		}
    	}
    	
    	return new ViewModel(array('form' => $form, 'id' => $id, 'errorMsg' => $erroMsg));
    }
    
    public function deleteAction(){
    	//resgata o servico
    	$service = $this->getServiceLocator()->get($this->service);
    	//captura o parametro enviado
    	$id = $this->params()->fromRoute('id', 0);
    	
    	try {
    	    $service->delete($id);
    	    $msg = new Alert(Alert::SUCCESS_MSG, 'Sucesso', 'Registro excluído com sucesso.');
    	    $this->flashMessenger()->addMessage($msg->render());
    	    return $this->redirect()->toRoute($this->route, array('controller' => $this->controller, 'action' => 'index'));
    	} catch (\Exception $e) {
    	    $msg = new Alert(Alert::ERROR_MSG, 'Erro', 'Falha ao excluir o registro. '.$e->getMessage());
    	    $this->flashMessenger()->addMessage($msg->render());
    	    return $this->redirect()->toRoute($this->route, array('controller' => $this->controller, 'action' => 'edit', 'id' => $id));
    	}    	
    }            
}
