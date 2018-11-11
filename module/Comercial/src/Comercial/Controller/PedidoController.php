<?php

namespace Comercial\Controller;

use Jlib\View\Html\Tabela\Tabela;
use Jlib\Ajax\AjaxReturn;
use Jlib\View\Html\Message\Alert;
use Jlib\Log\LogException;

use Basico\Controller\CrudController;
use Comercial\Form\Prospect As FrmProspect;
use Comercial\Entity\Prospect;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class PedidoController extends CrudController {	
	
    public function __construct(){
        $this->entity 		= 'Comercial\Entity\Pedido';
        $this->form			= 'Comercial\Form\Pedido';
        $this->service		= 'Comercial\Service\Pedido';
        $this->controller	= 'pedido';
        $this->route		= 'comercial';
    }
    
    public function indexAction(){

	    //título da página
	    $this->PageTitle()->set(array('getPedidos','Pedido'),' :: ');
	    
	    $frmPesquisa = $this->getServiceLocator()->get('Comercial\Form\Pedido');
	    $request 	 = $this->getRequest();
	    
	    $where = array(); 
	    
	  
	    if($request->isPost()){
	    	
	    	//recebe os dados pesquisados no filtro
	    	$dadosFiltro = $this->request->getPost()->toArray();
	    	
	    	if (count($dadosFiltro)){
	    		foreach ($dadosFiltro as $filtro => $valor){
	    			if (!empty($valor)){
	    				$where[$filtro] = $valor;
	    			}
	    		}
	    	}
	    		    	
	    	$frmPesquisa->setData($where);
	    	
	    	//tabela com os registros
	    	$tabelaHtml = $this->getTabelaHtml($where);
	    	
	    	
	    }
	    
	    else{
	    	
	    	//tabela com os registros
	    	$tabelaHtml = $this->getTabelaHtml($where);
	    	
	    }
	    	    
	    //mensagens
	    $mesagens = $this->getMessages();

	    	return new ViewModel(array(
	    			'frmPesquisa' => $frmPesquisa,
	    			'tabela' 	  => $tabelaHtml,
	    			'mensagens'	  => $mesagens,
	    	));
	    
	    
    }
    
    public function newAction(){

        //título da página
        $this->PageTitle()->set(array('getPedidos','Pedido','Novo Pedido'),' :: ');
        
        $this->objForm = $this->getServiceLocator()->get($this->form);
        $request = $this->getRequest();
        
        if ($request->isPost()){
            $dados = $request->getPost()->toArray();
          
            $this->objForm->setData($dados);
            
            if ($this->objForm->isValid()){
                //método de inserir
                try {
                    $this->addMessageSuccess('Registro cadastrado com sucesso!');
                    $service = $this->getServiceLocator()->get($this->service);
                    $entity = $service->insert($dados);
                    return $this->redirect()->toRoute($this->route, array('controller' => $this->controller, 'action' => 'edit', 'id' => $entity->getidPedido()));
                } catch (\Exception $e){
                    $this->addMessageError($e->getMessage());
                }
            } else {
                $this->catchFormErrors();
            }
        }
        
        return new ViewModel(array(                
                'form' 			=> $this->objForm,
                'mensagens' 	=> $this->getMessages(),
        ));
    }
    
    public function editAction(){
        //título da página
        $this->PageTitle()->set(array('getPedidos','Pedido','Alterando Pedido'),' :: ');
        
        $this->objForm = $this->getServiceLocator()->get($this->form);
        $request = $this->getRequest();
        
        if (!$request->isPost()){
            $dados = array();
            $id = $this->params()->fromRoute('id',0);
            //Busca o registro no banco
            $repository = $this->getEm()->getRepository($this->entity);
            $entity		= $repository->find($id);
            
            if ($entity == null){
                $this->addMessageError('Registro não encontrado.');
            } else {
                //Popula o formulario com os dados do banco
                $dados = $entity->toForm();
               
                $this->objForm->setData($dados);
                
                //carrega as grids
                $tablePedidoProduto = $this->getGrids($id);
                
            }
        } else {
            $dados = $request->getPost()->toArray();
            $this->objForm->setData($request->getPost());
            
            if ($this->objForm->isValid()){
            	
            	
	                //método de atualizar
	                try {
	                    $service = $this->getServiceLocator()->get($this->service);
	                    $entity = $service->update($dados, 'idPedido');
	                    $this->addMessageSuccess('Registro atualizado com sucesso!');
	                    $id = $entity->getidPedido();
	                    
	                    
	                    
	                    return $this->redirect()->toRoute($this->route, array('controller' => $this->controller, 'action' => 'edit', 'id' => $id));
	                } catch (\Exception $e){
	                    $this->addMessageError($e->getMessage());
	                }
            	
            } else {
                $this->catchFormErrors();
            }
        }
        
        
        
        return new ViewModel(array(                
                'form' 			=> $this->objForm,
                'mensagens' 	=> $this->getMessages(),
                'idPedido'	=> $id,
              
            'tablePedidoProduto'	=> $tablePedidoProduto,
            
                'DadosForm'		=> $dados,
        ));
    }

    public function deleteAction(){
        
        
        $this->objForm = $this->getServiceLocator()->get($this->form);
        
        $request 	= $this->getRequest();
        
        
        //recupera o id do registro pai, nesse caso o CodProspect
        $RegistroPaiId = $this->params()->fromRoute('id',0);
        //recupera o id do registro, nesse caso o ID
        $RegistroId = $this->params()->fromRoute('child_id',0);
        
        $sql ="SELECT ProdutoidProduto FROM pedidoproduto WHERE PedidoIdPedido = {$RegistroPaiId}";
        $stmt = $this->getEm()->getConnection()->query($sql);
        $result = $stmt->fetchAll();
        
        if($result){
            
            $this->addMessageWarning('Pedido possui produtos não pode ser excluído!');
            
        }else{
            $del = "DELETE FROM Pedido WHERE  idPedido = {$RegistroPaiId}";
            $this->getEm()->getConnection()->exec($del);
            $this->addMessageSuccess('Pedido excluído com sucesso!');
        }
        
        
        
        
        
        return $this->redirect()->toRoute($this->route, array('controller' => 'pedido', 'action' => 'index'));
    }
    
    /**
     * @see Basico\Controller.CrudController::getTabelaHtml()
     */
    public function getTabelaHtml ($where = array()) {
        $repository = $this->getEm()->getRepository($this->entity);
        $registros	= $repository->findBy($where);
        $dados		= array();
        foreach ($registros as $registro){
            $dados[] = $registro->toForm();
        }
    
        //Tabela
        $tab = new Tabela($dados);

        $tab->setHtmlId('pedido-list');
        $tab->addCampo('idPedido', 'Cód');
        $tab->addCampo('Data', 'Data');
        $tab->addCampo('Total', 'Total');
        
    
        $tab->setAllowEdit(true);
        $tab->setEditLink('/comercial/pedido/edit');
        $tab->addEditParam('idPedido', false);
    	$tab->setAllowDelete(true);
        
        $tab->setDeleteLink('/comercial/pedido/delete');
        $tab->setDeleteHrefClass('btnExcluir linkOpcoes');
        $tab->addDeleteParam('idPedido', false);
        return $tab;
    }
    
    /**
     * Busca as grids da tela.
     * 
     * @param $entity
     */
    protected function getGrids($codPedido){        
   
       //Busca os produtos antes de 
        $sql = "SELECT idPedidoProduto, idPedido,  Nome as Produto, QtdeProduto, Preco,  (Preco * QtdeProduto) as SubTotal  FROM Pedido p
                    INNER JOIN PedidoProduto pp ON pp.PedidoIdPedido = p.idPedido
                    INNER JOIN produto pr ON pr.idProduto = pp.ProdutoidProduto 
                WHERE p.idPedido = {$codPedido}";
        
        $stmt = $this->getEm ()->getConnection ()->query ( $sql );
        $resultado = $stmt->fetchAll ();

        
        //lista os produtos do pedido em formato de tabela html
        $repository = $this->getEm()->getRepository('Comercial\Entity\PedidoProduto');
        $tabelaProduto = $repository->dataToHtmlTable($resultado);
        
      
        
        return  $tabelaProduto;
    }
    
    
    
   
   

}
