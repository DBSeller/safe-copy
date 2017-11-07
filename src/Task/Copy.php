<?php

namespace DBSeller\SafeCopy\Task;

use Symfony\Component\Filesystem\Filesystem;
use Psr\Container\ContainerInterface;

class Copy extends Base
{
    protected function doRun()
    {
        $fs = $this->container->get('filesystem');
        $context = $this->container->get('context');
        $source = $context->get('source');
        $dest = $context->get('dest');

        $logger = $this->container->get('logger');
        $logger->info('executing copy task');
        $logger->debug(sprintf(' - source path %s', $dest));

        $fs->mirror($source, $dest);
    }
}
