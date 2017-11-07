<?php

namespace DBSeller\SafeCopy\Task;

use DBSeller\TaskRunner\Task\Base as Task;
use Psr\Container\ContainerInterface;

abstract class Base extends Task
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
}
