<?php

namespace Inovuerj\ADO;
/*
 * classe TSqlUpdate
 *  Esta classe prov meios
 *  para manipulao de uma instruo
 *  de UPDATE no banco de dados
 */
final class TSqlUpdate extends TSqlInstruction
{
    /*
     * mtodo setRowData()
     *  Atribui valores  determinadas
     *  colunas no banco de dados que sero modificadas
     * @param $column = coluna da tabela
     * @param $value  = valor a ser armazenado
     */
    public function setRowData($column, $value)
    {
        // monta um array indexado pelo nome da coluna
        if (is_string($value))
        {
            // adiciona \ em aspas
            $value = addslashes($value);
            
            // caso seja uma string
            $this->columnValues[$column] = "'$value'";
        }
        else if (is_bool($value))
        {
            // caso seja um boolean
            $this->columnValues[$column] = $value ? 'TRUE': 'FALSE';
        }
        else if (isset($value))
        {
            // caso seja outro tipo de dado
            $this->columnValues[$column] = $value;
        }
        else
        {
            // caso seja NULL
            $this->columnValues[$column] = "NULL";
        }
    }
 
    /*
     * mtodo getInstruction()
     *  retorna a instruo de UPDATE
     *  em forma de string.
     */
    public function getInstruction()
    {
        // monsta a string de UPDATE
        $this->sql = "UPDATE {$this->entity}";
        
        // monta os pares: coluna=valor,...
        if ($this->columnValues)
        {
            foreach ($this->columnValues as $column => $value)
            {
                $set[] = "{$column} = {$value}";
            }
        }
        $this->sql .= ' SET ' . implode(', ', $set);
        
        // retorna a clusula WHERE do objeto $this->criteria
        if ($this->criteria)
        {
            $this->sql .= ' WHERE ' . $this->criteria->dump();
        }
        
        return $this->sql;
    }
}
?>