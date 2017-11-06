<?php

namespace DBSeller\SafeCopy\Task;

use \DBSeller\TaskRunner\Task\Base;
use \Symfony\Component\Filesystem\Filesystem;

class Restore extends Base
{
    private $context;

    private $fs;

    public function __construct($context)
    {
        $this->context = $context;
    }

    protected function doRun()
    {
        echo "Restore" . PHP_EOL;

        $fs = new Filesystem();

        $files = $this->context->get('files');
        $backup = $this->context->get('backup');

        $dest = $this->context->get('dest');

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
            $fs->remove($dest . $file);
        }

        // remover diretorio vazio
        $this->recursiveRemovePaths(array_unique($removePaths));

    }

    private function recursiveRemovePaths($paths)
    {
        foreach ($paths as $path) {

            if (!$this->isEmptyDir($path)) {
                continue;
            }

            echo "$path" , PHP_EOL;
            rmdir($path);
            $this->recursiveRemovePaths(array(dirname($path)));
        }
    }

    private function isEmptyDir($path)
    {
        $dir = dir($path);
        $result = true;

        while (false !== ($entry = $dir->read()) ) {

            if ($entry != "." && $entry != "..") {
                $result = false;
                break;
            }
        }

        $dir->close();

        return $result;
    }
}
