<?php

/**
 * Entidade da tabela "Pedido"
 * @author Diego Wojcik <diegorafael85@gmail.com>
 * @since 2018-11-11
 */
namespace Comercial\Entity;

use Doctrine\Common\Collections\ArrayCollection;

use Jlib\Util\FormatValue;

use Basico\Entity\Configurator;

use Doctrine\ORM\Mapping As ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="pedido")
 * @ORM\Entity(repositoryClass="Comercial\Entity\Repository\PedidoRepository")
 */
class Pedido
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    protected $idPedido;
    
    /**
     * @ORM\Column(type="decimal")
     */
    protected $Total;
    
   
    /**
     * @ORM\Column(type="date")
     */
    protected $Data;
     
    /**
     * Configura automaticamente os getters e setters
     * @param string $options
     */
    public function __construct($options = null){
        Configurator::configure($this, $options);
        
    }
    
    public function __toString(){
        return $this->getNome_Fantasia();
    }
    
    public function toArray(){
        return array(
            'idPedido'	=> $this->getIdPedido(),
                'Total'	=> $this->getTotal(),
            'Data'	=> $this->getData(),
           
        );
    }
    
    public function toForm(){        
        $ret = $this->toArray();
        
        $formatter = new FormatValue();        
        $ret['Data']	= empty($ret['Data']) ? '00/00/0000' : $formatter->formatDateToView($ret['Data']);
        $ret['Total'] = $formatter->formatCurrencyToView($ret['Total']);
        
        return $ret;
    }
    
    /**
     * Getters e Setters
     */
    /**
     * @return mixed
     */
    public function getIdPedido()
    {
        return $this->idPedido;
    }

    /**
     * @return mixed
     */
    public function getTotal()
    {
        return $this->Total;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->Data;
    }

    /**
     * @param mixed $idPedido
     */
    public function setIdPedido($idPedido)
    {
        $this->idPedido = $idPedido;
    }

    /**
     * @param mixed $Total
     */
    public function setTotal($Total)
    {
        $this->Total = $Total;
    }

    /**
     * @param mixed $Data
     */
    public function setData($Data)
    {
        $this->Data = $Data;
    }

	
            
}

?>