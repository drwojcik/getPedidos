<?php

namespace Comercial;

return array(		
	    'router' => array(		    		
	        'routes' => array(
	           'comercial-home' => array(
	              	'type' 		=> 'Zend\Mvc\Router\Http\Literal',
					'options' => array(
	                	'route'    => '/comercial/',
	                    'defaults' => array(
	                    	'controller' => 'comercial-index',
	                        'action'     => 'index',
	                	),
	            	),
	            ),
	        	'comercial' => array(
	        		'type' 		=> 'Segment',
	        		'options' 	=> array(
	        			'route'    => '/comercial[/:controller[/:action]][/:id][/:child_id][/:child_id2]',
	        			'defaults' => array(
	        			    'module'	 => 'comercial',
	        				'controller' => 'index',
	        				'action'     => 'index',
	        			),
        				'constraints'	=> array(
        					'id' 		=> '[0-9]+',
        					'child_id' 	=> '[a-zA-Z0-9_-]+',
                            'child_id2' 		=> '[0-9]+',
        				)
	        		),
	        	),
	        




	        ),
	    ),
		'controllers' => array(
				'invokables' => array(					
						'comercial-index'			=> 'Comercial\Controller\IndexController',
				        'produto' 					=> 'Comercial\Controller\ProdutoController',
				        'pedido'					=> 'Comercial\Controller\PedidoController',
				        'pedido-produto'			=> 'Comercial\Controller\PedidoProdutoController',
				),
		),
	    'view_manager' => array(
	        'display_not_found_reason' => true,
	        'display_exceptions'       => true,
	        'doctype'                  => 'HTML5',
	        'not_found_template'       => 'error/404',
	        'exception_template'       => 'error/index',
	        'template_map' => array(
	            'error/404'               	=> __DIR__ . '/../view/error/404.phtml',
	            'error/index'             	=> __DIR__ . '/../view/error/index.phtml',
        		'comercial/form'            => __DIR__ . '/../view/comercial/form.phtml',
	        ),
	        'template_path_stack' => array(
	            __DIR__ . '/../view',
	        ),
	    ),
		'doctrine' => array(
			'driver' => array(
				__NAMESPACE__ . '_driver' => array(
					'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
					'cache' => 'array',
					'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')
				),
				'orm_default' => array(
					'drivers' => array(
						__NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
					),
				),
			),
		),           
);
