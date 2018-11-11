<?php
namespace Basico\View\Helper;

use Zend\View\Helper\AbstractHelper;

class LabelStatus extends AbstractHelper {

    public function __invoke($tipo, $status){
        switch ($tipo){
            case 'Tarefas':
                return $this->statusTarefas($status);
                break;
        	case 'Financeiro':
        	    return $this->statusFinanceiro($status);
        	    break;
        	default:
        	    return "O tipo {$tipo} não possui uma regra de status.";
        	    break;
        	    
        }        
    }
    
    protected function statusTarefas($status){
        switch ($status){
        	case 'P':
        	    return 'Pendente';
        	    break;
        	case 'A':
        	    return 'Em Andamento';
        	    break;
        	case 'C':
        	    return 'Concluído';
        	    break;        	    
        	default:
        	    return 'Indefinido';
        	    break;        	     
        }
    }
    
    protected function statusFinanceiro($status){
        switch ($status){
        	case 'P':
        	    return 'Pendente';
        	    break;
        	case 'A':
        	    return 'Agendado';
        	    break;
        	case 'R':
        	    return 'Realizado';
        	    break;        	    
        	default:
        	    return 'Indefinido';
        	    break;        
        }
    }
}

?>