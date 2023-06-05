<?php namespace Core\Application;

use ReflectionClass;
use Core\Exceptions\InexistentClass;

abstract class Runner
{
    abstract function run();

    public function getInstance(string $classname)
    {
        $initializedParams = $this->initializeParams(
            $this->getParams($this->validateClass($classname))
        );

        return new $classname(...$initializedParams);
    }

    public function initializeParams($params)
    {
        return array_map(fn($param) => $this->initializeClass($param['type']), $params);
    }

    public function getParams(string $classname)
    {
        $reflection = new ReflectionClass(
            $this->validateClass($classname)
        );

        $contructor = $reflection->getConstructor();

        if (is_null($contructor)) {
            return [];
        }

        $controllerParams = array_map(
            fn($param) => ['name' => $param, 'type' => $param->getClass()->name],
            $contructor->getParameters()
        );

        return $controllerParams;
    }

    public function validateClass(string $classname)
    {
        if (!class_exists($classname)) {
            throw InexistentClass::create($classname);
        }
        return $classname;
    }

    public function initializeClass(string $classname)
    {
        $classname = $this->validateClass($classname);
        return new $classname();
    }
}