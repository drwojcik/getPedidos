<?php

namespace Basico;

use Jlib\Util\GitInfo;

use Basico\View\Helper\LegendaDeCores;
use Basico\View\Helper\UserInfo;

//Zend
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Controller\ControllerManager;
use Zend\EventManager\Event;
use Zend\ModuleManager\ModuleManager;
use Zend\Authentication\Storage\Session As SessionStorage;
use Zend\Authentication\AuthenticationService;
use Zend\Session\Container;

//Service
use Basico\Service\Agenda As SvcAgenda;
use Basico\Service\AgendaAviso As SvcAgendaAviso;
use Basico\Service\Cliente As SvcCliente;
use Basico\Service\ClienteContato As SvcClienteContato;
use Basico\Service\ClienteCobranca As SvcClienteCobranca;
use Basico\Service\CondicaoPagamento As SvcCondicaoPagamento;
use Basico\Service\Fornecedor As SvcFornecedor;
use Basico\Service\FornecedorContaContabil As SvcFornecedorContaContabil;
use Basico\Service\FornecedorNaoConformidade As SvcFornecedorNaoConformidade;
use Basico\Service\FornecedorAvaliacaoHistorico As SvcFornecedorAvaliacaoHistorico;
use Basico\Service\TipoAgenda As SvcTipoAgenda;
use Basico\Service\GeraisEmailEnvioAutomatico As SvcGeraisEmailEnvioAutomatico;
use Basico\Service\MensagemInterna As SvcMensagemInterna;
use Basico\Service\MensagemInternaDestinatario As SvcMensagemInternaDestinatario;
use Basico\Service\Contatos As SvcContatos;
use Basico\Service\Telefones As SvcTelefones;
use Basico\Service\Funcionario As SvcFuncionario;
use Basico\Service\Setor As SvcSetor;
use Basico\Service\TipoFuncionario As SvcTipoFuncionario;
use Basico\Service\Material As SvcMaterial;
use Basico\Service\ClienteSegmento As SvcClienteSegmento;
use Basico\Service\Promoter As SvcPromoter;
use Basico\Service\Unidade As SvcUnidade;
use Basico\Service\Ramo As SvcRamo;
use Basico\Service\Gerais As SvcGerais;
use Basico\Service\ClientePauta As SvcClientePauta;
use Basico\Service\ContratoPadrao As SvcContratoPadrao;
use Basico\Service\ContratoPadraoCliente As SvcContratoPadraoCliente;
use Basico\Service\ClienteAnexo As SvcClienteAnexo;


//Form
use Basico\Form\Agenda As FrmAgenda;
use Basico\Form\PesquisaAgenda As FrmPesquisaAgenda;
use Basico\Form\AgendaAviso As FrmAgendaAviso;
use Basico\Form\MensagemInterna As FrmMensagemInterna;
use Basico\Form\MensagemInternaReply As FrmMensagemInternaReply;
use Basico\Form\CondicaoPagamento as FrmCondicaoPagamento;
use Basico\Form\Cliente As FrmCliente;
use Basico\Form\ClienteCobranca As FrmClienteCobranca;
use Basico\Form\ClienteCobrancaPesquisa As FrmClienteCobrancaPesquisa;
use Basico\Form\DesativarPessoasPesquisa as FrmDesativarPessoasPesquisa;
use Basico\Form\Fornecedor As FrmFornecedor;
use Basico\Form\FornecedorContaContabil As FrmFornecedorContaContabil;
use Basico\Form\FornecedorNaoConformidade As FrmFornecedorNaoConformidade;
use Basico\Form\FornecedorPesquisa As FrmFornecedorPesquisa;
use Basico\Form\Contatos As FrmContatos;
use Basico\Form\Telefones As FrmTelefones;
use Basico\Form\Setor As FrmSetor;
use Basico\Form\TipoFuncionario As FrmTipoFuncionario;
use Basico\Form\Funcionario As FrmFuncionario;
use Basico\Form\FuncionarioPesquisa As FrmFuncionarioPesquisa;
use Basico\Form\GerarFinanceiro As FrmGerarFinanceiro;
use Basico\Form\TipoAgenda As FrmTipoAgenda;
use Basico\Form\TipoAgendaPesquisa As FrmTipoAgendaPesquisa;
use Basico\Form\ImprimirEtiqueta As FrmImprimirEtiqueta;
use Basico\Form\ClienteSegmento As FrmClienteSegmento;
use Basico\Form\Unidade as FrmUnidade;  
use Basico\Form\UnidadePesquisa as FrmUnidadePesquisa;
use Basico\Form\RamoPesquisa as FrmRamoPesquisa;
use Basico\Form\Ramo as FrmRamo;
use Basico\Form\ClientePauta as FrmClientePauta;

