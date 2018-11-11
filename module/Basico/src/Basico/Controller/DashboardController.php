<?php

namespace Basico\Controller;

use Jlib\Util\UtilDate;
use Jlib\View\Html\Tabela\Tabela;

use Zend\View\Model\ViewModel;
use Zend\Authentication\Storage\Session As SessionStorage;
use Zend\Json\Json;
use Jlib\Util\FormatValue;
use Jlib\Ajax\AjaxReturn;

class DashboardController extends CrudController {	


    public function indexAction(){
        //título da página
        $this->PageTitle()->set(array('getPedido','Dashboard'),' :: ');

        //Numero de Pedidos
        $pedidos = $this->getNumeroPedidos();


        return new ViewModel(array(
          
            'pedidos'			      => $pedidos,
          
        ));
    }
    
    public function getTabelaHtml ($where = array()) {
      
    }
    public function getNumeroPedidos () {
        //lista os produtos do pedido em formato de tabela html
        $sql = "SELECT Count(idPedido) as Total FROM Pedido;";
        
        $stmt = $this->getEm ()->getConnection ()->query ( $sql );
        $resultado = $stmt->fetchAll ();
        $Total = $resultado[0]['Total'];
        return $Total;
    }
    
    
    


    



}
