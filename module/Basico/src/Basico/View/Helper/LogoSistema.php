<?php
namespace Basico\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LogoSistema extends AbstractHelper implements ServiceLocatorAwareInterface {

    private $_logoPrincipal;
    
    private $_serviceLocator;      
    
    public function __invoke() {
        $this->_serviceLocator = $this->getServiceLocator()->getServiceLocator();        
        $config = $this->_serviceLocator->get('config');
        $this->_logoPrincipal = $config['logo']['logo_principal'];
        
        return $this;
    }
    
    /**
     * Retona a logo principal formatada em uma tag <img>
     */
    public function getLogoPrincipal(){
        $imgPath = getcwd().'/public/'.$this->_logoPrincipal;
        if (is_file($imgPath)){            
            return '<img src="'.$this->_logoPrincipal.'" 
    							     style="border-radius: 10px; margin-left: 2px; margin-right: 10px;" />';
        }
        else {
            return '<div style="background-color: #fafafa; border-radius: 10px; color: #666666; float: left; font-size: 12px; 
                                line-height: 18px; margin-left: 2px; margin-right: 10px; padding: 15px 5px; 
                                text-align: center; width: 120px;">
                        Logo não encontrada
                    </div>';
        }
    }
    
    
    /**
     * Set the service locator.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return CustomHelper
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }
    /**
     * Get the service locator.
     *
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }
    
}

?>