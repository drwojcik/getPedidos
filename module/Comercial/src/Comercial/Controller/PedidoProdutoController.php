<?php

namespace Comercial\Controller;

use Basico\Controller\CrudAjaxController;

use Jlib\Ajax\AjaxReturn;
use Jlib\View\Html\Message\Alert;
use Jlib\View\Html\Tabela\Tabela;

use Zend\View\Model\ViewModel;

class PedidoProdutoController extends CrudAjaxController {	
	
    public function __construct(){
        $this->entity 		= 'Comercial\Entity\PedidoProduto';
        $this->form			= 'Comercial\Form\PedidoProduto';
        $this->service		= 'Comercial\Service\PedidoProduto';
        $this->controller	= 'pedido-produto';
        $this->route		= 'comercial';
    }               

    /**
     * @see Basico\Controller.CrudAjaxController::getFormAction()
     */
    public function getFormAction()
    {
    	    	
        $viewModel = new ViewModel();
        //desabilita o layout devido a requisicao via ajax
        $viewModel->setTerminal(true);

        //recupera o id do registro pai, nesse caso o idPedido
        $RegistroPaiId = $this->params()->fromRoute('id',0);
        //recupera o id do registro, nesse caso o ID
        $RegistroId = $this->params()->fromRoute('child_id',0);
    
        //formulario
        $this->objForm 	= new $this->form();
        $dadosForm 		= array();
    
        //se estiver alterando o registro popula o form
        if ($RegistroId){
            //busca o registro no banco
            $repository = $this->getEm()->getRepository($this->entity);
            $where		= array('idPedidoProduto' => $RegistroId);
            $entity		= $repository->findOneBy($where);
    
            if (null === $entity){
                $this->addMessageError('Registro não encontrado');
            }
            else {
                //Popula o formulario com os dados do banco
                $dadosForm = $entity->toForm();
                $this->objForm->setData($dadosForm);
            }
        } else {
            //inserindo registro
            //envia para o form o ID do registro pai
            $this->objForm->setData(array('idProduto' => $RegistroPaiId));
        }
    
        $viewModel->setVariables(array(
                'mensagens'			=> $this->getMessages(),
                'form' 				=> $this->objForm,
                'RegistroPaiId'		=> $RegistroPaiId,
                'RegistroId' 		=> $RegistroId,
                'DadosForm'			=> $dadosForm,
        ));
        return $viewModel;
    }
    
    /**
     * @see Basico\Controller.CrudAjaxController::saveAjaxAction()
     */
    public function saveAjaxAction()
    {
        $this->objForm	= new $this->form();
        $request 		= $this->getRequest();
        $response 		= $this->getResponse();
        //ajax return
        $ajaxReturn = new AjaxReturn();
    
        if ($request->isPost()){
           
                //formulario valido :)
                //grava os dados no banco
                try {
                
                    $dados 		= $request->getPost()->toArray();
                    $service 	= $this->getServiceLocator()->get($this->service);
    
                    //verifica se ira realizar insert ou update
                    if (empty($dados['idPedidoProduto'])){
                        $entity = $service->insert($dados);
                        $idPedidoProduto 	= $entity->getidPedidoProduto();
                        $this->addMessageSuccess('Registro cadastrado com sucesso.');
                    } else {
                        $entity = $service->update($dados, 'idPedidoProduto');
                        $idPedidoProduto 	=  $entity->getidPedidoProduto();
                        $this->addMessageSuccess('Registro atualizado com sucesso.');
                    }
    
                    $total = $this->getTotalPedido($dados['PedidoIdPedido']);
                    
                    //incluir no retorno os js utilizados pela grid
                    $js = '<script type="text/javascript" src="/js/app/comercial/pedido-produto/form.modal.js" />';
                    $js.= '<script type="text/javascript" src="/js/app/mascaras.js" />';
                    $retorno = $js;
                    //lista os registros da tabela e envia para a view
                    $tabelaProduto = $this->getGrids($dados['PedidoIdPedido']);
                    $retorno.= $tabelaProduto;
    
                    //seta o retorno ajax
                    $ajaxReturn->setSuccess('Sucesso', $this->getMessages());
                    $ajaxReturn->setHtml($retorno);
                    $ajaxReturn->setExtra(array(
                        'idSeq' => $idPedidoProduto,
                    ));
    
                } catch (\Exception $e) {
              
                	
                    $this->addMessageError($e->getMessage() . ' - FILE: ' . $e->getFile() . ' - LINE: ' . $e->getLine());
                    $ajaxReturn->setError('Erro', $this->getMessages());
                }
            
            //seta a resposta da página
            $response->setContent($ajaxReturn->toJson());
        }
        return $response;
    }
    
