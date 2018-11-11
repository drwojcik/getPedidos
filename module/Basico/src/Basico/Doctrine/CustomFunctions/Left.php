<?php

namespace Basico\Doctrine\CustomFunctions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;


class Left extends FunctionNode {
    
    public $strValue    = null;
    public $strLength   = null;    
    
    public function parse(\Doctrine\ORM\Query\Parser $parser){
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        
        $this->strValue = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_COMMA);
        
        $this->strLength = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
    

    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker) {
        return 'LEFT('.
                $this->strValue->dispatch($sqlWalker)   . ', ' .
                $this->strLength->dispatch($sqlWalker)  .
        ')';
    }
 
}