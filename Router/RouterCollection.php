<?php

namespace Inovuerj\Router;

use Illuminate\Support\Collection;

class RouterCollection
{
    protected $collection = [];

    /**
     * abstract,final antes da visibilidade. Ex: abstract public method(){}
     * static depois da visibilidade. Ex: public static method(){}
     */
    public function add(string $method, string $path, $callback)
    {
        if (!isset($this->collection[$method])) { // espaço entre as chaves e metodos
            $this->collection[$method] = new Collection;
        }

        // inclui um closure na collection com um caminho e um callback
        $this->collection[$method]->put($path, $callback);
    }

    public function filter($method)
    {
        if (!isset($this->collection[$method])) {
            $this->collection[$method] = new Collection();
        }
        return $this->collection[$method];
    }

    public function all()
    {
        return $this->collection;
    }
}

/**
 * PSR-1
 * studlyClass Ou upperCamelCase
 * todos os arquivos em UTF-8 sem BOM
 * Constantes são criadas assim: const FIRST_NAME // psr1 - declaração de constantes
 * Metodos sao camelCasel(lowerCamelCase). Asssim: public function addHuman() {}
 */
/**
 * PSR-2
 * primeira coisa é seguir PSR-1
 * identar com espaços e não com tabs (IDE's modernas dando tabs, converte para espaços)
 * Usar ferramenta php_codesniffer (https://packagist.org/packages/squizlabs/php_codesniffer) para corrigir possiveis de
 * Padrão, nas PSRs. Sendo este um pacode de Desenvolvimento.
 * Que deve ser incluído em require-dev ou com --dev no comando require
 */
/**
 * PSR-4
 * Nome do ARQUIVO.PHP deve ser igual ao nome da CLASSE
 */
/**
 * Usando Package Support do Illuminate para lidar com ROTAS
 * https://packagist.org/packages/illuminate/support
 */
