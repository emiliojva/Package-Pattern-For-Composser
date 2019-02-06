<?php
/**
 * vendor_name\subNamespace\classe
 */

namespace Inovuerj\Router;

use Inovuerj\Helper\Util;

class Router
{
    private $collection;
    private $method;
    private $path;

    public function __construct(string $path, string $method)
    {
        $this->collection = new RouterCollection; // mesno nivel de diretorio nao precisa instaciar classe
        $this->method = $method;
        $this->path = $path;
    }

    public function get($path, $fn)
    {
        $this->request('GET', $path, $fn);
    }

    public function post($path, $fn)
    {
        $this->request('POST', $path, $fn);
    }

    public function request($method, $path, $fn)
    {
        $this->collection->add($method, $path, $fn);
    }

    public function run()
    {

//        echo 'url do browser nomento';
//        var_dump($this->path);

        // pega todos os itens da Collection fitrados por 'GET' , 'POST' OU OUTRO DA CLASS
        $data = $this->collection->filter($this->method); // retorna um arrray de items

        // iterando cada PATH para localizar padrao de rota solicitado
        foreach ($data as $key => $value) {
//            key - 'url pattern da collection';
            $result = $this->checkUrl($key, $this->path); // retorna params

            $callback = $value;

            // Rota da Collection igual a passada no navegador. Encerro Loop
            if ($result['result']) {
                break;
            }
        }

        // se nao tiver resultado. SEM CALLBACK
        if (!$result['result']) {
            $callback = null;
        }

        return [
            'params' => $result['params'],
            'callback' => $callback
        ];
    }

    /**
     * Compara o pattern das, url do browser e url inserida na collections de rotas.
     * Se forem iguais retorna a rota limpa e seus parametros
     * @param string $toFind
     * @param string $subject
     * @return array
     */
    private function checkUrl(string $toFind, string $subject)
    {
        // Verificar se tem regex na routa da collection
        // preg_match_all encontra varias ocorrencias do mesmo pattern solicitado
        // encontra parametros com regex. Envolvidos por chaves {}
        preg_match_all('/\{([^\}]*)\}/', $toFind, $variables);

        // Escapando barra
        $regex = str_replace('/', '\/', $toFind);
        // iterando valores(escapados) de params da rota (sem {}). apenas o que está em parenteses na regex.
        foreach ($variables[1] as $k => $variable) {
            // caso a rota possua parametro customizado com RegEx. Gera um array[0] pra COLUNA & array[1] para REGEX CUSTOMIZADA
            $as = explode(':', $variable);
            // NAO havendo Regex Customizada, colocamos uma default com 'null coalesce' abaixo
            $replacement = $as[1] ? $as[1] :'([a-zA-Z0-9\-\_\ ]+)';
            // Substituindo a regex definida no app.php(get/post) pela regex sem parametro
            $regex = str_replace($variables[$k], $replacement, $regex);
        }

        $regex = preg_replace('/{([a-zA-Z]+)}/', '([a-zA-Z0-9+])', $regex);
        // pegar params
        $result = preg_match('/^' . $regex . '$/', $subject, $params);

        return compact('result', 'params');
    }
}
