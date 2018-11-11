<?php

/**
 * Entidade da tabela "pedidoproduto"
 * @author Diego Wojcik <diegorafael85@gmail.com>
 * @since 2018-11-11
 */
namespace Comercial\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping As ORM;

use Basico\Entity\Configurator;
use Basico\Entity\AbstractEntity;
use Jlib\Util\FormatValue;

/**
 * @ORM\Entity
 * @ORM\Table(name="pedidoproduto")
 * @ORM\Entity(repositoryClass="Comercial\Entity\Repository\PedidoProdutoRepository")
 */
class PedidoProduto extends AbstractEntity
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $idPedidoProduto;
    
    /**
     * @ORM\Column(type="integer")
     */
    protected $QtdeProduto;
    
 
    /**
     * @ORM\Column(type="integer")
     */
    protected $ProdutoIdProduto;
    
    /**
     * @ORM\Column(type="integer")
     */
    protected $PedidoIdPedido;
   
    
    /**
     * Configura automaticamente os getters e setters
     * @param string $options
     */
    public function __construct($options = null){
        Configurator::configure($this, $options);

    }
    
    public function __toString(){
        return $this->getQtdeProduto();
    }
    
    /**
     * @see Basico\Entity.AbstractEntity::toArray()
     */
    public function toArray(){
        return array(
            'idPedidoProduto'			=> $this->getidPedidoProduto(),
            'QtdeProduto'		=> $this->getQtdeProduto(),
            'ProdutoIdProduto'			=> $this->getProdutoIdProduto(),
            'PedidoIdPedido'			=> $this->getPedidoIdPedido(),
             
            
        );
    }
    
    /**
     * @see Basico\Entity.AbstractEntity::toTable()
     */
    public function toTable(){
        $dados = $this->toArray();
        
        $formatter = new FormatValue();
        
        
        return $dados;
    }    
    
    /**
     * @see Basico\Entity.AbstractEntity::toForm()
     */
    public function toForm(){
        return $this->toTable();
    }
    
    /**
     * Getters e Setters
     */
    /**
     * @return mixed
     */
    public function getIdPedidoProduto()
    {
        return $this->idPedidoProduto;
    }

    /**
     * @return mixed
     */
    public function getQtdeProduto()
    {
        return $this->QtdeProduto;
    }

    /**
     * @return mixed
     */
    public function getProdutoIdProduto()
    {
        return $this->ProdutoIdProduto;
    }

    /**
     * @return mixed
     */
    public function getPedidoIdPedido()
    {
        return $this->PedidoIdPedido;
    }

    /**
     * @param mixed $idPedidoProduto
     */
    public function setIdPedidoProduto($idPedidoProduto)
    {
        $this->idPedidoProduto = $idPedidoProduto;
    }

    /**
     * @param mixed $QtdeProduto
     */
    public function setQtdeProduto($QtdeProduto)
    {
        $this->QtdeProduto = $QtdeProduto;
    }

    /**
     * @param mixed $ProdutoIdProduto
     */
    public function setProdutoIdProduto($ProdutoIdProduto)
    {
        $this->ProdutoIdProduto = $ProdutoIdProduto;
    }

    /**
     * @param mixed $PedidoIdPedido
     */
    public function setPedidoIdPedido($PedidoIdPedido)
    {
        $this->PedidoIdPedido = $PedidoIdPedido;
    }

	
}

?>