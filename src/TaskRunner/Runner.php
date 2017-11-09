<?php

namespace DBSeller\TaskRunner;

use \DBSeller\TaskRunner\Task\Group;
use \DBSeller\TaskRunner\ExecutionContext;
use \DBSeller\TaskRunner\SharedContext;

class Runner
{
    /**
     * @param array $tasks
     * @return array
     */
    public function run($tasks, SharedContext $shared = null)
    {
        $tasks = $this->prepare($tasks);
        return $this->doRun($tasks, $shared ?: new SharedContext());
    }

    /**
     * @param array $tasks
     * @return array
     */
    private function doRun(array $tasks, SharedContext $shared)
    {
        $result = array();

        foreach ($tasks as $task) {

            $context = new ExecutionContext();
            $context->shared($shared);

            try {
                $result[] = $task->run($context);
            } catch (\Exception $error) {

                $this->doRun($this->prepare($task->fail()), $shared);
                throw $error;
            }
        }

        return $result;
    }

    /**
     * transform array of tasks/groups in 1D array
     * @param Array
     * @return Array
     */
    private function prepare($tasks)
    {
        if (!is_array($tasks)) {
            $tasks = array($tasks);
        }

        static $handler = null;

        if ($handler === null) {
            $handler = function($data, $task) use (& $handler) {

                $data = array_merge($data, $task->before());

                if ($task instanceof Group) {
                    $data = array_reduce($task->tasks(), $handler, $data);
                } else {
                    $data[] = $task;
                }

                $data = array_merge($data, $task->after());

                return $data;
            };
        }

        return array_reduce($tasks, $handler, array());
    }
}
