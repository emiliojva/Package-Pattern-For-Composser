<?php

/**
 * Created by PhpStorm.
 * User: emilio
 * Date: 19/11/15
 * Time: 03:00
 */
namespace Inovuerj\ADO;
class PDOConfig extends PDO
{
    /**
     * PDOConfig constructor.
     */
    public function __construct()
    {
        # LATESI
//        $host = 'localhost';
//        $port = '3306';
//        $dbname = 'sectids';
//        $dsn = "mysql:host={$host};port={$port};dbname={$dbname}";
//        $username = 'root';
//        $passwd = 'Latesi@Cempre';
//        $options = null;


        # RESENDE
//        $host = '152.92.236.69';
//        $port = '3306';
//        $dbname = 'sectids';
//        $dsn = "mysql:host={$host};port={$port};dbname={$dbname}";
//        $username = 'inovuerj';
//        $passwd = 'Danshe@2000';
//        $options = null;


        # INOVUERJ
//        $host = 'localhost';
//        $port = '3306';
//        $dbname = 'inovuerj_mapa';
//        $dsn = "mysql:host={$host};port={$port};dbname={$dbname}";
//        $username = 'inovuerj';
//        $passwd = 'danshe2000';
//        $options = null;

        # Local
        $host = 'localhost';
        $port = '3306';
        $dbname = 'sectids';
        $dsn = "mysql:host={$host};port={$port};dbname={$dbname}";
        $username = 'root';
        $passwd = '';
        $options = null;

        /** Overiding construtor pai PDO **/
        parent::__construct($dsn, $username, $passwd, $options);
    }
}
