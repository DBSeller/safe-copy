<?php

namespace DBSeller\SafeCopy\Task;

use Symfony\Component\Filesystem\Filesystem;
use Psr\Container\ContainerInterface;

class Restore extends Base
{
    protected function doRun()
    {
        $fs = $this->container->get('filesystem');
        $context = $this->container->get('context');
        $files = $context->get('files');
        $backup = $context->get('backup');
        $dest = $context->get('dest');

        $logger = $this->container->get('logger');
        $logger->info('executing restore task');
        $logger->debug(sprintf(' - restore from path %s', $backup['path']));

        // remove
        $remove = array_diff($files, $backup['files']);
        $removePaths = array();

        // update
        $update = array_intersect($files, $backup['files']);

        foreach ($update as $file) {
            $fs->copy($backup['path'] . $file, $dest . $file);
        }

        foreach ($remove as $file) {
            $removePaths[] = dirname($dest . $file);
            $logger->debug(sprintf(" - remove %s", $file));
            $fs->remove($dest . $file);
        }

        // remover diretorio vazio
        $this->recursiveRemovePaths(array_unique($removePaths));
    }

    private function recursiveRemovePaths($paths)
    {
        foreach ($paths as $path) {

            if (!is_dir($path) || !$this->isEmptyDir($path)) {
                continue;
            }

            rmdir($path);
            $this->recursiveRemovePaths(array(dirname($path)));
        }
    }

    private function isEmptyDir($path)
    {
        $dir = dir($path);
        $result = true;

        while (false !== ($entry = $dir->read())) {

            if ($entry != "." && $entry != "..") {
                $result = false;
                break;
            }
        }

        $dir->close();

        return $result;
    }
}
