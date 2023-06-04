<?php namespace Core\Application;

abstract class Runner
{
    abstract function run();

    public function getInstance($classname)
    {
        $initializedParams = $this->initializeParams(
            $this->getParams($this->validateClass($classname))
        );

        return new $classname(...$initializedParams);
    }

    public function initializeParams($params)
    {
        return array_map(fn($param) => $this->validateClass($classname), $params);
    }

    public function getParams(string $classname)
    {
        $reflection = new ReflectionClass(
            $this->validateClass($classname)
        );

        $controllerParams = array_map(
            fn($param) => ['name' => $param, 'type' => $param->getClass()->name],
            $reflection->getConstructor()->getParameters()
        );

        return $controllerParams;
    }

    public function validateClass($classname)
    {
        if (!class_exists($classname)) {
            throw InexistentClass::create($classname);
        }
        return $classname;
    }
}