<?php
namespace Basico\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Cria o html do campo do formulário.
 */
class FormularioCampoSelect extends AbstractHelper {

    protected $_campoNome;
    protected $_campoLabel;
    protected $_campoId;
    protected $_emptyOption;
    protected $_tamanhoLinha;
    protected $_idTagDiv;
    protected $_idTagP;
    protected $_idTagLabel;
    protected $_strHtml;
    
    public function __invoke($campoNome, $campoLabel, $campoId, $emptyOption, $tamanho, $idTagDiv = '', $idTagP = '', $idTagLabel = ''){        

        $this->_campoNome	 	= $campoNome;
        $this->_campoLabel		= $campoLabel;
        $this->_campoId	 		= $campoId;
        $this->_emptyOption		= $emptyOption;
        $this->_tamanhoLinha 	= $tamanho;
        $this->_idTagDiv 		= $idTagDiv;
        $this->_idTagP 			= $idTagP;
        $this->_idTagLabel 		= $idTagLabel;
                
        return $this->render();
    }
    
    protected function render(){
        $this->_strHtml	= '';
        $this->_strHtml.= '<div class="_'.$this->_tamanhoLinha.'" id="'.$this->_idTagDiv.'">';
        $this->_strHtml.= '	<p id="'.$this->_idTagP.'">';
        $this->_strHtml.= '		<label id="'.$this->_idTagLabel.'">'.$this->_campoLabel.'</label>';
        $this->_strHtml.= '		<select name="'.$this->_campoNome.'" id="'.$this->_campoId.'">';
        $this->_strHtml.= '			<option value="">'.$this->_emptyOption.'</option>';
        $this->_strHtml.= '		</select>';
        $this->_strHtml.= '	</p>';
        $this->_strHtml.= '</div>';
                    
        return $this->_strHtml;
    }        
}

?>