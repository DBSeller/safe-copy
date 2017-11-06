<?php

namespace DBSeller\TaskRunner\Task;

use DBSeller\TaskRunner\TaskInterface;

abstract class Base implements TaskInterface
{
    protected $name;
    protected $fail = array();
    protected $before = array();
    protected $after = array();
    protected $state = 0;

    abstract protected function doRun();

    public function run()
    {
        $this->state = self::STATE_RUNNING;
        $result = null;
        try {
            $result = $this->doRun();
        } catch (\Exception $e) {
            $this->state = self::STATE_FAIL;
            throw $e;
        }
        $this->state = self::STATE_SUCCESS;
        return $result;
    }

    public function name($name = null)
    {
        if ($name === null) {
            return $this->name;
        }
        $this->name = $name;
        return $this;
    }

    public function state($state = null)
    {
        if ($state === null) {
            return $this->state;
        }
        $this->state = $state;
        return $this;
    }

    public function fail(TaskInterface $task = null)
    {
        if ($task !== null) {
            $this->fail[] = $task;
        }
        return $this->fail;
    }

    public function before(TaskInterface $task = null) 
    {
        if ($task !== null) {
            $this->before[] = $task;
        }
        return $this->before; 
    }

    public function after(TaskInterface $task = null) 
    {
        if ($task !== null) {
            $this->after[] = $task;
        }
        return $this->after; 
    }
}
