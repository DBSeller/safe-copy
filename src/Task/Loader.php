<?php

namespace DBSeller\SafeCopy\Task;

use Symfony\Component\Finder\Finder;
use Psr\Container\ContainerInterface;

class Loader extends Base
{
    protected function doRun()
    {
        $this->loadFiles();
    }

    private function loadFiles()
    {
        $files = array();
        $logger = $this->container->get('logger');
        $context = $this->container->get('context');
        $logger->info('executing loader task');
        $logger->debug(sprintf(' - load files from source %s', $context->get('source')));

        $finder = new Finder();
        $finder->files()->in($context->get('source'));

        foreach ($finder as $file) {

            $logger->debug(sprintf(' - load file %s', $file->getRelativePathname()));
            $files[] = $file->getRelativePathname();
        }

        $context->set('files', $files);
    }
}
