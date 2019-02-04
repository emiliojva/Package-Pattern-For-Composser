<?php

/*
 * Class Utilidades
 * M?todos para facilitar desenvolvimento
 */

/*
Fun??es P?blicas

troca_titulo($titulo);
Troca o t?tulo da p?gina pela vari?vel passada.

datetime();
Retorna o valor de data/hora padr?o mysql (Ex: "2010-07-26 14:02:51").

datetime_formatado($datetime);
Retorna um array com o datetime passado formatado (Ex: "Array ( [horario] => 14:02:51 [hora] => 14:02 [data] => 26/07/2010 ) ").

js_redireciona($url);
Redireciona para a p?gina repassada em $url via javascript;
*/


namespace Inovuerj\Helper;

final class Util
{

    private function __construct()
    {
    }

    public static function remover_especiais($string)
    {
        $pattern = '/[^a-zA-Z0-9_-]+/i';
        $replacement = '';

        return preg_replace($pattern, $replacement, $string);
    }


    public static function removeAcentos_old2($string)
    {
        return preg_replace('/[`^~\'"]/', null, iconv('UTF-8', 'ASCII//TRANSLIT', $string));
    }

    public static function removeAcentos_old($string, $slug = false)
    {
        $string = strtolower($string);
        // C?digo ASCII das vogais
        $ascii['a'] = range(224, 230);
        $ascii['a'][] = 195;
        $ascii['e'] = range(232, 235);
        $ascii['i'] = range(236, 239);
        $ascii['o'] = array_merge(range(242, 246), array(240, 248));
        $ascii['u'] = range(249, 252);
        // C?digo ASCII dos outros caracteres
        $ascii['b'] = array(223);
        $ascii['c'] = array(231);
        $ascii['d'] = array(208);
        $ascii['n'] = array(241);
        $ascii['y'] = array(253, 255);
        foreach ($ascii as $key => $item) {
            $acentos = '';
            foreach ($item as $codigo) {
                $acentos .= chr($codigo);
            }
            $troca[$key] = '/[' . $acentos . ']/i';
        }
        $string = preg_replace(array_values($troca), array_keys($troca), $string);
        // Slug?
        if ($slug) {
            // Troca tudo que n?o for letra ou n?mero por um caractere ($slug)
            $string = preg_replace('/[^a-z0-9]/i', $slug, $string);
            // Tira os caracteres ($slug) repetidos
            $string = preg_replace('/' . $slug . '{2,}/i', $slug, $string);
            $string = trim($string, $slug);
        }
        return $string;
    }

    public static function mostrar($array, $linha = null)
    {
        echo $linha;
        print "<pre><hr /> ";
        print "<center><i><b>Start Debug>>></b></i></center>";
        print "<hr/><div style='background-color: #f66'>";
        var_dump($array);
        print "</div>";
        print "<hr />";

        print "<hr />";
        print "<div style='background-color: #afa'>";
        print_r($array);
        print "</div>";
        print "<hr />";
        print "<center><i><b><<<< end Debug</b></i></center></pre>";
    }

    public static function datetime()
    {
        return date("Y-m-d H:i:s");
    }

    public static function formata_data($Data, $exibicao = false)
    {
        /*
         * @param exibicao : se habilitado ser? para exibi??o na input ou tabela, sen?o ser? formatado para banco como
         * default
         */


        $Matchs = '';

        $arrDataHora = explode(" ", $Data);
        $somenteData = (count($arrDataHora) > 1 ? trim($arrDataHora[0]) : $Data);
        $somenteHora = (count($arrDataHora) > 1 ? trim($arrDataHora[1]) : '');

        if (!$exibicao) {
            # Validar para insert no mysql#

            # 12/08/1981
            $pattern1 = "/^(0[1-9]|[1-2][0-9]|3[0-1])[\/](0[1-9]|1[0-2])[\/](19|20)[0-9]{2}$/";
            # 12-08-1981
            $pattern2 = "/^(0[1-9]|[1-2][0-9]|3[0-1])[-](0[1-9]|1[0-2])[-](19|20)[0-9]{2}$/";

            if (preg_match($pattern1, $somenteData)) {
                $Matchs = explode('/', $somenteData);
            }
            if (preg_match($pattern2, $somenteData)) {
                $Matchs = explode('-', $somenteData);
            }

            $Data = $Matchs[2] . '/' . $Matchs[1] . '/' . $Matchs[0];
        } else {
            # Validar para Exibi??o no formul?rio#
            $Matchs = explode('-', $somenteData);
            $Data = $Matchs[2] . '/' . $Matchs[1] . '/' . $Matchs[0];
        }

        return (count($arrDataHora) > 1 ? $Data . " " . $somenteHora : $Data);
    }

    public static function titulo($titulo)
    {
        echo "<script language='javascript'>"
            . "document.title='" . $titulo . "'"
            . "</script>";
    }

    public static function datetime_formatado($datetime)
    {
        $retorno['horario'] = substr($datetime, 11, 8);
        $retorno['hora'] = substr($datetime, 11, 5);
        $retorno['data'] = substr($datetime, 8, 2) . "/" . substr($datetime, 5, 2) . "/" . substr($datetime, 0, 4);

        return $retorno;
    }

    public static function data2date($data)
    {
        $data_array = explode("/", $data);
        $date = $data_array[2] . "-" . $data_array[1] . "-" . $data_array[0];

        return $date;
    }

    public static function datahora2datetime($datahora)
    {
        $datahora_array = explode(" ", $datahora);

        $datahora = util::data2date($datahora_array[0]) . " " . $datahora_array[1] . ":00";

        return $datahora;
    }

    public static function js_redireciona($url)
    {
        echo "<script language='javascript'>"
            . "parent.location='" . $url . "'"
            . "</script>";
        exit();
    }


    public static function js_voltar()
    {
        echo "<script language='javascript'>history.back(1);</script>";
        exit();
    }

    public static function capitalize($str)
    {
        return ucwords(strtolower($str));
    }

    public static function removeAcentos($string)
    {

        $string = preg_replace("/[?????]/", "a", $string);
        $string = preg_replace("/[?????]/", "A", $string);
        $string = preg_replace("/[???]/", "e", $string);
        $string = preg_replace("/[???]/", "E", $string);
        $string = preg_replace("/[??]/", "i", $string);
        $string = preg_replace("/[??]/", "I", $string);
        $string = preg_replace("/[?????]/", "o", $string);
        $string = preg_replace("/[?????]/", "O", $string);
        $string = preg_replace("/[???]/", "u", $string);
        $string = preg_replace("/[???]/", "U", $string);
        $string = preg_replace("/?/", "c", $string);
        $string = preg_replace("/?/", "C", $string);

        return $string;
    }

    /**
     * checker artion method http is POST
     * @return bool
     */
    public static function isPost()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // The request is using the POST method
            return true;
        }

        return false;
    }
}
