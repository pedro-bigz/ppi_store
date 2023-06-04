<?php namespace Core\Routing;

use Core\Runner;
use Core\Request\Request;

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
            $this->traceInfo['class_name']
        );

        $this->params = $this->injectMethodDependencys();
    }

    public function run()
    {
        
    }

    public function setTraceInfo()
    {
        [$controller, $method] = explode('::', $this->controller);

        $classname = CONTROLLERS_PATH . '\\' . $controller;

        $this->traceInfo = [
            'class_name' => $classname,
            'method_name' => $method,
            'params' => $this->params,
        ];
    }

    public function getControllerInstance($classname)
    {
        return $this->getInstance($classname);
    }

    public function injectMethodDependencys(array $dependencys = [])
    {
        // foreach ($dependencys as $dependency) {
        //     if ()
        // }
    }
}