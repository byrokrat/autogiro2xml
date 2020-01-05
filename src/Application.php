<?php
/**
 * This file is part of autogiro2xml.
 *
 * autogiro2xml is free software: you can redistribute it and/or
 * modify it under the terms of the GNU General Public License as published
 * by the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * autogiro2xml is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with autogiro2xml. If not, see <http://www.gnu.org/licenses/>.
 *
 * Copyright 2018-20 Hannes Forsgård
 */

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
