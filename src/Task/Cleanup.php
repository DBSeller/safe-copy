<?php

namespace DBSeller\SafeCopy\Task;

use Symfony\Component\Filesystem\Filesystem;
use Psr\Container\ContainerInterface;

class Cleanup extends Base
{
    protected function doRun()
    {
        $fs = $this->container->get('filesystem');
        $backup = $this->container->get('context')->get('backup');

        $logger = $this->container->get('logger');
        $logger->info('executing cleanup task');
        $logger->debug(sprintf(' - remove path %s', $backup['path']));

        $fs->remove($backup['path']);
    }
}
