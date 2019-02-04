<?php

    /*
     * classe TRepository
     *  Esta classe prov os mtodos
     *  necessrios para manipular colees de objetos.
     */
namespace Inovuerj\ADO;

    final class TRepository
    {
        private $class; // nome da classe manipulada pelo repositrio

        /* mtodo __construct()
         *  instancia um Repositrio de objetos
         *  @param $class = Classe dos Objetos
         */
        function __construct($class)
        {
            $this->class = $class;
        }

        /*
         * mtodo load()
         *  Recuperar um conjunto de objetos (collection) da base de dados
         *  atravs de um critrio  de seleo, e instanci-los em memria
         *  @param $criteria = objeto do tipo TCriteria
         */
        function load(TCriteria $criteria,$columns="*")
        {

            $results = array();

            // instancia a instruo de SELECT
            $sql = new TSqlSelect();
            $sql->addColumn($columns);
            $entityName = constant($this->class . '::TABLENAME');
            $sql->setEntity($entityName);
            // atribui o critrio passado como parmetro
            $sql->setCriteria($criteria);
//            echo $sql->getInstruction();

            // inicia transao
            if ($conn = TTransaction::get()) {
                // registra mensagem de log
                TTransaction::log($sql->getInstruction());

                // executa a consulta no banco de dados
                $result = $conn->Query($sql->getInstruction());

                if ($result) {
                    // percorre os resultados da consulta, retornando um objeto

                    while ($row = $result->fetchObject($this->class)) {
                        // armazena no array $results;
                        $results[] = $row;
                    }
                }

                return $results;
            } else {

                // se no tiver transao, retorna uma exceo
                throw new Exception('No h transao ativa !!');
            }
        }

        /*
         * mtodo delete()
         *  Excluir um conjunto de objetos (collection) da base de dados
         *  atravs de um critrio de seleo.
         *  @param $criteria = objeto do tipo TCriteria
         */
        function delete(TCriteria $criteria)
        {
            // instancia instruo de DELETE
            $sql = new TSqlDelete;

            $entityName = constant($this->class . '::TABLENAME');
            $sql->setEntity($entityName);
            // atribui o critrio passado como parmetro
            $sql->setCriteria($criteria);

            // inicia transao
            if ($conn = TTransaction::get()) {
                // registra mensagem de log
                TTransaction::log($sql->getInstruction());
                // executa instruo de DELETE
                $result = $conn->exec($sql->getInstruction());

                return $result;
            } else {
                // se no tiver transao, retorna uma exceo
                throw new Exception('No h transao ativa !!');
            }
        }

        /*
         * mtodo count()
         *  Retorna a quantidade de objetos da base de dados
         *  que satisfazem um determinado critrio de seleo.
         *  @param $criteria = objeto do tipo TCriteria
         */
        function count(TCriteria $criteria)
        {
            // instancia instruo de SELECT
            $sql = new TSqlSelect;
            $sql->addColumn('count(*)');
            $entityName = constant($this->class . '::TABLENAME');
            $sql->setEntity($entityName);
            // atribui o critrio passado como parmetro
            $sql->setCriteria($criteria);

            // inicia transao
            if ($conn = TTransaction::get()) {
                // registra mensagem de log
                TTransaction::log($sql->getInstruction());
                // executa instruo de SELECT
                $result = $conn->Query($sql->getInstruction());
                if ($result) {
                    $row = $result->fetch();
                }

                // retorna o resultado
                return $row[0];
            } else {
                // se no tiver transao, retorna uma exceo
                throw new Exception('No h transao ativa !!');
            }
        }
    }