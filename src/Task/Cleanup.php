<?php

namespace DBSeller\SafeCopy\Task;

use DBSeller\TaskRunner\Task\Base;
use Symfony\Component\Filesystem\Filesystem;

class Cleanup extends Base
{
    private $context;

    public function __construct($context)
    {
        $this->context = $context;
    }

    protected function doRun()
    {
        $fs = new Filesystem();
        $backup = $this->context->get('backup');
        $fs->remove($backup['path']);
    }
}
