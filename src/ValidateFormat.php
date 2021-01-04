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
 * Copyright 2018-21 Hannes Forsgård
 */

declare(strict_types=1);

namespace byrokrat\autogiro2xml;

use Symfony\Component\Console\Output\ConsoleOutputInterface;
use byrokrat\autogiro\Tree\Node;

final class ValidateFormat implements FormatInterface
{
    /**
     * @var ConsoleOutputInterface
     */
    private $output;

    /**
     * @var int
     */
    private $passCount = 0;

    /**
     * @var int
     */
    private $failCount = 0;

    public function initialize(ConsoleOutputInterface $output): void
    {
        $this->output = $output;
        $this->passCount = 0;
        $this->failCount = 0;
    }

    public function formatNode(string $filename, Node $node): void
    {
        $this->output->writeln("PASS: $filename");
        $this->passCount++;
    }

    public function formatError(string $filename, \Exception $exception): void
    {
        $this->output->getErrorOutput()->writeln(
            sprintf(
                "FAIL: %s \n\n%s\n",
                $filename,
                $exception->getMessage()
            )
        );
        $this->failCount++;
    }

    public function finalize(): void
    {
        $this->output->writeln(
            sprintf(
                "DONE! %s files passed. %s failed.",
                $this->passCount,
                $this->failCount
            )
        );
    }
}
