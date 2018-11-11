<?php
return array (
	'modules' => array (
		'DoctrineModule',
		'DoctrineORMModule',
		'Jlib', // Biblioteca Própria
        
        // Módulos do SCAM WEB
		'Basico',
		'Comercial',
		
	)
	,
	'module_listener_options' => array (
		'module_paths' => array (
			'./module',
			'./vendor' 
		),
		'config_glob_paths' => array (
			'config/autoload/{,*.}{global,local}.php' 
		) 
	) 
);