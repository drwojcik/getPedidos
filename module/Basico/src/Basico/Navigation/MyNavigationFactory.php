<?php
namespace Basico\Navigation;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MyNavigationFactory implements FactoryInterface
{
	public function createService (ServiceLocatorInterface $serviceLocator)
    {
        $navigation = new MyNavigation();
        return $navigation->createService($serviceLocator);
        
        /*try {
            return $navigation->createService($serviceLocator);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }*/      
    }    
}

?>