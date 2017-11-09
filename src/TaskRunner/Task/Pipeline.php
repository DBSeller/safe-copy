<?php

namespace DBSeller\TaskRunner\Task;

use \DBSeller\TaskRunner\ExecutionContext;

class Pipeline extends Base
{
    private $tasks;

    public function __construct(array $tasks = array())
    {
        $this->tasks = $tasks;
    }

    public function pipe(\Closure $pipe)
    {
        $this->tasks[] = $pipe;
        return $this;
    }

    protected function doRun(ExecutionContext $context)
    {
        $payload = null;
        $tasks = $this->tasks;

        while (($task = array_shift($tasks)) !== null) {
            $payload = call_user_func_array($task, array($payload, $context));
        }

        return $payload;
    }
}
