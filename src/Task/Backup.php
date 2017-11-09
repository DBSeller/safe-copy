<?php

namespace DBSeller\SafeCopy\Task;

use \DBSeller\TaskRunner\ExecutionContext;

class Backup extends Base
{
    protected function doRun(ExecutionContext $context)
    {
        $shared = $context->shared();

        $fs = $this->container->get('filesystem');
        $id = basename($shared->get('source'));
        $storage = $shared->get('storage') . $id . "/";

        $logger = $this->container->get('logger');
        $logger->info('executing backup task');
        $logger->debug(sprintf(' - backup path %s', $storage));

        $files = array();
        foreach ($shared->get('files') as $file) {

            $destFile = $shared->get('dest') . $file;

            if (!file_exists($destFile)) {
                continue;
            }

            $logger->debug(sprintf(' - copy file %s', $file));
            $fs->copy($destFile, $storage . $file, true);
            $files[] = $file;
        }

        $shared->set('backup', array(
            'files' => $files,
            'path' => $storage
        ));
    }
}
