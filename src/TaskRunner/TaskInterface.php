<?php

namespace DBSeller\TaskRunner;

interface TaskInterface 
{
    const STATE_IDLE = 0;
    const STATE_RUNNING = 1;
    const STATE_FAIL = 2;
    const STATE_SUCCESS = 3;

    public function name($name = null);
    public function state($state = null);
    public function fail(TaskInterface $task = null);
    public function before(TaskInterface $task = null);
    public function after(TaskInterface $task = null);
    public function run();
}
