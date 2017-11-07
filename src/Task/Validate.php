<?php

namespace DBSeller\SafeCopy\Task;

class Validate extends Base
{
    protected function doRun()
    {
        $this->permissions();
    }

    public function permissions()
    {
        $logger = $this->container->get('logger');
        $context = $this->container->get('context');
        $dest = $context->get('dest');

        $logger->info('executing validate task');

        $this->permissionDirectory($dest);

        foreach ($context->get('files') as $file) {

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
