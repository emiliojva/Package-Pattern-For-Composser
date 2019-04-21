<?php
/*
 * classe TLoggerXML
 *  Implementa o algoritmo de LOG em XML
 */

namespace Inovuerj\ADO;

class TLoggerXML extends TLogger
{
    /*
     *  escreve uma mensagem no arquivo de LOG
     * @param  $message = mensagem a ser escrita
     */
    public function write($message)
    {
        $time = date("Y-m-d H:i:s");
        // monta a string
        $text = "<log>\n";
        $text.= "   <time>$time</time>\n";
        $text.= "   <message>$message</message>\n";
        $text.= "</log>\n";
        // adiciona ao final do arquivo
        $handler = fopen("{$this->filename}.xml", 'a');
        fwrite($handler, $text);
        fclose($handler);
    }
}