<?php
/**
 * Created by PhpStorm.
 * User: emili
 * Date: 02/02/2019
 * Time: 21:53
 */

namespace Inovuerj;

use Inovuerj\DI\Resolver;
use Inovuerj\Renderer\PHPRendererInterface;
use Inovuerj\Router\Router;

class App
{
    private $router;
    /**
     * @var PHPRendererInterface
     */
    private $renderer;

    public function __construct()
    {
        $path = $this->getUri();
        $method = !empty($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';

        $path= preg_replace('/^(\/public)/','',$path);

        $this->router = new Router($path, $method);
    }

    public function setRender(PHPRendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    public function get($path, $fn)
    {
        if(is_string($path)){
            $this->router->get($path, $fn);
        }

    }

    public function post($path, $fn)
    {
        if(!is_string($path)){
            throw new \Exception("\$path precisa ser do tipo string");
        }

        $this->router->post($path, $fn);
    }

    public function run()
    {
        $route = $this->router->run(); // retorna um array com uma closure(function) da rota e seus parametros


        $method = $route['callback']; // closure
        $params = $route['params']; // params da closure
        $data = null;




        // Resolve um metodo, e seus parametros.
        $resolver = new Resolver;

        // Executando Controller por chamadas de string no padrao: SomeController@action
        if (is_string($method)) {

            // removendo url completa da lista de params do metodo
            unset($params[0]);

            // Retorna um Controller e Action validos.
            $resultControllerAction = $this->checkControllerAction($method);


            // Se o array retornado estiver preenchido com o controller e action, executos
            if ($resultControllerAction['controller'] && $resultControllerAction['action']) {


                $classResolved = $resolver->byClass($resultControllerAction['controller']);

                $data = call_user_func_array(array($classResolved, $resultControllerAction['action']), $params); // chamada dinamica de metodo


            } else {
                throw new \Exception('Voce passou um Controller/action invalido:' . $resultControllerAction['controller'] . '@' . $resultControllerAction['action'] . 'Insira uma string com o seguinte padrao: HomeController@index');
            }


        } else {

            if (is_callable($method)) {
                $data = $resolver->method($method, ['params' => $params]);
            }

        }


        $this->renderer->setData($data);// Pega o retorno resolvido da Rota (Incluindo params) e seta como conteudo do renderer
        // Renderiza
        $this->renderer->run();
    }

    private function checkControllerAction($subject)
    {
        if (preg_match('/^(([A-Z]{1}[a-z]+)+Controller)@([a-z]{1}[a-zA-Z0-9_-]+)$/', $subject, $variables)) {

            if (!empty($variables[0])) {
                $controller = $variables[1];
                $action = $variables[3];

                return [
                    'controller' => "App\\Controllers\\" . $controller,
                    'action' => $action
                ];
            }

            return [];

        }


    }

    protected function getUri()
    {

//        $path = $_SERVER['PATH_INFO'] ?? '/';

        $path = "";


        if (!empty($_SERVER['REQUEST_URI'])) {
            $path = urldecode($_SERVER['REQUEST_URI']);
        }
        if (!empty($_SERVER['PATH_INFO'])) {
            $path = $_SERVER['PATH_INFO'];
        } else if (!empty($_SERVER['REDIRECT_URL'])) {
            $path = $_SERVER['REDIRECT_URL'];
        } else {
            $path = "/";
        }


//        var_dump($path);
//        die;

//        var_dump($_SERVER);

        return parse_url($path, PHP_URL_PATH);
    }
}
