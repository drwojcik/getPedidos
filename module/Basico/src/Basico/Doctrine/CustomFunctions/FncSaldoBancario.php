<?php

namespace Basico\Doctrine\CustomFunctions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;


class FncSaldoBancario extends FunctionNode {
    
    public $codContaBanco   = null;    
    public $dataAtual       = null;    
    public $mesAtual        = null;    
    public $anoAtual        = null;    
    public $conciliado      = null;
    
    
    public function parse(\Doctrine\ORM\Query\Parser $parser){
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        
        $this->codContaBanco = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_COMMA);
        
        $this->dataAtual = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_COMMA);
        
        $this->mesAtual = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_COMMA);
        
        $this->anoAtual = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_COMMA);
        
        $this->conciliado = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
    

    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker) {
        return 'dbo.FNC_SALDO_BANCARIO('.
                $this->codContaBanco->dispatch($sqlWalker)  . ', ' .
                $this->dataAtual->dispatch($sqlWalker)      . ', ' .
                $this->mesAtual->dispatch($sqlWalker)       . ', ' . 
                $this->anoAtual->dispatch($sqlWalker)       . ', ' .
                $this->conciliado->dispatch($sqlWalker)     .
        ')';
    }
 
}