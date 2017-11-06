<?php 

namespace DBSeller\TaskRunner;

class Collection implements CollectionInterface, \Countable
{
    /**
     * @var array
     */
    protected $values = array();
    protected $iterator;
    protected $sort;
    protected $filter;

    public function __construct(array $collection = array())
    {
        $this->values = $collection;
    }

    /**
     * {@inheritdoc}
     */
    public function & get($name, $default = null)
    {
        if ($this->has($name)) {
            return $this->values[$name];
        } 

        return $default;
    }

    /**
     * {@inheritdoc}
     */
    public function has($name)
    {
        return array_key_exists($name, $this->values);
    }

    /**
     * {@inheritdoc}
     */
    public function set($name, $object)
    {
        $this->values[$name] = $object;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        if (empty($offset)) {
            $this->values[] = $value;
        } else {
            $this->set($offset, $value);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        unset($this->values[$offset]);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        // not guaranteed to retain the current position of the iterator.
        return iterator_count($this->getIterator());
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return iterator_to_array($this);
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        $array = $this->values;

        // @TODO use \FilterIterator
        if ($this->filter) {
            $array = array_filter($array, $this->filter);
        }

        if ($this->sort) {
            // maintain indices
            uasort($array, $this->sort);
        }

        return new \ArrayIterator($array);
    }

    public function sort(\Closure $callback)
    {
        $this->sort = $callback;
    }

    public function filter(\Closure $callback)
    {
        $this->filter = $callback;
    }
}

