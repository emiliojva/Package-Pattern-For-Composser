<?php
/*
 * classe TLoggerHTML
 *  Implementa o algoritmo de LOG em HTML
 */
namespace Inovuerj\ADO;
class TLoggerHTML extends TLogger
{
    /*
     * m�todo write()
     *  escreve uma mensagem no arquivo de LOG
     * @param  $message = mensagem a ser escrita
     */
    public function write($message)
    {
        $time = date("Y-m-d H:i:s");
        // monta a string
        $text = "<p>\n";
        $text.= "   <b>$time</b> : \n";
        $text.= "   <i>$message</i> <br>\n";
        $text.= "</p>\n";
        // adiciona ao final do arquivo
        $handler = fopen($this->filename, 'a');
        fwrite($handler, $text);
        fclose($handler);
    }
}
?>