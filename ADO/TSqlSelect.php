<?php

/*
 * classe TSqlSelect
 *  Esta classe prov meios
 *  para manipulao de uma instruo
 *  de SELECT no banco de dados
 */
namespace Inovuerj\ADO;

final class TSqlSelect extends TSqlInstruction
{
    private $columns;   // array de colunas a serem retornadas.

    /*
     * mtodo addColumn
     *  adiciona uma coluna a ser
     *  retornada pelo SELECT
     * @param $column = coluna da tabela
     */
    public function addColumn($column)
    {
        // adiciona a coluna no array
        $this->columns[] = $column;
    }

    /*
     * mtodo getInstruction()
     *  retorna a instruo de SELECT
     *  em forma de string.
     */
    public function getInstruction()
    {

        $limit = null;
        $order = null;
        $offset = null;

        // monsta a instruo de SELECT
        $this->sql = 'SELECT ';
        // monta string com os nomes de colunas
        $this->sql .= implode(',', $this->columns);
        // adiciona na clusula FROM o nome da tabela
        $this->sql .= ' FROM ' . $this->entity;

        // obtm a clusula WHERE do objeto criteria.
        if ($this->criteria) {
            $expression = $this->criteria->dump();
            if ($expression) {
                $this->sql .= ' WHERE ' . $expression;
            }

            # GROUP BY DEFINITION
            $group = $this->criteria->getProperty('group');
            if (!empty($group)) {
                $this->sql .= " GROUP BY {$group}";
            }


            // obtm as propriedades do critrio
            $order = $this->criteria->getProperty('order');
            $limit = $this->criteria->getProperty('limit');
            $offset = $this->criteria->getProperty('offset');

            // obtm a ordenao do SELECT
            if ($order) {
                $this->sql .= ' ORDER BY ' . $order;
            }
            if ($limit) {
                $this->sql .= ' LIMIT ' . $limit;
            }
            if ($offset) {
                $this->sql .= ' OFFSET ' . $offset;
            }
        }




        return $this->sql;
    }
}