    /**
     * @see Basico\Controller.CrudAjaxController::deleteAjaxAction()
     */
    public function deleteAjaxAction()
    {
        $response = $this->getResponse();
        //resgata o servico
        $service = $this->getServiceLocator()->get($this->service);
    
        //recupera o id do registro pai, nesse caso o CodProspect
        $RegistroPaiId = $this->params()->fromRoute('id',0);
        //recupera o id do registro, nesse caso o ID
        $RegistroId = $this->params()->fromRoute('child_id',0);
    
        $del = "DELETE FROM pedidoproduto WHERE  idPedidoProduto = {$RegistroId}";
        $this->getEm()->getConnection()->exec($del);
        $this->getTotalPedido($RegistroPaiId);
        //deleta o registro
        if ($RegistroPaiId){
            $js = '<script type="text/javascript" src="/js/app/comercial/pedido-produto/form.modal.js" />';
            $js.= '<script type="text/javascript" src="/js/app/mascaras.js" />';
            $retorno = $js;
            //lista os registros da tabela e envia para a view
            $tabelaProdutos = $this->getGrids($RegistroPaiId);
            $retorno.= $tabelaProdutos;
            
            //seta o retorno ajax
            $ajaxReturn = new AjaxReturn();
            $this->addMessageSuccess('Registro excluído com sucesso.');
            $ajaxReturn->setSuccess('Sucesso', $this->getMessages());
            $ajaxReturn->setHtml($retorno);
            
             
            //seta a resposta da página
            $response->setContent($ajaxReturn->toJson());
        }
        return $response;
    }
    
    /**
     * @see Basico\Controller.CrudAbstractController::getTabelaHtml()
     */
    public function getTabelaHtml($where = array()) {
        $repository = $this->getEm()->getRepository($this->entity);
        return $repository->getTableHtmlByWhere($where);
    }
    
    public function getGrids($codPedido){
        
        //lista os produtos do pedido em formato de tabela html
        $sql = "Select idPedidoProduto, idPedido , NOme as Produto, QtdeProduto, Preco,  (Preco * QtdeProduto) as SubTotal  FroM Pedido p
                INNER JOIN PedidoProduto pp ON pp.PedidoIdPedido = p.idPedido
                INNER JOIN produto pr ON pr.idProduto = pp.ProdutoidProduto where p.idPedido = {$codPedido}";
        
        $stmt = $this->getEm ()->getConnection ()->query ( $sql );
        $resultado = $stmt->fetchAll ();

        
        $repository = $this->getEm()->getRepository('Comercial\Entity\PedidoProduto');
        $tabelaProduto = $repository->dataToHtmlTable($resultado);
        
        
        
        return  $tabelaProduto;
    }

	public function getMsgDeleteAction(){
	    $response = $this->getResponse();
	    $request = $this->getRequest();
	    
	    $dados = $request->getPost()->toArray();	    
	    //recupera o id do registro, nesse caso o ID
	    $RegistroId 	= $dados['child_id'];
	    
	    //busca os follows do prospect
	    $repository = $this->getEm()->getRepository('Comercial\Entity\ProspectFollow');
	    $where = array('Contato' => $RegistroId);
	    $follows = $repository->findBy($where);
	    $total = count($follows);
	    
	    if ($total == 0){
	        $msg = 'Confirmar a exclusão desse contato?';
	    } else if ($total == 1){
	        $msg = 'Existe <b>1 follow</b> cadastrado para esse contato.';
	        $msg.= ' Ao excluir esse contato seus respectivos follows também serão excluídos.';
	        $msg.= '<br><br>Deseja confirmar a exclusão?';	        
	    } else {
	        $msg = 'Existem <b>'.$total.' follows</b> cadastrados para esse contato.';
	        $msg.= ' Ao excluir esse contato seus respectivos follows também serão excluídos.';
	        $msg.= '<br><br>Deseja confirmar a exclusão?';
	    }
	    
	    $msgHtml = new Alert(Alert::WARNING_MSG, 'Atenção', $msg);
	    $msgHtml->_closeButton = false;
    	
	    //seta o retorno ajax
	    $ajaxReturn = new AjaxReturn();
	    $ajaxReturn->setSuccess('Sucesso', '');
	    $ajaxReturn->setHtml($msgHtml->render());
	     
	    //seta a resposta da página
	    $response->setContent($ajaxReturn->toJson());
	    return $response; 
	}
	
	public function getTotalPedido($codPedido){
	    
	    //lista os produtos do pedido em formato de tabela html
	    $sql = "SELECT sum((Preco * QtdeProduto)) as Total  FroM Pedido p
                INNER JOIN PedidoProduto pp ON pp.PedidoIdPedido = p.idPedido
                INNER JOIN produto pr ON pr.idProduto = pp.ProdutoidProduto where p.idPedido = {$codPedido}";
	    
	    $stmt = $this->getEm ()->getConnection ()->query ( $sql );
	    $resultado = $stmt->fetchAll ();
	    $Total = $resultado[0]['Total'];
	    
	    $upd = "UPDATE Pedido SET Total = {$Total} WHERE  idPedido = {$codPedido}";
	    $this->getEm()->getConnection()->exec($upd);
	    
	    
	    
	    
	    
	    
	    return  $Total;
	}
}
