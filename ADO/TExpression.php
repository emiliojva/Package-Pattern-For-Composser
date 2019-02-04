<?php
/*
 * classe TExpression
 *  Classe abstrata para 
 *  gerenciar expresses
 */
namespace Inovuerj\ADO;
abstract class TExpression
{
    // operadores lgicos
    const AND_OPERATOR = 'AND ';
    const OR_OPERATOR  = 'OR ';
    
    // marca mtodo dump como obrigatrio
    abstract public function dump();
}
