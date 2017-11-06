<?php

namespace DBSeller\TaskRunner\Task;

use DBSeller\TaskRunner\Collection as CollectionBase;

class Collection extends CollectionBase
{
    public function get($name, $default = null)
    {
        if (!$this->has($name) && func_num_args() == 1) {
            throw new \Exception(sprintf("Task not found %s", $name));
        }

        return parent::get($name, $default);
    }
}
