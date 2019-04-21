<?php
/*
 * classe TLogger
 *  Esta classe prov� uma interface abstrata
 *  para defini��o de algoritmos de LOG
 */
namespace Inovuerj\ADO;
abstract class TLogger
{
    protected $filename; // local do arquivo de LOG
    
    /*
     * mtodo __construct()
     *  instancia um logger
     * @param  $filename = local do arquivo de LOG
     */
    public function __construct($filename)
    {
        $this->filename = $filename;
        // reseta o conte�do do arquivo
        file_put_contents($filename, '');
    }
    
    // define o mtodo write como obrigatrio
    abstract function write($message);
}