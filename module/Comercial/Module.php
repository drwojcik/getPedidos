<?php

namespace Comercial;

//Zend
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Controller\ControllerManager;
use Zend\EventManager\Event;

//Comercial\Form
use Comercial\Form\Produto As FrmProduto;
use Comercial\Form\Pedido As FrmPedido;
use Comercial\Form\PedidoProduto As FrmPedidoProduto;
use Comercial\Form\ProdutoPesquisa As FrmProdutoPesquisa;


//Comercial\Service
use Comercial\Service\Pedido As SrvPedido;
use Comercial\Service\PedidoProduto As SrvPedidoProduto;
use Comercial\Service\Produto As SrvProduto;




class Module {	
	
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
    public function getServiceConfig(){    	
    	return array(
    	        'factories' => array(
    	               
    	                'Comercial\Service\Produto' => function($service){
    	                return new SrvProduto($service->get('Doctrine\ORM\EntityManager'));
    	                },
    	                'Comercial\Service\Pedido' => function($service){
    	                return new SrvPedido($service->get('Doctrine\ORM\EntityManager'));
    	                },
    	                'Comercial\Service\PedidoProduto' => function($service){
    	                return new SrvPedidoProduto($service->get('Doctrine\ORM\EntityManager'));
    	                },
    	               
    	                'Comercial\Form\Produto' => function($service){
	    	                //recupera o EntityManager
	    					$em = $service->get('Doctrine\ORM\EntityManager');
	    	                
	    	          
	    	                
	    	                return new FrmProduto(null);
    	                },
    	                'Comercial\Form\Pedido' => function($service){
    	                //recupera o EntityManager
    	                $em = $service->get('Doctrine\ORM\EntityManager');
    	                
    	              
    	                return new FrmPedido(null);
    	                },
    	                'Comercial\Form\PedidoProduto' => function($service){
    	                //recupera o EntityManager
    	                $em = $service->get('Doctrine\ORM\EntityManager');
    	                
    	                
    	                return new FrmPedidoProduto(null);
    	                },
    	                'Comercial\Form\ProdutoPesquisa' => function($service){
	    	                //recupera o EntityManager
	    					$em = $service->get('Doctrine\ORM\EntityManager');
	    	             
	    	                return new FrmProdutoPesquisa(null);
    	                },
    	               

    	                
    	        )
    	);
    }        
    
}
