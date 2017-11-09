<?php

namespace DBSeller\SafeCopy;

use DBSeller\TaskRunner\Task\Callback as TaskCallback;
use DBSeller\TaskRunner\Task\Group as TaskGroup;
use DBSeller\TaskRunner\Runner;
use DBSeller\TaskRunner\SharedContext;
use DBSeller\SafeCopy\Task\Loader as LoaderTask;
use DBSeller\SafeCopy\Task\Validate as ValidateTask;
use DBSeller\SafeCopy\Task\Backup as BackupTask;
use DBSeller\SafeCopy\Task\Copy as CopyTask;
use DBSeller\SafeCopy\Task\Restore as RestoreTask;
use DBSeller\SafeCopy\Task\Cleanup as CleanupTask;
use Symfony\Component\Filesystem\Filesystem;

class SafeCopy
{
    private $source;
    private $dest;
    private $storage;

    private $container;
    
    private $task;
    private $cleanup;
    private $fail;

    public function __construct($source, $dest, $storage = '/tmp/safe-copy/')
    {
        $this->source = $source;
        $this->dest = $dest;
        $this->storage = $storage;
        $this->init();
    }

    public function container()
    {
        return $this->container;
    }

    public function init()
    {
        $this->validate();
        $this->sanitize();

        $container = new Container();

        $container->set('filesystem', function() {
            return new Filesystem(); 
        });

        $container->set('logger', function() {
            return new \Monolog\Logger('SafeCopy');
        });

        $loader = new LoaderTask($container);
        $validate = new ValidateTask($container);
        $backup = new BackupTask($container);
        $copy = new CopyTask($container);

        $this->fail = new RestoreTask($container);
        $copy->fail($this->fail);

        $this->cleanup = new CleanupTask($container);

        $this->task = new TaskGroup(array(
            $loader, $validate, $backup, $copy
        ));

        $this->container = $container;
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
        $shared = new SharedContext();
        $shared->set('source', $this->source);
        $shared->set('dest', $this->dest);
        $shared->set('storage', $this->storage);

        $runner = new Runner();
        $runner->run($this->task, $shared);
        $runner->run($this->cleanup, $shared);
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
