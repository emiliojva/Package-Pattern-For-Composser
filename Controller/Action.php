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

    public function __construct()
    {
        $this->view = new \stdClass();
    }

    public function getLayoutUrl()
    {
        $this->layout_url = "../App/Views/Layouts/{$this->getLayout()}.phtml";

        return $this->layout_url;
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
    protected function content()
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