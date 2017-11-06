<?php

namespace DBSeller\SafeCopy;

use \DBSeller\TaskRunner\Task\Collection as TaskCollection;
use \DBSeller\TaskRunner\Task\Callback as TaskCallback;
use \DBSeller\TaskRunner\Task\Group as TaskGroup;
use \DBSeller\TaskRunner\Runner;

use \DBSeller\SafeCopy\Task\Loader as LoaderTask;
use \DBSeller\SafeCopy\Task\Validate as ValidateTask;
use \DBSeller\SafeCopy\Task\Backup as BackupTask;
use \DBSeller\SafeCopy\Task\Copy as CopyTask;
use \DBSeller\SafeCopy\Task\Restore as RestoreTask;

class SafeCopy
{
    private $source;
    private $dest;
    private $store;

    private $task;
    private $fail;

    public function __construct($source, $dest, $store = '/tmp/safe-copy/')
    {
        $this->source = $source;
        $this->dest = $dest;
        $this->store = $store;
        $this->init();
    }

    public function init()
    {
        $this->validate();
        $this->sanitize();

        $context = new Context();
        $context->set('source', $this->source);
        $context->set('dest', $this->dest);
        $context->set('store', $this->store);

        $loader = new LoaderTask($context);
        $validate = new ValidateTask($context);
        $backup = new BackupTask($context);
        $copy = new CopyTask($context);

        $this->fail = new RestoreTask($context);
        $copy->fail($this->fail);

        $this->task = new TaskGroup(array(
            $loader, $validate, $backup, $copy
        ));
    }

    public function fail(\Closure $callback)
    {
        $task = new TaskCallback($callback);
        $this->fail->after($task);
    }

    public function before(\Closure $callback)
    {
        $task = new TaskCallback($callback);
        $this->task->before($task);
    }

    public function after(\Closure $callback)
    {
        $task = new TaskCallback($callback);
        $task->fail($this->fail);
        $this->task->after($task);
    }

    public function execute()
    {
        $runner = new Runner();
        $runner->run($this->task);
        //$runner->run($this->cleanup);
        return true;
    }

    private function sanitize()
    {
        $this->source = realpath($this->source) . "/";
        $this->dest = realpath($this->dest) . "/";
    }

    private function validate()
    {
        if (!$this->validatePath($this->source)) {
            throw new \Exception("Invalid source path.");
        }

        if (!$this->validatePath($this->dest)) {
            throw new \Exception("Invalid dest path.");
        }
    }

    private function validatePath($path)
    {
        return !empty($path) && is_dir($path);
    }
}