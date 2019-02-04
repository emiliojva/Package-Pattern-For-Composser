<?php

/*
 * classe TCriteria
 *  Esta classe prov uma interface
 *  utilizada para definio de critrios
 */
namespace Inovuerj\ADO;
class TCriteria extends TExpression
{
    private $expressions;  // armazena a lista de expresses
    private $operators;    // armazena a lista de operadores
    private $properties;   // propriedades do critrio

    /*
     * mtodo add()
     *  adiciona uma expresso ao critrio
     * @param  $expression = expresso (objeto TExpression)
     * @param  $operator   = operador lgico de comparao
     */
    public function add(TExpression $expression, $operator = self::AND_OPERATOR)
    {
        // na primeira vez, no precisamos de operador lgico para concatenar
        if (empty($this->expressions)) {
            $operator = NULL;
        }
        // agrega o resultado da expresso  lista de expresses
        $this->expressions[] = $expression;
        $this->operators[] = $operator;
    }

    /*
     * mtodo dump()
     *  retorna a expresso final
     */
    public function dump()
    {
        $result = '';
        // concatena a lista de expresses
        if (is_array($this->expressions)) {
            foreach ($this->expressions as $i => $expression) {
                $operator = $this->operators[$i];
                // concatena o operador com a respectiva expresso
                $result .= $operator . $expression->dump() . ' ';
            }
            $result = trim($result);

            return "({$result})";
        }
    }

    /*
     * mtodo setProperty()
     *  define o valor de uma propriedade
     * @param  $property = propriedade
     * @param  $value    = valor
     */
    public function setProperty($property, $value)
    {
        $this->properties[$property] = $value;
    }

    /*
     * mtodo getProperty()
     *  retorna o valor de uma propriedade
     * @param  $property = propriedade
     */
    public function getProperty($property)
    {
        return (isset($this->properties[$property])) ? $this->properties[$property] : '';
    }
}
