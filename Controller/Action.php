<?php
/**
 * Created by PhpStorm.
 * User: emili
 * Date: 2/3/2019
 * Time: 3:33 PM
 */

namespace Inovuerj\Controller;

abstract class Action
{
    protected $view;
    private $action;
    protected $content;
    protected $parseToVar = true;
    protected $urlAction;


    private $layout_name;
    private $layout_url;

    private $pathTemplate;

    public function __construct()
    {
        $this->view = new \stdClass();

        $this->init();

    }

    // Renderiza conteudo para com layout ou sem.
    public function render($action, $layout = true, $parser_layout = true)
    {
        $this->action = $action;
        $this->parseToVar = $parser_layout;

        // arquivo layout incluir o metodo $this->content() de forma encadeada
        if ($layout == true && file_exists($this->getLayoutUrl())) {

            if ($this->parseToVar == true) {
                return $this->includeToVar($this->getLayoutUrl());
            } else {
                include_once $this->getLayoutUrl();
            }

        } else {

            // Retornar processamnto do conteudo para um variavel
            if ($this->parseToVar == true) {
                return $this->content();
            }

            $this->content();


        }
    }

    // Pega o conteudo da view pelo nome do Controller. Retorna ou inclui conforme atributo $this->parseToVar
    public function content()
    {
        $current = get_class($this);
        // limpando namespace e deixando a classe somente
        $singleClassName = strtolower((str_replace("Controller", "", str_replace("App\\Controllers\\", "", $current))));

        $this->urlAction = getcwd() . "/../App/Views/" . $singleClassName . "/" . $this->action . ".phtml";

        if ($this->parseToVar == true) {
            return $this->includeToVar($this->urlAction);
        } else {
            include_once $this->urlAction;
        }


    }


    /**
     * Define uma variavel de template com seu respectivo valor
     * @param $var
     * @param $value
     */
    public function setViewVar($var, $value)
    {
        $this->view->{$var} = $value;
    }


    /**
     * @param mixed $urlAction
     */
    public function setUrlAction($urlAction)
    {
        $this->urlAction = $urlAction;
    }


    public function getPathTemplate()
    {
        return $this->pathTemplate;
    }

    public function setLayout($layout_name)
    {
        $this->layout_name = $layout_name;
    }

    public function getLayout()
    {

        if (is_null($this->layout_name)) {
            $this->layout_name = 'default';
        }

        return $this->layout_name;
    }

    public function getLayoutUrl()
    {
//        $this->layout_url = "../App/Views/Layouts/{$this->getLayout()}.phtml";

        $this->layout_url = getcwd() . "/templates/{$this->getLayout()}/index.phtml";
        return $this->layout_url;
    }


    public function getTemplateDir()
    {
//        $this->layout_url = "../App/Views/Layouts/{$this->getLayout()}.phtml";

        $path = getcwd() . "/templates/{$this->getLayout()}/";

        return $path;
    }

    public function getDirBase()
    {
//        $this->layout_url = "../App/Views/Layouts/{$this->getLayout()}.phtml";

        $path = getcwd();

        return $path;
    }

    protected $root_directory;
    protected static $url_base;
    protected $url_template;

    public static function getBaseUrl()
    {

        // removendo barra no final
        return self::$url_base;
    }

    public function getTemplateUrl()
    {

        return $this->getBaseUrl() . "/templates/{$this->getLayout()}/";
    }

    private function init()
    {

        $pathAux = str_replace('/public/', '', $this->getBaseURI());

        $this->root_directory = $_SERVER['DOCUMENT_ROOT'] . "{$pathAux}";

        if (file_exists($this->root_directory)) {

            self::$url_base = "http://{$_SERVER['HTTP_HOST']}/{$pathAux}";

            self::$url_base = preg_replace('/\/$/', '', self::$url_base) . '/';

            $this->url_template = $this->getTemplateUrl();

            /*CONTANTES DE CAMINHOS ABSOLUTOS*/
//            define('CAMINHO_ROOT_DIR', $this->root_directory); // Retorna exemplo : /var/www/sites/inovuerj/

//            define('CAMINHO_ROOT', $this->root_directory); // Retorna exemplo : http://127.0.0.1:8000/

//            define('CAMINHO_ADMIN', CAMINHO_MAINE . "admin/");
        }
    }

    private function getBaseURI()
    {
        $startUrl = strlen($_SERVER["DOCUMENT_ROOT"]);
        // removendo barra no final : preg_replace("/\/$/",'',$aux);


        // pegando apenas uri
        return substr($_SERVER["SCRIPT_FILENAME"], $startUrl, -9);


    }

    private function includeToVar($file)
    {

        if (file_exists($file)) {

            ob_start();
            $data = include_once $file;
            return ob_get_clean();

        }


    }

}