use Basico\Form\Gerais As FrmGerais;

use Basico\Form\Promoter As FrmPromoter;
use Basico\Form\PromoterPesquisa As FrmPromoterPesquisa;

use Basico\Form\ClienteFiltroPesquisa As FrmClienteFiltroPesquisa;
use Basico\Form\ContratoPadrao As FrmContratoPadrao;
use Basico\Form\ContratoPadraoCliente As FrmContratoPadraoCliente;


//Auth
use Basico\Auth\Adapter As AuthAdapter;
//Email
use Basico\Email\Email;
use Zend\Session\Storage\SessionArrayStorage;
use Zend\Session\SessionManager;
use Basico\Form\ClienteContato;
//Auditoria
use Doctrine\DBAL\Logging\LoggerChain;
use Basico\Auditoria\SqlLogger;

class Module {
	
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
//         	'Zend\Loader\ClassMapAutoloader' => array(
//         		__DIR__.'/autoload_classmap.php'	
//         	),	
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
    
    public function onBootstrap(\Zend\Mvc\MvcEvent $e) {
        $eventManager = $e->getApplication()->getEventManager();
                        
        //handle the dispatch error (exception)
        $eventManager->attach(\Zend\Mvc\MvcEvent::EVENT_DISPATCH_ERROR, array($this, 'handleError'));
                
        $e->getApplication()->getEventManager()->getSharedManager()->attach('Zend\Mvc\Controller\AbstractController', 'dispatch', function($e) {
            $controller      = $e->getTarget();
            $controllerClass = get_class($controller);
            $moduleNamespace = substr($controllerClass, 0, strpos($controllerClass, '\\'));
            $config          = $e->getApplication()->getServiceManager()->get('config');
            if (isset($config['module_layouts'][$moduleNamespace])) {
                $controller->layout($config['module_layouts'][$moduleNamespace]);
            } else {
                $controller->layout($config['module_layouts']['Basico']);
            }
        }, 99);
 
    }
    
