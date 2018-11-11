<?php

/**
 * Entidade da tabela "Produto"
 * @author Diego Wojcik <diego@softwar.com.br>
 * @since 2016-12-29
 */
namespace Comercial\Entity;

use Doctrine\Common\Collections\ArrayCollection;

use Jlib\Util\FormatValue;

use Basico\Entity\Configurator;

use Doctrine\ORM\Mapping As ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Produto")
 * @ORM\Entity(repositoryClass="Comercial\Entity\Repository\ProdutoRepository")
 */
class Produto
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    protected $idProduto;
    
    /**
     * @ORM\Column(type="string")
     */
    protected $SKU;
    
    /**
     * @ORM\Column(type="string")
     */
    protected $Nome;
    

    /**
     * @ORM\Column(type="string")
     */
    protected $Descricao;
    
    /**
     * @ORM\Column(type="decimal")
     */
    protected $Preco;
    

    
    /**
     * Configura automaticamente os getters e setters
     * @param string $options
     */
    public function __construct($options = null){
        Configurator::configure($this, $options);
        

    }
    
    public function __toString(){
        return $this->getNome();
    }
    
    public function toArray(){
        return array(
            'idProduto'	  => $this->getidProduto(),
            'SKU'	  => $this->getSKU(),
            'Nome'		  => $this->getNome(),
            'Descricao' 		  => $this->getDescricao(),
            'Preco'  => $this->getPreco(),
        		
        );
    }
    
    public function toForm(){        
        $ret = $this->toArray();
        $formatter = new FormatValue();  
        $ret['Preco'] = $formatter->formatCurrencyToView($ret['Preco']);
        return $ret;
    }
    
    public function toTable(){        
        $ret = $this->toArray();
    	
     
        return $ret;
    }
    
    /**
     * Getters e Setters
     */
    
	/**
     * @return mixed
     */
    public function getIdProduto()
    {
        return $this->idProduto;
    }

    /**
     * @return mixed
     */
    public function getSKU()
    {
        return $this->SKU;
    }

    /**
     * @return mixed
     */
    public function getNome()
    {
        return $this->Nome;
    }

    /**
     * @return mixed
     */
    public function getDescricao()
    {
        return $this->Descricao;
    }

    /**
     * @return mixed
     */
    public function getPreco()
    {
        return $this->Preco;
    }

    /**
     * @param mixed $idProduto
     */
    public function setIdProduto($idProduto)
    {
        $this->idProduto = $idProduto;
    }

    /**
     * @param mixed $SKU
     */
    public function setSKU($SKU)
    {
        $this->SKU = $SKU;
    }

    /**
     * @param mixed $Nome
     */
    public function setNome($Nome)
    {
        $this->Nome = $Nome;
    }

    /**
     * @param mixed $Descricao
     */
    public function setDescricao($Descricao)
    {
        $this->Descricao = $Descricao;
    }

    /**
     * @param mixed $Preco
     */
    public function setPreco($Preco)
    {
        $this->Preco = $Preco;
    }

    
    

            
}

?>