<?php namespace Core\Request;

class Request
{
    public const AJAX = 'XMLHttpRequest';
    public const METHOD_POST = 'POST';
    public const METHOD_GET = 'GET';
    public const METHOD_HEAD = 'HEAD';
    public const METHOD_PUT = 'PUT';
    public const METHOD_PATCH = 'PATCH';
    public const METHOD_DELETE = 'DELETE';

    public $request;
    public $query;
    public $cookies;
    public $server;
    public $files;
    public $headers;
    protected $method;
    protected $session;
    protected $acceptableContentTypes;

    public function __construct(
        array $query = [],
        array $request = [],
        array $cookies = [],
        array $files = [],
        array $server = []
    ) {
        $this->method = null;
        $this->acceptableContentTypes = null;
        $this->request = ItemContainer::create($request);
        $this->query = ItemContainer::create($query);
        $this->cookies = ItemContainer::create($cookies);
        $this->files = FileContainer::create($files);
        $this->server = ServerContainer::create($server);
        $this->headers = ItemContainer::create($this->server->getHeaders());
    }

    public static function getServerAdapterArray(array $server = [])
    {
        $defaultServer = [
            'SERVER_NAME' => SERVER_HOST,
            'SERVER_PORT' => SERVER_PORT,
            'HTTP_HOST' => SERVER_HOST,
            'HTTP_USER_AGENT' => APP_NAME,
            'HTTP_ACCEPT' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'HTTP_ACCEPT_LANGUAGE' => 'en-us,en;q=0.5',
            'HTTP_ACCEPT_CHARSET' => 'ISO-8859-1,utf-8;q=0.7,*;q=0.7',
            'REMOTE_ADDR' => SERVER_ADDR,
            'SCRIPT_NAME' => '',
            'SCRIPT_FILENAME' => '',
            'SERVER_PROTOCOL' => SERVER_PROTOCOL,
            'REQUEST_TIME' => time(),
            'REQUEST_TIME_FLOAT' => microtime(true),
        ];
        return array_replace($defaultServer, $server);
    }

    public static function create(
        string $uri,
        string $method = 'GET',
        array $request = [],
        array $parameters = [],
        array $cookies = [],
        array $files = [],
        array $server = [],
    ) {
        $method = strtoupper($method);
        $urlParts = parse_url($uri);
        $server = static::getServerAdapterArray($server);

        if (isset($urlParts['host'])) {
            $server['SERVER_NAME'] = $urlParts['host'];
            $server['HTTP_HOST'] = $urlParts['host'];
        }

        if (isset($urlParts['user'])) {
            $server['PHP_AUTH_USER'] = $urlParts['user'];
        }

        if (isset($urlParts['pass'])) {
            $server['PHP_AUTH_PW'] = $urlParts['pass'];
        }

        if (!isset($urlParts['path'])) {
            $urlParts['path'] = '/';
        }

        if (in_array($method, [self::METHOD_POST, self::METHOD_PUT, self::METHOD_DELETE])) {
            if (!isset($server['CONTENT_TYPE'])) {
                $server['CONTENT_TYPE'] = 'application/x-www-form-urlencoded';
            }
        }
        if ($method == self::METHOD_PATCH) {
            $request = $parameters;
            $query = [];
        } else {
            $request = [];
            $query = $parameters;
        }

        $queryString = '';
        if (isset($urlParts['query'])) {
            parse_str(html_entity_decode($urlParts['query']), $queryBuffer);

            if ($query) {
                $query = array_replace($queryBuffer, $query);
                $queryString = http_build_query($query, '', '&');
            } else {
                $query = $queryBuffer;
                $queryString = $urlParts['query'];
            }
        } elseif ($query) {
            $queryString = http_build_query($query, '', '&');
        }

        $requestUri = $urlParts['path'];
        if (empty($queryString)) {
            $requestUri .= '?'.$queryString;
        }

        $server['PATH_INFO'] = '';
        $server['REQUEST_METHOD'] = $method;
        $server['SERVER_PORT'] = SERVER_PORT;
        $server['REQUEST_URI'] = $requestUri;
        $server['QUERY_STRING'] = $queryString;
        $server['SECURE'] = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';

        unset($server['HTTPS']);

        return new static($query, $request, $cookies, $files, $server);
    }

    public function ajax()
    {
        dd($this->server);
        // return $this->headers->get('X-Requested-With') == AJAX;
    }
}