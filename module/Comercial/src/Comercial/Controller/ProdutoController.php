<?php

namespace Comercial\Controller;

use Jlib\Util\FormatValue;
use Basico\Controller\CrudController;
use Zend\View\Model\ViewModel;
use Jlib\View\Html\Tabela\Tabela;
use Jlib\Log\LogException;

class ProdutoController extends CrudController {
	
	public function __construct(){
		$this->entity 		= 'Comercial\Entity\Produto';
		$this->form			= 'Comercial\Form\Produto';
		$this->formPesquisa	= 'Comercial\Form\ProdutoPesquisa';
		$this->service		= 'Comercial\Service\Produto';
		$this->controller	= 'produto';
		$this->route		= 'comercial';
	}
	
	public function indexAction(){
	
		
	
		//título da página
		$this->PageTitle()->set(array('getPedido','Produto'),' :: ');
		
		$frmPesquisa = $this->getServiceLocator()->get($this->formPesquisa);
			
		$where = array();
		if ($this->request->isPost()){
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
		
			$tabProdutos = $this->getTabelaHtml($where);
		}
		else {
			$this->addMessageInfo('Listando os 50 últimos registros.', 'Produtos');
			$tabProdutos = $this->getTabelaHtml();

		}
			
		return new ViewModel(array(
		    'tabProdutos'		=> $tabProdutos,
				'mensagens'			=> $this->getMessages(),
				'frmPesquisa'		=> $frmPesquisa,
		));
		
	}
	
public function newAction(){
	
		//título da página
		$this->PageTitle()->set(array('gePedidos','Produto', 'Novo'),' :: ');			

		$this->objForm = $this->getServiceLocator()->get($this->form);
		
		$request 	= $this->getRequest();
		
		if ($request->isPost()){
			$dados = $request->getPost()->toArray();

			try {
			
				$service = $this->getServiceLocator()->get($this->service);
				$entity = $service->insert($dados);
				$id = $entity ->getidProduto();
				$this->addMessageSuccess('Produto cadastrado com sucesso!');
				return $this->redirect()->toRoute($this->route, array('controller' => 'produto', 'action' => 'edit', 'id' => $id));
			} catch (\Exception $e){
				$log = new LogException($e);
				$this->addMessageError($log->getMessage());
				return $this->redirect()->toRoute($this->route, array('controller' => 'produto', 'action' => 'new'));
			}

			$this->objForm->setData($dados);
		}else{
			
			//$this->objForm->setData($dados);
		}
		
		return new ViewModel(array(
				'form' => $this->objForm,
				'mensagens'=> $this->getMessages(),
		));
	}
	public function editAction(){
	
		
		//título da página
		$this->PageTitle()->set(array('getPedidos','Produto'),' :: ');
			

		
	
		$this->objForm = $this->getServiceLocator()->get($this->form);
		
		$request 	= $this->getRequest();
		
		if ($request->isPost()){
			
			$dados = $request->getPost()->toArray();
			
			$service = $this->getServiceLocator()->get($this->service);
			$entity = $service->update($dados, 'idProduto');
			$this->addMessageSuccess('Produto atualizado com sucesso!');
			$idProduto = $dados['idProduto'];
			return $this->redirect()->toRoute($this->route, array('controller' => 'produto', 'action' => 'edit', 'id' => $idProduto));
		}else{
			
		    $idProduto = $this->params()->fromRoute('id',0);
			
			$repository = $this->getEm()->getRepository($this->entity);
			$entity		= $repository->findBy(array('idProduto'=> $idProduto));
			
			if ($entity == null){
				$this->addMessageError('Produto não encontrado.');
			} else {
				//Popula o formulário com os dados do banco
				$dadosForm = $entity[0]->toForm();
								
				$this->objForm->setData($dadosForm);

				
			}
			
			
		}	
		return new ViewModel(array(
		    'id' => $idProduto,
				'form' => $this->objForm,
				'mensagens'=> $this->getMessages(),
				
		));
	}
	public function deleteAction(){
	  
	    
	    $this->objForm = $this->getServiceLocator()->get($this->form);
	    
	    $request 	= $this->getRequest();
	    
	    
	    //recupera o id do registro pai, nesse caso o CodProspect
	    $RegistroPaiId = $this->params()->fromRoute('id',0);
	    //recupera o id do registro, nesse caso o ID
	    $RegistroId = $this->params()->fromRoute('child_id',0);
	    
	    $sql ="SELECT ProdutoidProduto FROM pedidoproduto WHERE ProdutoidProduto = {$RegistroPaiId}";
	    $stmt = $this->getEm()->getConnection()->query($sql);
	    $result = $stmt->fetchAll();
	    
	    if($result){
	        
	        $this->addMessageWarning('Produto já utilizado em algum pedido!');
	        
	    }else{
	        $del = "DELETE FROM Produto WHERE  idProduto = {$RegistroPaiId}";
	        $this->getEm()->getConnection()->exec($del);
	        $this->addMessageSuccess('Produto excluído com sucesso!');
	    }
	   
	    
	 
	    
	    
	    return $this->redirect()->toRoute($this->route, array('controller' => 'produto', 'action' => 'index'));
	}
	
	
	
