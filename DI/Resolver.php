<?php
/**
 * Created by PhpStorm.
 * User: emili
 * Date: 01/02/2019
 * Time: 23:22
 */

namespace Inovuerj\DI;

/**
 * Resolver dependencias
 * Class Resolver
 * @package Inovuerj\DI
 */
class Resolver
{
    private $dependencies;

    public function method($method, array $dependencies = [])
    {
        $this->dependencies = $dependencies;

        $info = new \ReflectionFunction($method);         // metodo a ser resolvido. Trás informações de uma function

        $parameters = $info->getParameters();

        $parameters = $this->resolveParameters($parameters);

        return call_user_func_array($info->getClosure(), $parameters);
    }

    public function byClass($class, array $dependencies = [])
    {
        $this->dependencies = $dependencies;

        // Pega informacoes da class
        $class = new \ReflectionClass($class);

        if (!$class->isInstantiable()) {
            throw new \Exception("{$class} is not instantiable");
        }

        $constructor = $class->getConstructor();

        if (!$constructor) {
            return new $class->name;
        }

        $parameters = $constructor->getParameters();
        $parameters = $this->resolveParameters($parameters);

        return $class->newInstanceArgs($parameters);
    }

    private function resolveParameters($parameters)
    {
        $dependencies = [];
        foreach ($parameters as $parameter) {
            $dependency = $parameter->getClass();

            if ($dependency) {
                $dependencies[] = $this->class($dependency->name, $this->dependencies);
            } else {
                $dependencies[] = $this->getDependencies($parameter);
            }
        }

        return $dependencies;
    }

    private function getDependencies($parameter)
    {
        if (isset($this->dependencies[$parameter->name])) {
            return $this->dependencies[$parameter->name];
        }

        if ($parameter->isDefaultValueAvailable()) {
            return $parameter->getDefaultValue();
        }

        throw new \Exception("{parameter->name} not receive a valid value");
    }
}
