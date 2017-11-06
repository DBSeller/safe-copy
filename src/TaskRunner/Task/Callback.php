<?php

namespace DBSeller\TaskRunner\Task;

class Callback extends Base
{
    private $callback;

    public function __construct(\Closure $callback)
    {
        $this->callback = $callback;
    }

    protected function doRun()
    {
        return call_user_func($this->callback);
    }
}
