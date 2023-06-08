<?php namespace Core\Routing;

class Routes
{
    public function __construct()
    {
        $this->routes = include_once ROOT_PATH . '/routes/web.php';
    }

    public static function create()
    {
        return new static();
    }

    public function get(string $method, string $uri)
    {
        [$uri, $query] = explode('?', $uri);
        $key = $method . '::' . $uri;
        $parts = explode('/', $key);
        $paths = array_keys($this->routes);

        foreach ($paths as $path) {
            $params = $this->check($path, $method, $parts);
            
            if ($params !== false) {
                return Route::create($method, $this->routes[$path], $params);
            }
        }

        return null;
    }

    private function check(string $path, string $method, array $uriParts)
    {
        if (!str_starts_with($path, $method)) {
            return false;
        }

        $parts = explode('/', $path);

        if (count($parts) != count($uriParts)) {
            return false;
        }

        $params = [];
        foreach ($parts as $key => $part) {
            if (str_starts_with($part, ':')) {
                $params[$part] = $uriParts[$key];
            } else if ($uriParts[$key] !== $part) {
                return false;
            }
        }

        return $params;
    }
}