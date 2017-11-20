<?php

namespace DBSeller\TaskRunner;

class Context
{
    private $data = array();

    public function __construct(array $data = array())
    {
        $this->data = $data;
    } 

    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function get($key, $default = null)
    {
        if ($this->has($key)) {
            return $this->data[$key];
        }
        return $default;
    }

    public function has($key)
    {
        return isset($this->data[$key]);
    }
}
