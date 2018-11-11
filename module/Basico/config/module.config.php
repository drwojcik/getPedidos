<?php

namespace Basico;

use Zend\Log\Writer\Stream;
use Zend\Log\Logger;

return array(		
	    'router' => array(		    		
	        'routes' => array(
        		'default' => array(
        				'type' => 'Zend\Mvc\Router\Http\Literal',
        				'options' => array(
        						'route'    => '/',
        						'defaults' => array(
        								'controller' => 'home-index',
        								'action'     => 'index',
        						),
        				),
        		),
	            'home' => array(
	            	'type' => 'Zend\Mvc\Router\Http\Literal',
	                'options' => array(
	                	'route'    => '/home/',
	                    'defaults' => array(
	                    	'controller' => 'home-index',
	                        'action'     => 'index',
	                    ),
	                ),
	            ),
        		'home-index' => array(
        			'type' => 'Zend\Mvc\Router\Http\Literal',
        			'options' => array(
        				'route'    => '/home',
        				'defaults' => array(
        					'controller' => 'home-index',
        					'action'     => 'index',
        				),
        			),
        		),
	            'basico' => array(
	            	'type' 		=> 'Segment',
	                'options' 	=> array(
	                	'route'    => '/basico[/:controller[/:action]][/:id][/:child_id]',
	                    'defaults' => array(
	                    	'module'	 => 'basico',
	                        'controller' => 'index',
	                        'action'     => 'index',
	                    ),
	                   	'constraints'	=> array(
	                    	'id' 		=> '[0-9]+',
	                        'child_id' 	=> '[a-zA-Z0-9_-]+',
	                    )
	                ),
	           ),
                //Rota: basico-email-envio-automatico
                'basico-email-envio-automatico' => array(
	                'type' 		=> 'Segment',
	                'options' 	=> array(
	                    'route'    => '/basico/email-envio-automatico[/:action][/:id]',
	                    'defaults' => array(
                                'controller' => 'Basico\Controller\EmailEnvioAutomatico',
                                'action'     => 'index',
                        ),
                        'constraints'	=> array(
                                'id' 	=> '[0-9]+',
                        )
	                ),
	            ),
	            //Rota: basico-mensagem
	            'basico-mensagem' => array(
	                'type' 		=> 'Segment',
	                'options' 	=> array(
	                    'route'    => '/basico/mensagem[/:action][/:id]',
	                    'defaults' => array(
	                        'controller' => 'Basico\Controller\Mensagem',
	                        'action'     => 'index',
	                    ),
	                    'constraints'	=> array(
	                        'id' 	=> '[0-9]+',
	                    )
	                ),
	                'may_terminate' => true,
	                'child_routes' => array(
	                    'wildcard' => array(
	                        'type' => 'Zend\Mvc\Router\Http\Wildcard',
	                        'options' => array(
	                            'key_value_delimiter' => '/',
	                            'param_delimiter' => '/',
	                        ),
	                        'may_terminate' => true,
	                    ),
	                ),
	            ),
	           //Rota: login
	           'login' => array(
	            	'type' => 'Literal',
        			'options' => array(
        				'route'    => '/login',
        				'defaults' => array(
        					'controller' => 'Basico\Controller\Auth',
        					'action'     => 'index',
        				),
					),
	           ),
	           //Rota: logout
	           'logout' => array(
	           		'type' => 'Literal',
					'options' => array(
	                	'route'    => '/logout',
	                    'defaults' => array(
	                    	'controller' => 'Basico\Controller\Auth',
	                        'action'     => 'logout',
	                    ),
	                ),
	           ),
	            //Rota: error-page
				'error-page' => array(
	            	'type' => 'Segment',
					'options' => array(
	                	'route' => '/error-page[/:action]',
						'defaults' => array(
	                    	'controller' => 'error-page',
	                        'action' => 'index'
						)
					)
				),
	        	//Rota: acesso-negado
	        	'acesso-negado' => array(
	        			'type' => 'Segment',
	        			'options' => array(
	        					'route' => '/acesso-negado',
	        					'defaults' => array(
	        							'controller' => 'error-page',
	        							'action' => 'acesso-negado'
	        					)
	        			)
	        	),
	            //Rota: log-download
	            'log-download' => array(
	                'type' => 'Segment',
	                'options' => array(
	                    'route' => '/log[/:filename]',
	                    'defaults' => array(
	                        'controller' => 'Basico\Controller\LogDownload',
	                        'action' => 'index'
	                    ),
	                	'filename' 	=> '[a-zA-Z0-9_-]+',
	                )
	            ),
	            //Rota: download-text-file
	            'basico-download-text-file' => array(
	                'type' => 'Segment',
	                'options' => array(
	                    'route' => '/basico/download-text-file[/:filename]',
	                    'defaults' => array(
	                        'controller' => 'Basico\Controller\DownloadTextFile',
	                        'action' => 'index'
	                    )
	                )
	            ),
	        	//cadastro-check-list
	        	'basico-cliente' => array(
	        			'type' 		=> 'Segment',
	        			'options' 	=> array(
	        					'route'    => '/basico/cliente[/:action][/:id]',
	        					'defaults' => array(
	        							'controller' => 'cliente',
	        							'action'     => 'index',
	        					),
	        					'constraints'	=> array(
	        							'id' => '[0-9]+',
	        					)
	        			),
	        	),
	        	'basico-fornecedor' => array(
	        			'type' 		=> 'Segment',
	        			'options' 	=> array(
	        					'route'    => '/basico/fornecedor[/:action][/:id][/:child_id]',
	        					'defaults' => array(
	        							'controller' => 'fornecedor',
	        							'action'     => 'index',
	        					),
	        					'constraints'	=> array(
	        							'id' => '[0-9]+',
	        							'child_id' => '[0-9]+',
	        					)
	        			),
	        	),
	        	'basico-contatos' => array(
	        			'type' 		=> 'Segment',
	        			'options' 	=> array(
	        					'route'    => '/basico/contatos[/:action][/:id][/:child_id]',
	        					'defaults' => array(
	        							'controller' => 'contatos',
	        							'action'     => 'index',
	        					),
	        					'constraints'	=> array(
	        							'id' => '[0-9]+',
	        							'child_id' => '[0-9]+',
	        					)
	        			),
	        	),
	        	'basico-telefones' => array(
	        			'type' 		=> 'Segment',
	        			'options' 	=> array(
	        					'route'    => '/basico/telefones[/:action][/:id][/:child_id]',
	        					'defaults' => array(
	        							'controller' => 'telefones',
	        							'action'     => 'index',
	        					),
	        					'constraints'	=> array(
	        							'id' => '[0-9]+',
	        							'child_id' => '[0-9]+',
	        					)
	        			),
	        	),
	        	'basico-setor' => array(
	        			'type' 		=> 'Segment',
	        			'options' 	=> array(
	        					'route'    => '/basico/setor[/:action][/:id][/:child_id]',
	        					'defaults' => array(
	        							'controller' => 'setor',
	        							'action'     => 'index',
	        					),
	        					'constraints'	=> array(
	        							'id' => '[0-9]+',
	        							'child_id' => '[0-9]+',
	        					)
	        			),
	        	),
	        	'basico-tipo-funcionario' => array(
	        			'type' 		=> 'Segment',
	        			'options' 	=> array(
	        					'route'    => '/basico/tipo-funcionario[/:action][/:id][/:child_id]',
	        					'defaults' => array(
	        							'controller' => 'tipo-funcionario',
	        							'action'     => 'index',
	        					),
	        					'constraints'	=> array(
	        							'id' => '[0-9]+',
	        							'child_id' => '[0-9]+',
	        					)
	        			),
	        	),
	        	'basico-funcionario' => array(
	        			'type' 		=> 'Segment',
	        			'options' 	=> array(
	        					'route'    => '/basico/funcionario[/:action][/:id][/:child_id]',
	        					'defaults' => array(
	        							'controller' => 'funcionario',
	        							'action'     => 'index',
	        					),
	        					'constraints'	=> array(
	        							'id' => '[0-9]+',
	        							'child_id' => '[0-9]+',
	        					)
	        			),
	        	),
	        	'basico-agenda' => array(
	        			'type' 		=> 'Segment',
	        			'options' 	=> array(
	        					'route'    => '/basico/agenda[/:action][/:id][/:child_id]',
	        					'defaults' => array(
	        							'controller' => 'agenda',
	        							'action'     => 'index',
	        					),
	        					'constraints'	=> array(
	        							'id' => '[0-9]+',
	        							'child_id' => '[0-9]+',
	        					)
	        			),
	        	),
	        	'basico-tipo-agenda' => array(
	        			'type' 		=> 'Segment',
	        			'options' 	=> array(
	        					'route'    => '/basico/tipo-agenda[/:action][/:id][/:child_id]',
	        					'defaults' => array(
	        							'controller' => 'tipo-agenda',
	        							'action'     => 'index',
	        					),
	        					'constraints'	=> array(
	        							'id' => '[0-9]+',
	        							'child_id' => '[0-9]+',
	        					)
	        			),
	        	),
	        	'basico-cliente-cobranca' => array(
	        			'type' 		=> 'Segment',
	        			'options' 	=> array(
	        					'route'    => '/basico/cliente-cobranca[/:action][/:id]',
	        					'defaults' => array(
	        							'controller' => 'cliente-cobranca',
	        							'action'     => 'index',
	        					),
	        					'constraints'	=> array(
	        							'id' => '[0-9]+',
	        							'child_id' => '[0-9]+',
	        					)
	        			),
	        	),
	        	'basico-desativar-pessoas' => array(
	        			'type' 		=> 'Segment',
	       				'options' 	=> array(
	       						'route'    => '/basico/desativar-pessoas[/:action][/:id]',
	       						'defaults' => array(
	       								'controller' => 'desativar-pessoas',
	       								'action'     => 'index',
	       						),
	       						'constraints'	=> array(
	       								'id' => '[0-9]+',
	       								'child_id' => '[0-9]+',
	       						)
	       				),
	       		),
	        	'basico-condicao-pagamento' => array(
	        			'type' 		=> 'Segment',
	        			'options' 	=> array(
	        					'route'    => '/basico/condicao-pagamento[/:action][/:id]',
	        					'defaults' => array(
	        							'controller' => 'condicao-pagamento',
	        							'action'     => 'index',
	        					),
	        					'constraints'	=> array(
	        							'id' => '[0-9]+',
	        							'child_id' => '[0-9]+',
	        					)
	        			),
	        	),
	        	'basico-imprimir-etiqueta' => array(
	        			'type' 		=> 'Segment',
	        			'options' 	=> array(
	        					'route'    => '/basico/imprimir-etiqueta[/:action][/:id]',
	        					'defaults' => array(
	        							'controller' => 'imprimir-etiqueta',
	        							'action'     => 'index',
	        					),
	        					'constraints'	=> array(
	        							'id' => '[0-9]+',
	        							'child_id' => '[0-9]+',
	        					)
	        			),
	        	),
        		'basico-cliente-segmento' => array(
        				'type' 		=> 'Segment',
        				'options' 	=> array(
        						'route'    => '/basico/cliente-segmento[/:action][/:id][/:child_id]',
        						'defaults' => array(
        								'controller' => 'cliente-segmento',
        								'action'     => 'index',
        						),
        						'constraints'	=> array(
        								'id' => '[0-9]+',
        								'child_id' => '[0-9]+',
        						)
        				),
        		),
	        	'basico-gerais' => array(
	        			'type' 		=> 'Segment',
	        			'options' 	=> array(
	        					'route'    => '/basico/gerais[/:action][/:id]',
	        					'defaults' => array(
	        							'controller' => 'gerais',
	        							'action'     => 'index',
	        					),
	        					'constraints'	=> array(
	        							'id' => '[0-9]+',
	        							'child_id' => '[0-9]+',
	        					)
	        			),
	        	),
	            //criado por mateus c dia 09/08/2017 #16198
	            'basico-unidade' => array(
	                'type' 		=> 'Segment',
	                'options' 	=> array(
	                    'route'    => '/basico/unidade[/:action][/:id]',
	                    'defaults' => array(
	                        'controller' => 'unidade',
	                        'action'     => 'index',
	                    ),
	                    'constraints'	=> array(
	                        'id' => '[0-9]+',
	                        'child_id' => '[0-9]+',
	                    )
	                ),
	            ),
	            
        		'basico-pesquisa-generica' => array(
        				'type' 		=> 'Segment',
        				'options' 	=> array(
        						'route'    => '/basico/pesquisa-generica[/:action][/:id]',
        						'defaults' => array(
        								'controller' => 'pesquisa-generica',
        								'action'     => 'index',
        						),
        						'constraints'	=> array(
        								'id' => '[0-9]+',
        								'child_id' => '[0-9]+',
        						)
        				),
        		),
	            //criado por mateus c dia 14/09/2017 #16249
	            'basico-ramo' => array(
	                'type' 		=> 'Segment',
	                'options' 	=> array(
	                    'route'    => '/basico/ramo[/:action][/:id]',
	                    'defaults' => array(
	                        'controller' => 'ramo',
	                        'action'     => 'index',
	                    ),
	                    'constraints'	=> array(
	                        'id' => '[0-9]+',
	                        'child_id' => '[0-9]+',
	                    )
	                ),
	            ),
                'basico-contrato-padrao' => array(
                    'type' 		=> 'Segment',
                    'options' 	=> array(
                        'route'    => '/basico/contrato-padrao[/:action][/:id]',
                        'defaults' => array(
                            'controller' => 'contrato-padrao',
                            'action'     => 'index',
                        ),
                        'constraints'	=> array(
                            'id' => '[0-9]+',
                            'child_id' => '[0-9]+',
                        )
                    ),
                ),
                'basico-contrato-padrao-cliente' => array(
                    'type' 		=> 'Segment',
                    'options' 	=> array(
                        'route'    => '/basico/contrato-padrao-cliente[/:action][/:id][/:child_id]',
                        'defaults' => array(
                            'controller' => 'contrato-padrao-cliente',
                            'action'     => 'index',
                        ),
                        'constraints'	=> array(
                            'id' => '[0-9]+',
                            'child_id' => '[0-9]+',
                        )
                    ),
                ),
                'basico-cliente-pauta' => array(
                    'type' 		=> 'Segment',
                    'options' 	=> array(
                        'route'    => '/basico/cliente-pauta[/:action][/:id][/:child_id]',
                        'defaults' => array(
                            'controller' => 'cliente-pauta',
                            'action'     => 'index',
                        ),
                        'constraints'	=> array(
                            'id' => '[0-9]+',
                            'child_id' => '[0-9]+',
                        )
                    ),
                ),
                'basico-cliente-anexo' => array(
                    'type' 		=> 'Segment',
                    'options' 	=> array(
                        'route'    => '/basico/cliente-anexo[/:action][/:id][/:child_id]',
                        'defaults' => array(
                            'controller' => 'cliente-anexo',
                            'action'     => 'index',
                        ),
                        'constraints'	=> array(
                            'id' => '[0-9]+',
                            'child_id' => '[0-9]+',
                        )
                    ),
                ),

	        
	        ),
	    ), //end of router
        //-------------------------------------------------------------------------------------------
		
        'controllers' => array(
				'invokables' => array(
					'home-index'	=> 'Basico\Controller\IndexController',
				    'cidade'		=> 'Basico\Controller\CidadeController',
				    'Basico\Controller\Auth'	=> 'Basico\Controller\AuthController',
				    'dashboard'		=> 'Basico\Controller\DashboardController',
				    'view-tipo-fornecedor' => 'Basico\Controller\ViewTipoFornecedorController',
				    'cliente'		=> 'Basico\Controller\ClienteController',
				    'fornecedor'	=> 'Basico\Controller\FornecedorController',
				    'error-page'	=> 'Basico\Controller\ErrorPageController',
				    'Basico\Controller\EmailEnvioAutomatico'    => 'Basico\Controller\EmailEnvioAutomaticoController',
				    'Basico\Controller\Mensagem'                => 'Basico\Controller\MensagemController',
				    'Basico\Controller\LogDownload'             => 'Basico\Controller\LogDownloadController',
				    'Basico\Controller\DownloadTextFile'        => 'Basico\Controller\DownloadTextFileController',
					'cliente-contato'	=> 'Basico\Controller\ClienteContatoController',
					'contatos'	=> 'Basico\Controller\ContatosController',
					'telefones'	=> 'Basico\Controller\TelefonesController',
					'setor'	=> 'Basico\Controller\SetorController',
					'tipo-funcionario'	=> 'Basico\Controller\TipoFuncionarioController',
					'funcionario'	=> 'Basico\Controller\FuncionarioController',
					'agenda'	=> 'Basico\Controller\AgendaController',
					'tipo-agenda'	=> 'Basico\Controller\TipoAgendaController',
					'cliente-cobranca'		=> 'Basico\Controller\ClienteCobrancaController',
					'desativar-pessoas'	    => 'Basico\Controller\DesativarPessoasController',
					'condicao-pagamento'    => 'Basico\Controller\CondicaoPagamentoController',
					'imprimir-etiqueta'     => 'Basico\Controller\ImprimirEtiquetaController',
					'cliente-segmento'     => 'Basico\Controller\ClienteSegmentoController',
					'gerais'			=> 'Basico\Controller\GeraisController',
					'pesquisa-generica'	=> 'Basico\Controller\PesquisaGenericaController',
				    'unidade'	=> 'Basico\Controller\UnidadeController',
				    'ramo'	=> 'Basico\Controller\RamoController',
				    'contrato-padrao'	=> 'Basico\Controller\ContratoPadraoController',
				    'contrato-padrao-cliente'	=> 'Basico\Controller\ContratoPadraoClienteController',
                    'cliente-pauta'	=> 'Basico\Controller\ClientePautaController',
                    'basico-cliente-anexo'	=> 'Basico\Controller\ClienteAnexoController'

				),
		),
        //-------------------------------------------------------------------------------------------
        
        'controller_plugins' => array(
                'invokables' => array(
                        'ParamCores' 	=> 'Basico\PluginController\ParamCores',
                        'PageTitle'		=> 'Basico\PluginController\PageTitle',
                )
        ),
        //-------------------------------------------------------------------------------------------
        
	    'service_manager' => array(
	        'factories' => array(
	            	'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
	             	'Navigation' => 'Basico\Navigation\MyNavigationFactory',
	        ),
	    ),
        //-------------------------------------------------------------------------------------------
        
	    'translator' => array(
	        'locale' => 'en_US',
	        'translation_file_patterns' => array(
	            array(
	                'type'     => 'gettext',
	                'base_dir' => __DIR__ . '/../language',
	                'pattern'  => '%s.mo',
	            ),
	        ),
	    ),
        //-------------------------------------------------------------------------------------------
    
	    'view_manager' => array(
	        'display_not_found_reason' => true,
	        'display_exceptions'       => true,
	        'doctype'                  => 'HTML5',
	        'not_found_template'       => 'error/404',
	        'exception_template'       => 'error/index',
	        'template_map' => array(
	            'error/404'			=> __DIR__ . '/../view/error/404.phtml',
	            'error/index'		=> __DIR__ . '/../view/error/index.phtml',
	        ),
	        'template_path_stack' => array(
	            __DIR__ . '/../view',
	        ),
	    ),
        //-------------------------------------------------------------------------------------------
    
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
            'configuration' => array(
                'orm_default' => array(
                    //Custom types
                    'types' => array(
                        'SqlServerDatetime' 		=> 'Basico\Doctrine\Types\SqlServerDatetime',
                        'SqlServerSmalldatetime' 	=> 'Basico\Doctrine\Types\SqlServerSmalldatetime',
                    ),
                    //Custom DQL functions
                    'datetime_functions'    => array(),
                    'string_functions'      => array(),
                    'numeric_functions'     => array(
                        'Fnc_Saldo_Bancario'    => 'Basico\Doctrine\CustomFunctions\FncSaldoBancario',
                        'Cast'                  => 'Basico\Doctrine\CustomFunctions\Cast',
                        'Year'                  => 'Basico\Doctrine\CustomFunctions\Year',
                        'Month'                 => 'Basico\Doctrine\CustomFunctions\Month',
                        'Day'                   => 'Basico\Doctrine\CustomFunctions\Day',
                        'Left'                  => 'Basico\Doctrine\CustomFunctions\Left',
                    ),
                ),
            ),
        ),
        //-------------------------------------------------------------------------------------------
);
