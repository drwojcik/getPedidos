<?php
namespace Basico\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Cria o html do campo do formulário.
 * 
 */
class FormularioCampo extends AbstractHelper {

    protected $_objForm;
    protected $_objFormCampo;
    protected $_objFormCampoFunctionName;
    protected $_campoNome;
    protected $_campoTipo;
    protected $_tamanhoCampo;
    protected $_idTagDiv;
    protected $_idTagLabel;
    protected $_gridClass;
    protected $_strHtml;
    
    /**
     * 
     * @param object $form Objeto do Formulário
     * @param string $campoNome Nome do campo do formulário 
     * @param integer $tamanho Tamanho do campo (1 - 12)
     * @param string $idTagDiv Id da tag Div
     * @param string $idTagLabel Id da tag Label
     * @param string $gridClass Classe da grid da div (boostrap)
     * @throws \Exception
     * @return string
     */
    public function __invoke($form, $campoNome, $tamanho, $idTagDiv = '', $idTagLabel = '', $gridClass = ''){        
        $this->_objForm = $form;
                        
        if (!empty($campoNome)){
        	$this->_objFormCampo = $this->_objForm->get($campoNome);
        	if (empty($this->_objFormCampo)){
        	    throw new \Exception("Falha ao recupar o campo {$campoNome}. Campo não encontrado no formulário.");
        	}
        	$this->_campoNome	 = $campoNome;
        	$this->_campoTipo 	 = $this->_objFormCampo->getAttribute('type');
        } else {
            $this->_campoNome	 = '';
            $this->_campoTipo    = 'empty';
        }
        
        $this->_tamanhoCampo = $tamanho;
        $this->_idTagDiv 	= $idTagDiv;
        $this->_idTagLabel 	= $idTagLabel;
        $this->_gridClass	= (empty($gridClass) ? 'col-md' : $gridClass);
        
        switch ($this->_campoTipo){
            case 'text':
                $this->_objFormCampoFunctionName = 'formInput';                
                break;
			case 'textarea':
            	$this->_objFormCampoFunctionName = 'formTextArea';
                break;
        	case 'hidden':
        	    $this->_objFormCampoFunctionName = 'formHidden';
        	    break;
        	case 'select':
        		$this->_objFormCampoFunctionName = 'formSelect';
        	    break;
			case 'checkbox':
        		$this->_objFormCampoFunctionName = 'formCheckbox';
        	    break;
			case 'radio':
        		$this->_objFormCampoFunctionName = 'formRadio';
        	    break;
        	//Alterado em 27/03/2015 - Diego Wojcik - #6118 - 
        	case 'file':
        	   	$this->_objFormCampoFunctionName = 'formFile';
        	   	break;
        	//Alterado em 10/11/2015 - Diego Wojcik - #8962 - Adicionado tipo Number (spinner)
        	case 'number':
        	   	$this->_objFormCampoFunctionName = 'formNumber';
        	   	break;
        	case 'empty':
        	    break;
        	default:
        	    return "O tipo {$this->_campoTipo} não possui uma regra de renderização.";
        	    break;        	    
        }
        
        return $this->render();
    }
    
    protected function render(){
        $this->_strHtml	= '';
        $functionName 	= $this->_objFormCampoFunctionName;                
        
        if ($this->_campoTipo == 'hidden'){            
            $this->_strHtml.= '	'.$this->view->$functionName($this->_objFormCampo);
        }
        else {
        	$divId = (!empty($this->_idTagDiv) ? ' id="'.$this->_idTagDiv.'"' : '');
        	
            $this->_strHtml.= '<div class="'.$this->_gridClass.'-'.$this->_tamanhoCampo.'"'.$divId.'>';
            
            if (!empty($this->_campoNome)){
                if ($this->_campoTipo == 'checkbox' || $this->_campoTipo == 'radio'){
                	$labelId = (!empty($this->_idTagLabel) ? ' id="'.$this->_idTagLabel.'"' : '');
                	
                    $this->_strHtml.= '	<label'.$labelId.' class="col-xs-12 no-padding">';
                    $this->_strHtml.=       $this->_objFormCampo->getLabel();
                    $this->_strHtml.= ' </label>';
                    $this->_strHtml.=   $this->view->$functionName($this->_objFormCampo);
                }
                else {
                	$labelId = (!empty($this->_idTagLabel) ? ' id="'.$this->_idTagLabel.'"' : '');
                	
                    $this->_strHtml.= '	<label'.$labelId.'>'.$this->_objFormCampo->getLabel().'</label>';
                    $this->_strHtml.= '	'.$this->view->$functionName($this->_objFormCampo);
                }
            } else {
            	$labelId = (!empty($this->_idTagLabel) ? ' id="'.$this->_idTagLabel.'"' : '');
            	
                $this->_strHtml.= '	<label'.$labelId.'>&nbsp;</label>';
            }
            
            $this->_strHtml.= '</div>';
        }
		
        return $this->_strHtml;
    }
}

?>