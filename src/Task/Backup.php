<?php

namespace DBSeller\SafeCopy\Task;

use DBSeller\TaskRunner\Task\Base;
use Symfony\Component\Filesystem\Filesystem;

class Backup extends Base
{
    private $context;

    public function __construct($context)
    {
        $this->context = $context;
    }

    protected function doRun()
    {
        $fs = new Filesystem();
        $store = $this->context->get('store');
        $id = basename($this->context->get('source'));

        $files = array();
        foreach ($this->context->get('files') as $file) {

            $sourceFile = $this->context->get('source') . $file;
            $destFile = $this->context->get('dest') . $file;

            if (!file_exists($destFile)) {
                continue;
            }

            $fs->copy($sourceFile, $store . $id . "/" . $file);
            $files[] = $file;
        }

        $this->context->set('backup', array(
            'files' => $files,
            'path' => $store . $id . "/"
        ));
    }
}
