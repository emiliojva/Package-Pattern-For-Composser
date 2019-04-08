<?php
/**
 * Created by PhpStorm.
 * User: vieiras
 * Date: 20/03/19
 * Time: 16:36
 */

namespace Inovuerj\ADO;

class TSanitizer
{

    /**
     * @param str => $valor = valor a ser sanitizado
     */
    public static function varchar($valor)
    {
        $valor = str_replace(array('"', "'", '`', '´', '¨'), '', trim($valor));
        return $valor;

    }


    /**
     * @param str => $valor = valor a ser sanitizado
     */
    public static function text($valor)
    {
        return addslashes($valor);
    }

    /**
     * @param str => $email = email a ser sanitizado
     */
    public static function email($email)
    {
        return filter_var($email, FILTER_SANITIZE_EMAIL);
    }

    /**
     * @param str => $valor = valor a ser sanitizado
     */
    public static function filename($valor)
    {
        $valor = str_replace(array('"', "'", '`', '´', '¨'), '', trim($valor));

        $valor = filter_var($valor, FILTER_SANITIZE_STRING);
        return $valor;

    }

    public static function password($valor)
    {
        return filter_var($valor, FILTER_SANITIZE_STRING);
    }

    /**
     * @param str => $valor = valor a ser sanitizado
     */
    public static function integer($valor)
    {
        return (int)$valor;
    }

    /**
     * @param str => $valor = valor a ser sanitizado
     */
    public static function float($valor)
    {
        return (float)$valor;
    }

    /**
     * @param str => $valor = valor a ser sanitizado
     */
    public static function boolean($valor)
    {
        return (bool)$valor;
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
     */
    public static function url($valor)
    {
        $valor = strip_tags(str_replace(array('"', "'", '`', '´', '¨'), '', trim($valor)));
        return filter_var($valor, FILTER_SANITIZE_URL);
    }


    /**
     * @param str => $valor = valor a ser sanitizado
     * @param bol => $allow_accents = permitir acentos
     * @param bol => $allow_spaces = permitir espaços
     */
    public static function alfabetico($valor, $allow_accents = true, $allow_spaces = true)
    {
        $valor = str_replace(array('"', "'", '`', '´', '¨'), '', trim($valor));
        if (!$allow_accents && !$allow_spaces) {
            return preg_replace('#[^A-Za-z]#', '', $valor);
        }
        if ($allow_accents && !$allow_spaces) {
            return preg_replace('#[^A-Za-zà-źÀ-Ź]#', '', $valor);
        }
        if (!$allow_accents && $allow_spaces) {
            return preg_replace('#[^A-Za-z ]#', '', $valor);
        }
        if ($allow_accents && $allow_spaces) {
            return preg_replace('#[^A-Za-zà-źÀ-Ź ]#', '', $valor);
        }
    }

    /**
     * @param str => $valor = valor a ser sanitizado
     */
    public static function alfanumerico($valor, $allow_accents = true, $allow_spaces = true)
    {
        $valor = str_replace(array('"', "'", '`', '´', '¨'), '', trim($valor));
        if (!$allow_accents && !$allow_spaces) {
            return preg_replace('#[^A-Za-z0-9]#', '', $valor);
        }
        if ($allow_accents && !$allow_spaces) {
            return preg_replace('#[^A-Za-zà-źÀ-Ź0-9]#', '', $valor);
        }
        if (!$allow_accents && $allow_spaces) {
            return preg_replace('#[^A-Za-z0-9 ]#', '', $valor);
        }
        if ($allow_accents && $allow_spaces) {
            return preg_replace('#[^A-Za-zà-źÀ-Ź0-9 ]#', '', $valor);
        }
    }


    /**
     * @param str => $valor = valor a ser sanitizado
     */
    public static function numerico($valor)
    {
        $valor = str_replace(array('"', "'", '`', '´', '¨'), '', trim($valor));
        return preg_replace('/\D/', '', $valor);
    }


    /**
     * @param str => $valor = valor a ser sanitizado
     */
    public static function texto($valor)
    {
        return addslashes($valor);
    }


}

// ex:
//var_dump(Sanitizer::numerico('bgusybd458ad8964sdfsd'));
// 4588964