<?php

namespace DBSeller\SafeCopy\Task;

use \DBSeller\TaskRunner\Task\Base;

class Validate extends Base
{
    private $context;

    public function __construct($context)
    {
        $this->context = $context;
    }

    protected function doRun()
    {
        $this->permissions();
    }

    public function permissions()
    {
        $dest = $this->context->get('dest');
        $this->permissionDirectory($dest);

        foreach ($this->context->get('files') as $file) {

            $destPath = $dest . $file;

            if (!$this->permissionDirectory($destPath)) {
                throw new \Exception(sprintf("Sem permissão de escrita no diretório: %s", dirname($destPath)));
            }

            if (!$this->permissionPath($destPath)) {
                throw new \Exception("Sem permissão de escrita para o arquivo: $destPath");
            }
        }
    }

    private function permissionDirectory($path)
    {
        if (!is_dir($path)) {
            $path = dirname($path);
        }

        return $this->permissionPath($path);
    }

    private function permissionPath($path)
    {
        return !file_exists($path) || is_writeable($path);
    }
}
