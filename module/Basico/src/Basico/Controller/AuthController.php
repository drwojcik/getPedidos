<?php

namespace Basico\Controller;

use Zend\Session\Container;

use Zend\View\Model\ViewModel;

use Zend\Authentication\AuthenticationService;

use Zend\Authentication\Storage\Session As SessionStorage;

use Basico\Form\Auth As AuthForm;

class AuthController extends CrudAbstractController {	
	
    public function indexAction(){
        //título da página
        $this->PageTitle()->set(array('Pedidos','Login'),' :: ');
        
        $this->objForm = new AuthForm();
        $error = false;
        
        $serivicelogo = $this->getServiceLocator();        
        $config = $serivicelogo->get('config');
        $logo = $config['logo']['logo_principal'];
        $imgPath = getcwd().'/public/'.$logo;
        if (is_file($imgPath)){
        	$logoPrincipal = '<img src="'.$logo.'"
    							     style="border-radius: 10px; margin-left: 2px; margin-right: 10px;" />';
        }
        else {
        	$logoPrincipal = '<img src="/img/logo/Logo_Cliente.png" style="border-radius: 10px; margin-left: 2px; margin-right: 10px;"/>';
        }
        
     
        $request = $this->getRequest();
        if ($request->isPost()){
            $this->objForm->setData($request->getPost());
            if ($this->objForm->isValid()){
                try {
                    //processo de autenticacao
                    $data = $request->getPost()->toArray();
                  

                    $sessionStorage = new SessionStorage('Main');
                    $auth = new AuthenticationService();
                    $auth->setStorage($sessionStorage);
                    
                    $authAdapter = $this->getServiceLocator()->get('Basico\Auth\Adapter');
                    $authAdapter->setUsername($data['Login']);
                    $authAdapter->setPassword($data['Senha']);
                    
                    $result = $auth->authenticate($authAdapter);
                    if ($result->isValid()){
                        $identity = $auth->getIdentity();
                        $sessionStorage->write(array(
                                'usuario' 	=> $identity['funcionario'],
                                'perfil'	=> 'funcionario'
                        ));
                      


                        //redireciona o usuário para a página que ele tentou acessar antes do login
                        $sessionUrl = new Container('Url');
                        $url = $sessionUrl->offsetGet('last');
                        
                        if (!empty($url))
                            return $this->redirect()->toUrl($url);
                        else
                        	return $this->redirect()->toRoute('home');
                    } else {
                        $this->addMessageError('Usuário ou Senha inválidos', 'Falha ao realizar o login');
                    }
                } catch (\Exception $e){
                    $this->addMessageError($e->getMessage());                   
                }                
            }
        }

        //Logos do rodapé da tela de Login estão dinâmicas

        $viewModel = new ViewModel(array(
                'form' 		=> $this->objForm,
                'mensagens' => $this->getMessages(),
                'error'		=> $error,
        		'logo'      => $logoPrincipal,

        		'logoErpScam'      => $config['logo']['tela-login-sistema'],
        		'logoSoftwar'      => $config['logo']['tela-login-softwar'],
		));
        
        //altera para o layout de login
        $this->layout('layout\layout_login');
        
        return $viewModel;
    }
    
    public function logoutAction(){
        $sessionStorage = new SessionStorage('Main');
    	$auth = new AuthenticationService();
    	$auth->setStorage($sessionStorage);
    	$auth->clearIdentity();
    	//Apaga os parãmetros de Obra e Empresa
    	$sessionStorage = new SessionStorage('Parametros');
    	$sessionStorage->clear();
    	
    	$sessionStorage = new SessionStorage('Git');
    	$sessionStorage->clear();
    	
    	$sessionUrl = new Container('Url');
    	$sessionUrl->getManager()->getStorage()->clear('Url');
    	
    	return $this->redirect()->toRoute('login');
    }

    /**
     * @see Basico\Controller.CrudAbstractController::getTabelaHtml()
     */
	public function getTabelaHtml ($where = array()) {
        throw new \Exception('Método não implementado.');
    }

        
	    
}
