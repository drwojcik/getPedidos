<?php
namespace Basico\PluginController;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class PageTitle extends AbstractPlugin {        

    /**
     * Altera o título da página.
     * 
     * @param array $names
     * @param string $separator
     * @return void
     */
    public function set(array $names, $separator = null){
        $headTitleHelper = $this->getController()->getServiceLocator()->get('viewhelpermanager')->get('headTitle');
        
        if (empty($separator))
            $separator = ' - ';
        
        $headTitleHelper->setSeparator($separator);
        
        foreach ($names as $name){
            $headTitleHelper->append($name);
        }                
    }
}

?>