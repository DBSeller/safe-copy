<?php

namespace DBSeller\TaskRunner\Task;

use \DBSeller\TaskRunner\ExecutionContext;

class Callback extends Base
{
    private $callback;

    public function __construct(\Closure $callback)
    {
        $this->callback = $callback;
    }

    protected function doRun(ExecutionContext $context)
    {
        return call_user_func_array($this->callback, array($context));
    }
}
