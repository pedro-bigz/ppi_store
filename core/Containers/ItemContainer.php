<?php namespace Core\Containers;

use Countable;
use Traversable;
use ArrayIterator;
use IteratorAggregate;
use Core\Exceptions\BadRequestException;

class ItemContainer implements IteratorAggregate, Countable
{
    protected $items;

    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    public static function create(array $items = [])
    {
        return new static($items);
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function getAll($key = null)
    {
        if ($key === null) {
            return $this->items;
        }

        if (! array_key_exists($key, $this->items)) {
            throw BadRequestException::create(
                'Unexpected value for parameter "%s": expecting "array", got "%s".', $key, get_debug_type($value)
            );
        }

        return $this->items;
    }

    public function has($key)
    {
        return array_keys_exists($key, $this->items);
    }

    public function keys()
    {
        return array_keys($this->items);
    }

    public function add(array $items = [])
    {
        $this->items = array_replace($this->items, $items);
    }

    public function push($items)
    {
        array_push($this->items, $items);
    }

    public function get($key, $default = null)
    {
        if (!array_key_exists($key, $this->items)) {
            return $default;
        }
        return $this->items[$key];
    }

    public function set(array $items = [])
    {
        $this->items = $items;
    }

    public function setItems($key, $value)
    {
        $this->items[$key] = $value;
    }

    public function exists(string $key)
    {
        return array_key_exists($key, $this->items);
    }

    public function remove(string $key)
    {
        unset($this->items[$key]);
    }

    public function getAlpha(string $key, string $default = '')
    {
        return preg_replace('/[^[:alpha:]]/', '', $this->get($key, $default));
    }

    public function getAlnum(string $key, string $default = '')
    {
        return preg_replace('/[^[:alnum:]]/', '', $this->get($key, $default));
    }

    public function filter(string $key, $default = null, int $filter = FILTER_DEFAULT, $options = [])
    {
        $value = $this->get($key, $default);

        if ($options && !is_array($options)) {
            $options = ['flags' => $options];
        }

        if (!isset($options['flags']) && is_array($value)) {
            $options['flags'] = \FILTER_REQUIRE_ARRAY;
        }

        return filter_var($value, $filter, $options);
    }
}
