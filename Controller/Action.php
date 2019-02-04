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

    public function __construct()
    {
        $this->view = new \stdClass();
    }

    // Renderiza conteudo para com layout ou sem.
    public function render($action, $layout = true, $parser_template = true)
    {
        $this->action = $action;
        $this->parseToVar = $parser_template;

        // arquivo layout incluir o metodo $this->content() de forma encadeada
        if ($layout == true && file_exists("../App/Views/layout.phtml")) {

            if ($this->parseToVar == true) {
                return $this->includeToVar('../App/Views/layout.phtml');
            } else {
                include_once "../App/Views/layout.phtml";
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
    protected function content()
    {
        $current = get_class($this);
        // limpando namespace e deixando a classe somente
        $singleClassName = strtolower((str_replace("Controller", "", str_replace("App\\Controllers\\", "", $current))));

        $this->urlAction = getcwd()."/../App/Views/" . $singleClassName . "/" . $this->action . ".phtml";

        if ($this->parseToVar == true) {
            return $this->includeToVar($this->urlAction);
        } else {
            include_once $this->urlAction;
        }


    }

    /**
     * @param mixed $urlAction
     */
    public function setUrlAction($urlAction)
    {
        $this->urlAction = $urlAction;
    }

    public function includeToVar($file)
    {

        if (file_exists($file)) {

            ob_start();
            $data = include_once $file;
            return ob_get_clean();

        }


    }
}