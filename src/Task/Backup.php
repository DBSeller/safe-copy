<?php

namespace DBSeller\SafeCopy\Task;

class Backup extends Base
{
    protected function doRun()
    {
        $fs = $this->container->get('filesystem');
        $context = $this->container->get('context');
        $id = basename($context->get('source'));
        $storage = $context->get('storage') . $id . "/";

        $logger = $this->container->get('logger');
        $logger->info('executing backup task');
        $logger->debug(sprintf(' - backup path %s', $storage));

        $files = array();
        foreach ($context->get('files') as $file) {

            $sourceFile = $context->get('source') . $file;
            $destFile = $context->get('dest') . $file;

            if (!file_exists($destFile)) {
                continue;
            }

            $logger->debug(sprintf(' - copy file %s', $file));
            $fs->copy($sourceFile, $storage . $file, true);
            $files[] = $file;
        }

        $context->set('backup', array(
            'files' => $files,
            'path' => $storage
        ));
    }
}