    /**
     * Método que recebe os erros e as Exceptions que não foram tratadas no código. 
     * Gera um log da Exception e tenta fazer o envio da mesma via email para o suporte.
     * 
     * @param MvcEvent $e
     * @return \Zend\Stdlib\ResponseInterface
     */
    public function handleError(MvcEvent $e) {
        
        # ------------------------------------------------------------------------------
        #   Dados do acesso e do usuario
        # ------------------------------------------------------------------------------
        $uri = $e->getRouter()->getRequestUri();
        $fullUrl = str_replace(':80/', '/', $uri->toString());

        
        //Dados do erro
        $logMsg = "URL: {$fullUrl} \nAcessado por: {$userNome} ({$userCod})\nERRO: ";
        $logHtml = "<b>URL</b>: {$fullUrl} <br><b>Acessado por</b>: {$userNome} ({$userCod})<br><b>ERRO:</b> ";
        # ------------------------------------------------------------------------------
        
        
        # ------------------------------------------------------------------------------
        #   Trata o erro recebido e redireciona para a página adequada
        # ------------------------------------------------------------------------------
        $ex = $e->getParam('exception');
        if ($ex){
            $errorStr = $ex->getMessage()."\n";
            $errorStr.= 'Arquivo: '.$ex->getFile().' - Linha: '.$ex->getLine()."\n";
            $errorStr.= 'Trace: '.$ex->getTraceAsString()."\n";
            
            $errorStrHtml = $ex->getMessage().'<br>';
            $errorStrHtml.= '<b>Arquivo:</b> '.$ex->getFile().' - <b>Linha:</b> '.$ex->getLine().'<br>';
            $errorStrHtml.= '<b>Trace:</b> '.$ex->getTraceAsString();
            
            $errorCode = $ex->getCode();
            if ($errorCode == 403){
                $urlLocation = '/acesso-negado'; 
            } else if ($errorCode == 404){
                $urlLocation = '/error-page/pagina-nao-encontrada';
            } else {
                $urlLocation = '/error-page';
            }            
        }
        else {
            $errorStr = $e->getParam('error');
            $errorStrHtml = $e->getParam('error');            
            
            $urlLocation = '/error-page';
        }
        
        
        # ------------------------------------------------------------------------------
        #   Configura os parâmetros para envio de email
        # ------------------------------------------------------------------------------
        $config = $e->getApplication()->getServiceManager()->get('config');
        if (!$config){            
            die('Arquivo de configuração não encontrado.');
        }
        
       
        
        
        # ------------------------------------------------------------------------------
        #   Seta e cria o diretório de logs
        # ------------------------------------------------------------------------------
        if (empty($config['log']['path'])){
            die('Caminho dos arquivos de log não configurado.');
        }
        $dirFileOnDisc = getcwd() . str_replace('/', '\\', $config['log']['path']);
        
        if (!is_dir($dirFileOnDisc)){
            if (!mkdir($dirFileOnDisc, 0777, true)){
                die('Erro ao gerar arquivo de log. Falha ao criar o diretório.');
            }
        }
        
        
        # ------------------------------------------------------------------------------
        #   Cria o objeto e grava o arquivo de Log
        # ------------------------------------------------------------------------------
        $logger = new \Zend\Log\Logger();
        
        //stream writer
        $filename = date('Ymd_His') . '.txt';
        $_SESSION['erro_nomearquivo'] = $filename;
        
        $writerStream = new \Zend\Log\Writer\Stream($dirFileOnDisc.$filename);
        $logger->addWriter($writerStream);
        
        //Mensagem de log        
        $msg = $logMsg.$errorStr;
        
        //log it!
        $logger->crit($msg);
        # ------------------------------------------------------------------------------
        

        //finaliza o redirecionamento
        $response = $e->getResponse();
        $response->setHeaders(
            $response->getHeaders()->addHeaderLine('Location', $urlLocation)
        );
        $response->setStatusCode(302);
        $response->sendHeaders();
        # ------------------------------------------------------------------------------
        
        return $response;
    }
    
