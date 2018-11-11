<?php
namespace Basico\View\Helper;

use Zend\View\Helper\AbstractHelper;

class ProgressBar extends AbstractHelper {

    public function __invoke($percentage, $class = '', $corHex = ''){
        $bg = (!empty($corHex) ? 'background: '.$corHex : '');        
        $percentage = number_format($percentage);
        
        $strHtml = "
	        <div class='progress'>
	        	<div class='progress-bar {$class}' style='width: {$percentage}%; {$bg}'>&nbsp;</div>
	        </div>
        ";
        return $strHtml;
    }        
}

?>