	public function getTabelaHtml($where = array()){
	
		
		
		$repository = $this->getEm()->getRepository('Comercial\Entity\Produto');
	        $registros	= $repository->findBy($where, null, 100);
	        $dados = array();
	        foreach ($registros as $entity){
	            $dados[] = $entity->toTable();
	        }
	       
	        $tab = new Tabela($dados);
	        $tab->addCampo('idProduto', 'Código');
	        $tab->addCampo('SKU', 'SKU');
	        $tab->addCampo('Nome', 'Nome');
	        $tab->addCampo('Descricao', 'Descrição');
	        $tab->setAllowDelete(true);
	       	$tab->setDeleteLink('/comercial/produto/delete');
	       	$tab->setDeleteHrefClass('btnExcluir');
	       	$tab->addDeleteParam('idProduto',false);
	       	$tab->setShowDeleteIcon('fa fa-trash');
	       	$tab->setAllowEdit(true);
	       	$tab->setEditLink('/comercial/produto/edit');
	       	$tab->addEditParam('idProduto',false);
	       	$tab->setShowEditIcon('fa fa-pencil');
	        $tab->setHtmlId('produto-list');
	        	
	        return $tab;
	}
	
	/**
	 * Utilizado no autocomplete via ajax
	 * Busca os clientes através da parte do nome informado
	 * @return Json
	 */
	public function listprodutoAction(){
	    $response = $this->getResponse();
	    
	    //resgata os parametros
	    $urlParams = $this->getRequest()->getQuery()->toArray();
	    $nomeproduto = $urlParams['query'];
	    
	    
	    //busca somente quando for informado mais de 2 caracteres na pesquisa
	    if (strlen($nomeproduto) > 2){
	        
	        $sql = "SELECT idProduto, Nome, Preco From Produto WHERE Nome like '%{$nomeproduto}%' ";
	        $stmt = $this->getEm()->getConnection()->query($sql);
	        $result = $stmt->fetchAll();
	        
	        if($result){
	            foreach ($result as $registro){
	                
	                $formater = new FormatValue();
	                $valor = $formater->formatDecimal($registro['Preco']);
	                
	                
	                $suggestions[] = array(
	                    
	                    'data'  => $registro['idProduto'],
	                    'value' => $registro['Nome'],
	                    'preco' => $valor,
	                    
	                );
	            }
	        }else{
	            $suggestions[] = array(
	                
	                'data'  => 0,
	                'value' => '',
	                'preco' => '',
	                
	            );
	        }
	        
	        $ret = array(
	            'query'			=> $nomeproduto,
	            'suggestions' 	=> $suggestions
	        );
	        $response->setContent(\Zend\Json\Json::encode($ret));
	    }
	    
	    return $response;
	}
	
	
	
}