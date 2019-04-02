<?php
/**
 * Created by PhpStorm.
 * User: vieiras
 * Date: 20/03/19
 * Time: 16:36
 */

namespace Inovuerj\ADO;

class TValidator
{

    /**
     * @param str => $valor = valor a ser sanitizado
     */
    public static function varchar($valor)
    {
        $valor = str_replace(array('"', "'", '`', '´', '¨'), '', trim($valor));
        return preg_match("/^(.*)$/", $valor, $matches);

    }


    /**
     * @param str => $valor = valor a ser sanitizado
     */
    public static function text($valor)
    {
//        return preg_match("/(.*), (.*)/", $valor, $matches);
        return preg_match("/(.*)/", $valor, $matches);
    }


    /**
     * @param str => $valor = valor a ser sanitizado
     */
    public static function integer($valor)
    {
//        var_dump((int)$valor); die;
        return filter_var($valor, FILTER_VALIDATE_INT);
//        return is_int($valor);
    }

    /**
     * @param str => $valor = valor a ser sanitizado
     */
    public static function float($valor)
    {
        return filter_var($valor, FILTER_VALIDATE_FLOAT);
//        return is_float($valor);
    }

    /**
     * @param str => $valor = valor a ser sanitizado
     */
    public static function boolean($valor)
    {
        return filter_var($valor, FILTER_VALIDATE_BOOLEAN);
//        return is_bool($valor);
    }

    /**
     * @param str => $email = email a ser sanitizado
     */
    public static function email($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }


    public static function password($valor)
    {
        return preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/", $valor, $matches);

    }

    /**
     * @param str => $valor = valor a ser sanitizado
     */
    public static function filename($valor)
    {
        $valor = str_replace(array('"', "'", '`', '´', '¨'), '', trim($valor));
        return preg_match("/^[^.][A-z0-9\-\_\.]+[^.]$/", $valor, $matches);

    }

    /**
     * @param str => $valor = valor a ser sanitizado
     */
    public static function url($valor)
    {
        $valor = strip_tags(str_replace(array('"', "'", '`', '´', '¨'), '', trim($valor)));
        return filter_var($valor, FILTER_VALIDATE_URL);
    }

    /**
     * @param str => $valor = valor a ser sanitizado
     */
    public static function money($valor)
    {
        $valor = preg_replace('/\D/', '', $valor);
        if (strlen($valor) < 3) {
            $valor = substr($valor, 0, strlen($valor)) . '.00';
            return (float)$valor;
        }
        if (strlen($valor) > 2) {
            $valor = substr($valor, 0, (strlen($valor) - 2)) . '.' . substr($valor, (strlen($valor) - 2));
            return (float)$valor;
        }
    }


    /**
     * @param str => $valor = valor a ser sanitizado
     * @param bol => $allow_accents = permitir acentos
     * @param bol => $allow_spaces = permitir espaços
     */
    public static function alfabetico($valor)
    {
        $valor = str_replace(array('"', "'", '`', '´', '¨'), '', trim($valor));
        return preg_match("/^[A-Za-zà-źÀ-Ź ]+$/", $valor, $matches);

    }


    /**
     * @param str => $valor = valor a ser sanitizado
     */
    public static function alfanumerico($valor)
    {
        $valor = str_replace(array('"', "'", '`', '´', '¨'), '', trim($valor));
        return preg_match("/^[A-Za-zà-źÀ-Ź0-9 ]+$/", $valor, $matches);

    }


    /**
     * @param str => $valor = valor a ser sanitizado
     */
    public static function texto($valor)
    {
        return preg_match("/(.*), (.*)/", $valor, $matches);
    }

    /**
     * @param str => $valor = valor a ser sanitizado
     */
    public static function numerico($valor)
    {
//        Util::mostrar(preg_match('/[0-9]+/', $valor));
        return preg_match('/[0-9]+/', $valor);
    }

}

// ex:
//var_dump(Sanitizer::numerico('bgusybd458ad8964sdfsd'));
// 4588964