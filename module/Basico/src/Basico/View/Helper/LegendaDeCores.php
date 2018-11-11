<?php
namespace Basico\View\Helper;

use Zend\View\Helper\AbstractHelper;

class LegendaDeCores extends AbstractHelper {

    public function __invoke(array $data){
        if (count($data)){
            $strHtml = '<span class="label" style="float: left;">Legenda:</span>
						<div style="float: left; margin-left: 10px;">
							<ul class="inline-list">';
            foreach ($data as $linha){
                $strHtml.= $this->renderElement($linha);
            }
            $strHtml.= '	</ul>
            			</div>';
            return $strHtml;
        } else {
        	return 'Dados não informados para formatar a legenda.';
        }
    }

    protected function renderElement(array $element){
    	$strHtml = '<li>
								<span class="label-btn" style="background-color:'.$element['CorHex'].'">&nbsp;</span>
								'.$element['Situacao'].'
						   </li>';
    	return $strHtml;
    }
}

?>