    /**
     * Métodos e Configurações executados ao iniciar
     */    
    public function init(ModuleManager $moduleManager){
        $sharedEvents = $moduleManager->getEventManager()->getSharedManager();
        
        // -----------------------------------------------------------------
        // Verifica se o usuário está logado no sistema
        // Verifica se o perfil do usuário é do tipo Cliente, Funcionario ou Fornecedor
        // -----------------------------------------------------------------
        $sharedEvents->attach('Zend\Mvc\Controller\AbstractController', 'dispatch', function(\Zend\Mvc\MvcEvent $e){
            $controller 		= $e->getTarget();
            $controllerClass 	= get_class($controller);
            $action 			= $e->getRouteMatch()->getParam('action');
            $moduleNamespace 	= substr($controllerClass, 0, strpos($controllerClass, '\\'));

            
        	$sessionStorage = new SessionStorage('Main');
        	$auth = new AuthenticationService();
        	$auth->setStorage($sessionStorage);
        	        	
        	//usuário não logado no sistema
        	if (!$auth->hasIdentity() && $controllerClass != 'Basico\Controller\ErrorPageController'
        	        				  && $controllerClass != 'Basico\Controller\AuthController' 
        	        				){        	            	    

        	    //salva a url que o usuário tentou acessar, para redirecioná-lo após o login
        	    //exceto quando a url for a que verifica automaticamente as mensagens não lidas
        	    if ($action != 'get-qtd-msg-nao-lida'){        	        
            	    $uri = $e->getRouter()->getRequestUri();
            	    $fullUrl = str_replace(':80/', '/', $uri->toString());
            	    $sessionUrl = new Container('Url');
            	    $sessionUrl->offsetSet('last', $fullUrl);
        	    }
        	    
        	    
        	        return $controller->redirect()->toRoute('login');
        	}
        	else {
        	    //Controle de acesso
        	    if ($controllerClass != 'Basico\Controller\ErrorPageController'
        	            && $controllerClass != 'Basico\Controller\MensagemController'
        	            && $controllerClass != 'Basico\Controller\AuthController' ){
        	        
	        	    //usuario logado, verifica o perfil
	        	    $dadosSession = $sessionStorage->read();
	        	    
	        	           	    
        	    }
        	}
        }, 99);

        // --------------------------------------------------
        // Configurações do relatório
        // --------------------------------------------------
        $sharedEvents->attach('Zend\Mvc\Controller\AbstractController', 'dispatch', function($e){
            $controller = $e->getTarget();
            $config 	= $controller->getServiceLocator()->get('config');
            if (isset($config['jasper'])){
                $session = new Container('Report');
                foreach ($config['jasper']['params'] as $param => $value){
                    $session->offsetSet($param, $value);
                }
            }
        }, 98);
       
        // --------------------------------------------------
        // Dados do Git
        // --------------------------------------------------
        $sharedEvents->attach('Zend\Mvc\Controller\AbstractController', 'dispatch', function($e){
            $sessionGit = new SessionStorage('Git');
            if ($sessionGit->isEmpty()){
                $gitInfo = new GitInfo();
                $sessionGit->write($gitInfo);
            }            
        }, 97);
    }
    
    
    public function getServiceConfig(){
    	return array(
    	        'factories' => array(    	                
    	                
    	                
    	                'Basico\Form\MensagemInterna' => function($service){
    	                    //ao criar o form de Mensagem
    	                    $em = $service->get('Doctrine\ORM\EntityManager');
    	                     
    	                    //injeta um array com os FUNCIONARIOS para utilizar na combo
    	                    $repository		= $em->getRepository('Basico\Entity\Funcionario');
    	                    $order 			= array('Nome' => 'ASC');
    	                    $funcionarios 	= $repository->fetchPairs(array(), $order);
    	                    
    	                    //injeta um array com as OBRAS para utilizar na combo
    	                	$repositoryObras	= $em->getRepository('Engenharia\Entity\Obra');
    	                	$order 				= array('Obra' => 'ASC');
    	                	$obras 				= $repositoryObras->fetchPairsAbertas(array(), $order);
    	                	
    	                    return new FrmMensagemInterna(null, $funcionarios,$obras);
    	                },
    	                'Basico\Form\MensagemInternaReply' => function($service){
    	                    //ao criar o form de Mensagem
    	                    $em = $service->get('Doctrine\ORM\EntityManager');
    	                
    	                    //injeta um array com os FUNCIONARIOS para utilizar na combo
    	                    $repository		= $em->getRepository('Basico\Entity\Funcionario');
    	                    $order 			= array('Nome' => 'ASC');
    	                    $funcionarios 	= $repository->fetchPairs(array(), $order);
    	                    
    	                    //ADICIONADO POR PAULO LAVORATTI NO DIA 17/04/2015 MOTIVO: #4818
    	                    //injeta um array com as OBRAS para utilizar na combo
    	                    $repositoryObras	= $em->getRepository('Engenharia\Entity\Obra');
    	                    $order 				= array('Obra' => 'ASC');
    	                    $obras 				= $repositoryObras->fetchPairsAbertas(array(), $order);
    	                    
    	                    
    	                    return new FrmMensagemInternaReply(null, $funcionarios,$obras);
    	                },
    	               
    	                
    	                'Basico\Auth\Adapter' => function ($service){
    	                	return new AuthAdapter($service->get('Doctrine\ORM\EntityManager'));
    	                },
    	                'Basico\Email\Email' => function ($service){
    	                	return new Email($service->get('ViewRenderer'), $service->get('config'));
    	                },
    	               
				)
		);
    }        
    
    public function getViewHelperConfig(){
        return array(
                'invokables' => array(
                        'UserInfo' 				=> new View\Helper\UserInfo(),
                        'LegendaDeCores' 		=> new View\Helper\LegendaDeCores(),
                        'LabelStatus' 			=> new View\Helper\LabelStatus(),
                        'ProgressBar' 			=> new View\Helper\ProgressBar(),
                        'FormularioCampo'		=> new View\Helper\FormularioCampo(),
                        'FormularioCampoSelect'	=> new View\Helper\FormularioCampoSelect(),
                        'GitInfo' 				=> new View\Helper\GitInfo(),
                        'LogoSistema'           => new View\Helper\LogoSistema(),
				)
		);
    }
}
