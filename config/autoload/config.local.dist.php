<?php

return array(
    //Layout
    'module_layouts' => array(
        'Basico' => 'layout/layout_basico',
    ),
    //Doctrine
    'doctrine' => array(
        'connection' => array(
            'orm_default' => array(
                'driverClass' 	=> 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params'	 	=> array(
                    'host'		=> 'localhost',//Colocque o endereço da base
                    'port'		=> '3306',
                    'user'		=> 'root',//usaurio do banco
                    'password'	=> 'root',//senha do banco
                    'dbname'	=> 'produto_db',//o nome da base...
                ),
                
                
                
            )
        ),
        'configuration'=> array(
            'orm_default' => array ('generate_proxies'=>true)
        )
        
    ),
    
    
    
    //Email
    'email' => array(
        'smtp' => array(
            'host'	=> '',
            'user'	=> '',
            'pass'	=> '',
            'port'  => '',
        ),
        'email_from' => array(
            '' => ''
        ),
        'email_aviso' => array (
            '' => '',
        ),
        'email_suporte_softwar' => array (
            '' => ''
        ),
        'logo' => array (
            
        ),
    ),
    //Logo
    'logo' => array(
        
        'nome-sistema' => 'getPedidos', //nome do sistema no painel do fornecedor
    ),
    //LOG
    'log' => array(
        'path' => '/public/log/error/',
        'notifica_suporte' => false,
    ),
    
);
