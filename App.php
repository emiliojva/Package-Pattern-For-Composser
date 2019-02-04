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
        $path = $_SERVER['PATH_INFO'] ?? '/';
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        $this->router = new Router($path, $method);
    }

    public function setRender(PHPRendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    public function get(string $path, $fn)
    {
        $this->router->get($path, $fn);
    }

    public function post(string $path, $fn)
    {
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


                $classResolved = $resolver->class($resultControllerAction['controller']);

                $data = call_user_func_array(array($classResolved, $resultControllerAction['action']), $params); // chamada dinamica de metodo


            } else {
                throw new \Exception('Voce passou um Controller/action invalido:'. $resultControllerAction['controller']. '@' .$resultControllerAction['action'].'Insira uma string com o seguinte padrao: HomeController@index');
            }


        } else {

            if (is_callable($method)) {
                var_dump($method);
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
}
