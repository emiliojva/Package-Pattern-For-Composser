<?php

    /*
     * classe TSqlInsert
     *  Esta classe prov meios
     *  para manipulao de uma instruo
     *  de INSERT no banco de dados
     */
namespace Inovuerj\ADO;

    final class TSqlInsert extends TSqlInstruction
    {
        /*
         * mtodo setRowData()
         *  Atribui valores  determinadas
         *  colunas no banco de dados que sero inseridas
         * @param $column = coluna da tabela
         * @param $value  = valor a ser armazenado
         */
        public function setRowData($column , $value)
        {
            // monta um array indexado pelo nome da coluna
            if (is_string($value)) {
                // adiciona \ em aspas
                $value = addslashes($value);

                // caso seja uma string
                $this->columnValues[ $column ] = "'$value'";
            } else if (is_bool($value)) {
                // caso seja um boolean
                $this->columnValues[ $column ] = $value ? 'TRUE' : 'FALSE';
            } else if (isset($value)) {
                // caso seja outro tipo de dado
                $this->columnValues[ $column ] = $value;
            } else {
                // caso seja NULL
                $this->columnValues[ $column ] = "NULL";
            }
        }

        /*
         * mtodo setCriteria()
         *  No existe no contexto desta classe,
         *  logo, ir lanar um erro ser for executado
         */
//        public function setCriteria($criteria)
//        {
//            // lana o erro
//            throw new Exception("Cannot call setCriteria from " . __CLASS__);
//        }

        /*
         * mtodo getInstruction()
         *  retorna a instruo de INSERT
         *  em forma de string.
         */
        public function getInstruction()
        {
            $this->sql = "INSERT INTO {$this->entity} (";
            // monta uma string contendo os nomes de colunas
            $columns = implode(', ' , array_keys($this->columnValues));
            // monta uma string contendo os valores
            $values = implode(', ' , array_values($this->columnValues));
            $this->sql .= $columns . ')';
            $this->sql .= " values ({$values})";

            return $this->sql;
        }
    }