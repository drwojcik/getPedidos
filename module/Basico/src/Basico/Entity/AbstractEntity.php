<?php
namespace Basico\Entity;

abstract class AbstractEntity
{

    /**
     * Retorna os dados da entidade em um array.
     */
    abstract public function toArray();
    
    /**
     * Retorna os dados da entidade em um array para ser utilizado na tabela/grid.
     */
    abstract public function toTable();
    
    /**
     * Retorna os dados da entidade em um array para ser utilizado no formulário.
     */
    abstract public function toForm();
}

?>