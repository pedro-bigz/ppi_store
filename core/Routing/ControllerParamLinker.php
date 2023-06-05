<?php namespace Core\Routing;

use Core\Model\Model;
use Core\Request\Request;
use Core\Exceptions\BindParamException;

class ControllerParamLinker
{
    public function __construct(array $dependency, array $params)
    {
        $this->dependency = $dependency;
        $this->params = $params;
    }

    public static function create(array $dependency, array $params)
    {
        return new self($dependency, $params);
    }

    public static function resolve(array $dependency, array $params)
    {
        $linker = self::create($dependency, $params);
        $key = ':'.$linker->dependency['name'];
        
        if (!array_key_exists($key, $linker->params)) {
            if (!is_object($linker->dependency['class'])) {
                throw new InvalidArgumentException("Invalid Parameter ".$linker->dependency['name']);
            }
            return new $linker->dependency['type'];
        }

        if (is_object($linker->dependency['class'])) {
            if (!$linker->instanceof(Model::class)) {
                throw BindParamException::create("The %s dependency has no parameters", $linker->dependency['type']);
            }
            return (new $linker->dependency['type'])->find($linker->params[$key]);
        }

        return $linker->params[$key];
    }

    private function instanceof($classname)
    {
        return $linker->dependency['type'] == $classname || $linker->dependency['class']->isSubclassOf($classname);
    }
}
