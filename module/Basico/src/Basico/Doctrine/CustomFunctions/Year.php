<?php

namespace Basico\Doctrine\CustomFunctions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;

class Year extends FunctionNode {
    
    public $valor = null;    
    
    public function parse(\Doctrine\ORM\Query\Parser $parser){
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);        
        $this->valor = $parser->ArithmeticPrimary();        
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
    

    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker) {
        return 'YEAR('.
                $this->valor->dispatch($sqlWalker) .
        ')';
    }
 
}