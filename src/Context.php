<?php

namespace DBSeller\SafeCopy;

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

    public function get($key)
    {
        return $this->data[$key];
    }

    public function has($key)
    {
        return isset($this->data[$key]);
    }
}