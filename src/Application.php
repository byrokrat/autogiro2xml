<?php

declare(strict_types = 1);

namespace byrokrat\autogiro2xml;

class Application extends \Symfony\Component\Console\Application
{
    /**
     * Gets the name of the command based on input.
     *
     * @return string
     */
    protected function getCommandName(\Symfony\Component\Console\Input\InputInterface $input)
    {
        // This should return the name of your command.
        return 'autogiro2xml';
    }

    /**
     * Gets the default commands that should always be available.
     *
     * @return array<\Symfony\Component\Console\Command\Command>
     */
    protected function getDefaultCommands()
    {
        // Keep the core default commands to have the HelpCommand
        // which is used when using the --help option
        $defaultCommands = parent::getDefaultCommands();

        $defaultCommands[] = new Command();

        return $defaultCommands;
    }

    /**
     * Overridden so that the application doesn't expect the command
     * name to be the first argument.
     *
     * @return \Symfony\Component\Console\Input\InputDefinition
     */
    public function getDefinition()
    {
        $inputDefinition = parent::getDefinition();
        // clear out the normal first argument, which is the command name
        $inputDefinition->setArguments();

        return $inputDefinition;
    }
}
