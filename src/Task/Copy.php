<?php

namespace DBSeller\SafeCopy\Task;

use DBSeller\TaskRunner\Task\Base;
use \Symfony\Component\Filesystem\Filesystem;

class Copy extends Base
{
    private $context;

    public function __construct($context)
    {
        $this->context = $context;
    }

    protected function doRun()
    {
        echo "Copy" . PHP_EOL;

        $fs = new Filesystem();

        $source = $this->context->get('source');
        $dest = $this->context->get('dest');
        $fs->mirror($source, $dest);
    }
}