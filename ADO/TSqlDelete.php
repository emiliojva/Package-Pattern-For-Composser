<?php
/*
 * classe TSqlDelete
 *  Esta classe prov meios
 *  para manipulao de uma instruo
 *  de DELETE no banco de dados
 */

namespace Inovuerj\ADO;
final class TSqlDelete extends TSqlInstruction
{
    /*
     * mtodo getInstruction()
     *  retorna a instruo de DELETE
     *  em forma de string.
     */
    public function getInstruction()
    {
        // monta a string de DELETE
        $this->sql  = "DELETE FROM {$this->entity}";
        
        // retorna a clusula WHERE do objeto $this->criteria
        if ($this->criteria)
        {
            $expression = $this->criteria->dump();
            if ($expression)
            {
                $this->sql .= ' WHERE ' . $expression;
            }
        }
        return $this->sql;
    }
}
?>