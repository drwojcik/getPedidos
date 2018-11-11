<?php
namespace Basico\Navigation;

use Zend\Navigation\Service\DefaultNavigationFactory;
use Zend\ServiceManager\ServiceLocatorInterface;

class MyNavigation extends DefaultNavigationFactory
{
	
    protected function getPages (ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('config');        

        if ($this->pages === null){
        	$UserInfo 	= $serviceLocator->get('viewhelpermanager')->get('UserInfo');
            $UserPerfil = $UserInfo()->getPerfil();
            
            if ($UserPerfil == 'funcionario')
                $navigationName = 'navigation-funcionario';                
            else if ($UserPerfil == 'cliente')                
                $navigationName = 'navigation-cliente';
            else if ($UserPerfil == 'fornecedor')
                $navigationName = 'navigation-fornecedor';
            else if ($UserPerfil == 'promoter')
                $navigationName = 'navigation-promoter';
            else
                throw new \Exception('Falha ao construir o menu da aplicação. Perfil não definido.');            
            
            $application = $serviceLocator->get('Application');
            $routeMatch  = $application->getMvcEvent()->getRouteMatch();
            $router      = $application->getMvcEvent()->getRouter();
            $pages       = $this->getPagesFromConfig($config[$navigationName][$this->getName()]);
            #$pages = $UserInfo()->getMenuFuncionario();
 
            $this->pages = $this->injectComponents($pages, $routeMatch, $router);
        }
        
        return $this->pages;
    }

}

?>