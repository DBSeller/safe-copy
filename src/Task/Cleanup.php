<?php

namespace DBSeller\SafeCopy\Task;

use Symfony\Component\Filesystem\Filesystem;
use Psr\Container\ContainerInterface;
use \DBSeller\TaskRunner\ExecutionContext;

class Cleanup extends Base
{
    protected function doRun(ExecutionContext $context)
    {
        $fs = $this->container->get('filesystem');
        
        $backup = $context->shared()->get('backup');

        $logger = $this->container->get('logger');
        $logger->info('executing task cleanup');
        $logger->debug(sprintf(' - remove path %s', $backup['path']));

        $fs->remove($backup['path']);
    }
}
