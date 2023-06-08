<?php namespace Core\Routing;

use Core\Database\Model;
use Core\Request\Request;
use Core\Exceptions\BindParamException;

class ControllerParamLinker
{
    public $dependency;
    public $params;

    public function __construct(array $dependency, array $params)
    {
        $this->dependency = $dependency;
        $this->params = $params;
    }

    public static function create(array $dependency, array $params)
    {
        return new self($dependency, $params);
    }

    public static function resolve(array $dependency, array $params, Request $request)
    {
        $linker = self::create($dependency, $params);
        $key = ':'.$linker->dependency['name'];

        // dd($key, $linker->params);
        // Se não foi listado na rota como paramentro passado pela url
        if (! array_key_exists($key, $linker->params)) {
            if (! is_object($linker->dependency['class'])) {
                throw new InvalidArgumentException("Invalid Parameter ".$linker->dependency['type']);
            }
            // Se for objeto retorna uma instancia dele.
            return $linker->newDependencyInstance();
        }

        // Se foi listado na rota como parametro passado pela url e for objeto
        if (is_object($linker->dependency['class'])) {
            // Se não for uma model lança excessão
            if (! $linker->instanceOf(Model::class)) {
                throw BindParamException::create("The %s dependency has no parameters", $linker->dependency['type']);
            }
            // Se for uma model cria instancia e inicializa com id = $linker->params[$key]
            return $linker->newDependencyInstance()->find($linker->params[$key]);
        }

        // Se foi listado e não é objeto retorna o valor
        return $linker->params[$key];
    }

    public function getDependencyType()
    {
        return $this->dependency['type'];
    }

    public function newDependencyInstance()
    {
        return factory($this->getDependencyType());
    }

    public function instanceOf($classname)
    {
        return $this->dependency['type'] == $classname || $this->dependency['class']->isSubclassOf($classname);
    }
}
