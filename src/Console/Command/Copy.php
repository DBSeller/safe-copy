<?php

namespace DBSeller\SafeCopy\Console\Command;

use DBSeller\SafeCopy\SafeCopy;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface as Input;
use Symfony\Component\Console\Output\OutputInterface as Output;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Bridge\Monolog\Handler\ConsoleHandler;

class Copy extends Command
{
    public function configure()
    {
        $this
            ->setDescription('Safe copy')
            ->addArgument('source', InputArgument::REQUIRED, 'The source path directory')
            ->addArgument('dest', InputArgument::REQUIRED, 'The dest path directory')
            ->addArgument('storage', InputArgument::OPTIONAL, 'The storage directory', '/tmp/safe-copy/');
    }

    protected function execute(Input $input, Output $output)
    {
        $source = $input->getArgument('source');
        $dest = $input->getArgument('dest');
        $storage = $input->getArgument('storage');
        $safeCopy = new SafeCopy($source, $dest, $storage);

        $logger = $safeCopy->container()->get('logger');
        $logger->pushHandler(new ConsoleHandler($output));

        $safeCopy->execute();
    }
}
