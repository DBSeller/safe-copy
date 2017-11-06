<?php 

namespace DBSeller\SafeCopy\Console;

use Symfony\Component\Console\Application as Console;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Application extends Console
{
    /**
     * @var \DBSeller\SafeCopy\Console\Command\Copy
     */
    private $singleCommand;

    /**
     * @param string $name    The name of the application
     * @param string $version The version of the application
     */
    public function __construct($name, $version)
    {
        parent::__construct($name, $version);
        $this->createSingleCommand();
    }

    /**
     * Define command copy to default command 
     */
    private function createSingleCommand()
    {
        $command = new Command\Copy($this->getName());
        $this->add($command);
        $this->singleCommand = $command;
    }

    /**
     * Gets the name of the command based on input.
     *
     * @param InputInterface $input The input interface
     * @return string The command name
     */
    protected function getCommandName(InputInterface $input)
    {
        return $this->singleCommand->getName();
    }

    /**
     * Overridden so that the application doesn't expect the command
     * name to be the first argument.
     */
    public function getDefinition()
    {
        $inputDefinition = parent::getDefinition();
        // clear out the normal first argument, which is the command name
        $inputDefinition->setArguments();
        return $inputDefinition;
    }
}

