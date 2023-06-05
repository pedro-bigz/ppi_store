<?php namespace Core\Routing;

use ReflectionMethod;
use Core\Request\Request;
use Core\Application\Runner;

class ControllerRunner extends Runner
{
    private $controller;
    private $params;
    private $request;
    private $traceInfo;

    public function __construct(string $controller, array $params, Request $request)
    {
        $this->controller = $controller;
        $this->request = $request;
        $this->params = $params;
    }

    public function config()
    {
        $this->setTraceInfo();

        $this->controller = $this->getControllerInstance(
            $this->traceInfo['classname']
        );

        $this->params = $this->injectMethodDependencys(
            $this->getControllerMethodDependencys()
        );

        var_dump($this->params);

        return $this;
    }

    public function run()
    {
        
    }

    public function setTraceInfo()
    {
        [$controller, $method] = explode('::', $this->controller);

        $classname = CONTROLLERS_PATH . '\\' . $controller;

        $this->traceInfo = [
            'classname' => $classname,
            'methodname' => $method,
            'params' => $this->params,
        ];
    }

    public function getControllerInstance($classname)
    {
        return $this->getInstance($classname);
    }

    public function getControllerMethodDependencys()
    {
        $reflection = new ReflectionMethod(
            $this->controller, $this->traceInfo['methodname']
        );

        $controllerParams = array_map(
            fn($param) => ['name' => $param, 'type' => $param->getType()->__toString(), 'class' => $param->getClass()],
            $reflection->getParameters()
        );
        
        return $controllerParams;
    }

    public function injectMethodDependencys(array $dependencys = [])
    {
        return array_map(function($dependency) {
            return ControllerParamLinker::resolve($dependency, $this->params);
        }, $dependencys);
    }
}