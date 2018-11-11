<?php
return array (
    'navigation-funcionario' => array (
        'default' => array (
            'dashboard' => array (
                'label' => 'Dashboard',
                'route' => 'basico',
                'controller' => 'dashboard',
                'icon' => 'fa fa-desktop'
            ), // End of dashboard
            // -------------------------------------------------------------------------------------------------------
            'produto' => array (
                'label' => 'Produtos',
                'icon' => 'fa fa-gift',
                'route' 	 => 'comercial',
                'module' 	 => 'comercial',
                'controller' => 'produto',
            ), // End of produto
            'pedido' => array (
                'label' => 'Pedidos',
                'icon' => 'fa fa-truck',
                'route' 	 => 'comercial',
                'module' 	 => 'comercial',
                'controller' => 'pedido',
            ), // End of pedido
            
            
            
            
            //--------------------------------------------------------------------------------------------------------
        )
    )
);