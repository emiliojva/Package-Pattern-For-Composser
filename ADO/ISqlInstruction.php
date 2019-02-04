<?php
/**
 * Created by JetBrains PhpStorm.
 * User: BonecosProgramadores
 * Date: 26/01/13
 * Time: 21:12
 * To change this template use File | Settings | File Templates.
 */
namespace Inovuerj\ADO;
interface ISqlInstruction
{
    /*
     *  método getInstruction()
     * declarando-o como <abstract> obrigamos sua declaraçãonas classes filhas,
     * uma vez que seu comportamento será distinto em cada uma delas , configurando polimorfismo.
     */
    public function getInstruction();
}
