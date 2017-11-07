<?php

namespace DBSeller\SafeCopy;

use Psr\Container\ContainerInterface;
use Pimple as BaseContainer;

/**
 * Container extends Pimple 1.x.x and emulate some features of 3.x.x
 */
class Container extends BaseContainer implements ContainerInterface
{
    private $factories;

    public function __construct(array $values = array()) 
    {
        parent::__construct($values);
        $this->factories = new \SplObjectStorage();

        foreach ($values as $key => $value) {
            $this->offsetSet($key, $value);
        }
    }

    public function offsetGet($id)
    {
        if (isset($this->factories[$this->values[$id]])) {
            return $this->values[$id]($this);
        }
        return parent::offsetGet($id);
    }

    public function offsetUnset($id)
    {
        if (isset($this->factories[$this->values[$id]])) {
            unset($this->factories[$this->values[$id]]);
        }
        parent::offsetUnset($id);
    }

    public function has($name)
    {
        return isset($this[$name]);
    } 

    public function set($name, $value)
    {
        if (!isset($this->factories[$value]) && is_callable($value)) {
            $this[$name] = $this->share($value);
            return true;
        } 
        $this[$name] = $value;
    }

    public function remove($name)
    {
        unset($this[$name]);
    }

    public function get($name, $default = null)
    {
        if ($this->has($name)) {
            return $this[$name];
        }

        return $default;
    }

    public function factory(\Closure $callback) 
    {
        $this->factories->attach($callback);
        return $callback;
    }
}
