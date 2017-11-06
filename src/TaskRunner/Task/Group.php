<?php

namespace DBSeller\TaskRunner\Task;

class Group extends Base
{
    private $tasks = array();

    public function __construct(Array $tasks = array())
    {
        $this->tasks = $tasks;
    }

    public function tasks(Array $tasks = null)
    {
        if ($tasks === null) {
            return $this->tasks;
        }
        $this->tasks = $tasks;
        return $this;
    }

    public function doRun()
    {
        $result = array();
        foreach ($this->tasks as $task) {
            $result[] = $task->run();
        }
        return $result;
    }
}
