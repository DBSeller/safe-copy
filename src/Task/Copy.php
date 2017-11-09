<?php

namespace DBSeller\SafeCopy\Task;

use Symfony\Component\Filesystem\Filesystem;
use Psr\Container\ContainerInterface;
use \DBSeller\TaskRunner\ExecutionContext;

class Copy extends Base
{
    protected function doRun(ExecutionContext $context)
    {
        $fs = $this->container->get('filesystem');

        $shared = $context->shared();
        $source = $shared->get('source');
        $dest = $shared->get('dest');

        $logger = $this->container->get('logger');
        $logger->info('executing copy task');
        $logger->debug(sprintf(' - source path %s', $dest));

        $fs->mirror($source, $dest, null, array('override' => true));
    }
}
