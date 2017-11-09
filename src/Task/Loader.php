<?php

namespace DBSeller\SafeCopy\Task;

use Symfony\Component\Finder\Finder;
use Psr\Container\ContainerInterface;
use \DBSeller\TaskRunner\ExecutionContext;


class Loader extends Base
{
    protected function doRun(ExecutionContext $context)
    {
        $files = array();
        $logger = $this->container->get('logger');
        $shared = $context->shared();
        $logger->info('executing loader task');
        $logger->debug(sprintf(' - load files from source %s', $shared->get('source')));

        $finder = new Finder();
        $finder->ignoreDotFiles(false);
        $finder->ignoreVCS(false);
        $finder->files()->in($shared->get('source'));

        foreach ($finder as $file) {

            $logger->debug(sprintf(' - load file %s', $file->getRelativePathname()));
            $files[] = $file->getRelativePathname();
        }

        $shared->set('files', $files);
    }
}
