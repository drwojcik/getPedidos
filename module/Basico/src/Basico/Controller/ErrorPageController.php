<?php

namespace Basico\Controller;

use Jlib\View\Html\Message\Alert;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ErrorPageController extends AbstractActionController {	

    public function indexAction(){
        //título da página
        $this->PageTitle()->set(array('ERP SCAM','Erro', 'Erro ao exibir a página'),' :: ');
        
        $viewModel = new ViewModel();
        
        $titulo     = 'Erro no processo';
        $mensagem   = new Alert(Alert::WARNING_MSG, 'Isso não é bom', 'Ocorreu um erro ao realizar a operação solicitada.');
        
        $viewModel->setVariables(array(
            'titulo'    => $titulo,
            'mensagem'  => $mensagem
        ));
        
        return $viewModel;
    }
    
    public function acessoNegadoAction(){
        //título da página
        $this->PageTitle()->set(array('ERP SCAM','Erro', 'Acesso Negado'),' :: ');
        
        $viewModel = new ViewModel();
        
        $titulo     = 'Acesso Negado';
        $mensagem   = new Alert(Alert::WARNING_MSG, 'Acesso Negado', 'Você não tem permissão para visualizar a página.');
        
        $viewModel->setTemplate('basico/error-page/acesso-negado');
        
        $viewModel->setVariables(array(
            'titulo'    => $titulo,
            'mensagem'  => $mensagem
        ));
        
        return $viewModel;
    }
    
    public function paginaNaoEncontradaAction(){
        //título da página
        $this->PageTitle()->set(array('ERP SCAM','Erro', 'Página não encontrada'),' :: ');
        
        $viewModel = new ViewModel();
        
        $titulo     = 'Página não encontrada';
        $mensagem   = new Alert(Alert::WARNING_MSG, 'Página não encontrada', 'A página que você tentou acessar não foi encontrada.');
        
        $viewModel->setTemplate('basico/error-page/index');
        
        $viewModel->setVariables(array(
            'titulo'    => $titulo,
            'mensagem'  => $mensagem
        ));
        
        return $viewModel;
    }        
}
