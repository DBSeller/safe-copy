<?php

namespace DBSeller\SafeCopy\Task;

use \DBSeller\TaskRunner\ExecutionContext;

class Validate extends Base
{
    protected function doRun(ExecutionContext $context)
    {
        $logger = $this->container->get('logger');
        $shared = $context->shared();
        $dest = $shared->get('dest');

        $logger->info('executing validate task');

        $this->permissionDirectory($dest);

        foreach ($shared->get('files') as $file) {

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
