<?php

namespace DBSeller\SafeCopy\Task;

use \DBSeller\TaskRunner\Task\Base;
use Symfony\Component\Finder\Finder;

class Loader extends Base
{
    private $context;

    public function __construct($context)
    {
        $this->context = $context;
    }

    protected function doRun()
    {
        $this->loadFiles();
    }

    private function loadFiles()
    {
        $files = array();
        $finder = new Finder();
        $finder->files()->in($this->context->get('source'));

        foreach ($finder as $file) {
            $files[] = $file->getRelativePathname();
        }

        $this->context->set('files', $files);
    }
}
