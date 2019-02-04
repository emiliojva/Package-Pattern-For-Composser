<?php

/*
 * classe TSqlInstruction
 * Esta classe prov os mtodos
 * em comum entre todas instrues
 * SQL (SELECT, INSERT, DELETE e UPDATE)
 */
namespace Inovuerj\ADO;

abstract class TSqlInstruction
{
    protected $sql;         // armazena a instruo SQL
    /**
     * @var TCriteria
     */
    protected $criteria;    // armazena o objeto critrio

    /*
     * mtodo setEntity()
     *  define o nome da entidade (tabela)
     *  manipulada pela instruo SQL
     *  @param $entity = tabela
     */
    final public function setEntity($entity)
    {
        $this->entity = $entity;
    }

    /*
     * mtodo getEntity()
     *  retorna o nome da entidade (tabela)
     */
    final public function getEntity()
    {
        return $this->entity;
    }

    /*
     * mtodo setCriteria()
     *  Define um critrio de seleo dos dados
     *  atravs da composio de um objeto
     *  do tipo TCriteria, que oferece uma
     *  interface para definio de critrios
     *  @param $criteria = objeto do tipo TCriteria
     */
    public function setCriteria(TCriteria $criteria)
    {
        $this->criteria = $criteria;
    }

    /*
     * mtodo getInstruction()
     *  declarando-o como <abstract>
     *  obrigamos sua declarao nas
     *  classes filhas, uma vez que
     *  seu comportamento ser
     *  distinto em cada uma delas,
     *  configurando polimorfismo.
     */
    abstract function getInstruction();

}
