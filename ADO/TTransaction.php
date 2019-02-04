<?php

/*
 * classe TTransaction
 *  Esta classe prov os mtodos
 *  necessrios manipular transaes
 */

namespace Inovuerj\ADO;

final class TTransaction
{
    /**
     * @var PDO
     */
    private static $conn;     // conexo ativa
    private static $logger;   // objeto de LOG

    /*
     * mtodo __construct()
     *  Est declarado como private para
     *  impedir que se crie instncias de TTransaction
     */
    private function __construct()
    {
    }

    /*
     * mtodo open()
     *  Abre uma transao e uma conexo ao BD
     *  @param $database = nome do banco de dados
     */
    public static function open($database)
    {
        // abre uma conexo e armazena
        // na propriedade esttica $conn
        if (empty(self::$conn)) {
            self::$conn = TConnection::open($database);
            // inicia a transao
            self::$conn->beginTransaction();
            // desliga o log de SQL
            self::$logger = NULL;
        }
    }

    /**
     * m�todo <b>get()</b>
     * Retorna a conexo ativa da transa��o
     * @return PDO
     */
    public static function get()
    {
        // retorna a conexo ativa
        return self::$conn;
    }

    /*
     * mtodo rollback()
     *  Desfaz todas operaes realizadas na transao
     */
    public static function rollback()
    {
        if (self::$conn) {
            // desfaz as operaes realizadas
            // durante a transao
            self::$conn->rollback();
            self::$conn = NULL;
        }
    }

    /*
     * mtodo close()
     *  Aplica todas operaes realizadas e fecha a transao
     */
    public static function close()
    {
        if (self::$conn) {
            // aplica as operaes realizadas
            // durante a transao
            $commit = self::$conn->commit();
            self::$conn = NULL;

            return $commit;
        }
    }

    /*
     * mtodo setLogger()
     *  define qual estratgia (algoritmo de LOG ser usado)
     */
    public static function setLogger(TLogger $logger)
    {
        self::$logger = $logger;
    }

    /*
     * mtodo log()
     *  armazena uma mensagem no arquivo de LOG
     *  baseada na estratgia ($logger) atual
     */
    public static function log($message)
    {
        // verifica existe um logger
        if (self::$logger) {
            self::$logger->write($message);
        }
    }
}