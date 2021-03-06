<?php

/*
 * classe TFilter
 *  Esta classe prov uma interface
 *  para definio de filtros de seleo
 */
namespace Inovuerj\ADO;
class TFilter extends TExpression
{
    private $variable;  // varivel
    private $operator;  // operador
    private $value;     // valor
    private $escape;

    /*
     * mtodo __construct()
     *  instancia um novo filtro
     * @param  $variable = varivel
     * @param  $operator = operador (>,<)
     * @param  $value    = valor a ser comparado
     */
    public function __construct($variable, $operator, $value, $escape = true)
    {
        // armazena as propriedades
        $this->variable = $variable;
        $this->operator = $operator;
        $this->escape = $escape;
        // transforma o valor de acordo com certas regras
        // antes de atribuir  propriedade $this->value
        $this->value = $this->transform($value);
    }

    /*
     * mtodo transform()
     *  recebe um valor e faz as modificaes necessrias
     *  para ele ser interpretado pelo banco de dados
     *  podendo ser um integer/string/boolean ou array.
     * @param $value = valor a ser transformado
     */
    private function transform($value)
    {
        // caso seja um array
        if (is_array($value)) {
            // percorre os valores
            foreach ($value as $x) {
                // se for um inteiro
                if (is_integer($x)) {
                    $foo[] = $x;
                } else if (is_string($x) and $this->escape) {
                    // se for string, adiciona aspas
                    $foo[] = "'$x'";
                }
            }
            // converte o array em string separada por ","
            $result = '(' . implode(',', $foo) . ')';
        } // caso seja uma string
        else if (is_string($value) and $this->escape) {
            // adiciona aspas
            $result = "'$value'";
        } // caso seja valor nullo
        else if (is_null($value)) {
            // armazena NULL
            $result = 'NULL';
        } // caso seja booleano
        else if (is_bool($value)) {
            // armazena NULL
            $result = $value ? 'TRUE' : 'FALSE';
        } else {
            $result = $value;
        }
        // retorna o valor
        return $result;
    }

    /*
     * mtodo dump()
     *  retorna o filtro em forma de expresso
     */
    public function dump()
    {
        // concatena a expresso
        return "{$this->variable} {$this->operator} {$this->value}";
    }
}
