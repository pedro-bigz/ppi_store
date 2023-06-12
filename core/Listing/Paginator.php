<?php namespace Core\Listing;

use Core\Database\Model;
use Core\Request\Request;

class Paginator
{
    private $model;
    private $orderBy;
    private $orderDirection;
    private $search;
    private $searchIn;
    private $searchQuery;
    private $preparedPagination;
    private $maxWordsInSearch = 5;

    private $data;
    private $perPage;
    private $currentPage;
    private $total;
    private $numPages;

    public function __construct(string $model)
    {
        $this->model = factory($model);
    }

    public static function create($model) {
        return new static($model);
    }

    public function getModel()
    {
        return $this->model;
    }

    public function processRequest(
        Request $request,
        array $columns = ['*'],
        array $searchIn = [], 
        string|null $orderBy = null,  
        string $orderDirection = 'asc',        
    ) {
        $this->attachOrdering(
            $request->get('orderBy', $orderBy ?: $this->model->getKeyName()),
            $request->get('orderDirection', $orderDirection)
        );
        $this->attachSearch(
            $request->get('search', ''),
            $request->get('search', $searchIn)
        );
        $this->attachPagination(
            $request->get('page', 1),
            $request->get('per_page', $request->cookie('per_page', 10))
        );

        return $this->get($columns);
    }

    public function attachOrdering($orderBy, $orderDirection)
    {
        $this->orderBy = $orderBy;
        $this->orderDirection = $orderDirection;
    }

    public function attachSearch($search, $searchIn)
    {
        $this->bindings = [];
        $this->search = $search;
        $this->searchIn = is_array($searchIn) ?
            $searchIn : [$searchIn];
    }

    public function attachPagination($page, $perPage)
    {
        $this->currentPage = $page;
        $this->perPage = $perPage;
    }

    public function prepareSearch()
    {
        if (count($this->searchIn) === 0) {
            return '';
        }

        $words = explode(" ", $this->search);

        if (strlen($words[0]) === 0) {
            return '';
        }
        if (count($words) > 5) {
            $words = array_slice($a, 0, 5);
        }

        $prepared = implode(" OR ", array_map(function($column) use ($words) {
            return implode(" AND ", array_map(function($word, $key) use ($column) {
                $this->bindings["{$column}_{$key}"] = $word;
                return "{$column} LIKE '%:{$column}_{$key}%'";
            }, $words, array_keys($words)));
        }, $this->searchIn));

        return '('.$prepared.')';
    }

    public function preparePagination()
    {
        $this->searchQuery = $this->prepareSearch();
        $register = $this->model->select(
            columns: ["count(1) as total"],
            where: $this->searchQuery,
            first: true
        );

        $this->total = $register->get('total');
        $this->numPages = intval(ceil($this->total / $this->perPage));
    }

    public function get(array $columns)
    {
        $this->preparePagination();
        $this->data = $this->model->select(
            columns: $columns,
            where: $this->searchQuery,
            limit: $this->perPage,
            offset: $this->perPage * $this->currentPage - $this->perPage,
            orderBy: $this->orderBy,
            orderDirection: $this->orderDirection,
            bindings: $this->bindings ?: [],
        )->getAttributes();

        return $this;
    }
    
    public function values()
    {
        return $this->data;
    }
    
    public function toArray()
    {
        return [
            'data' => $this->values(),
            'perPage' => $this->perPage,
            'currentPage' => $this->currentPage,
            'total' => $this->total,
            'numPages' => $this->numPages,
        ];
    }
    
    public function toJson()
    {
        return json_encode($this->toArray());
    }
}