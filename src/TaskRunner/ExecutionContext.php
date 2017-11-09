<?php

namespace DBSeller\TaskRunner;

class ExecutionContext extends Context
{
    private $shared;

    public function shared(SharedContext $shared = null)
    {
        if ($shared === null) {
            return $this->shared;
        }

        $this->shared = $shared;
        return $this;
    }
}

