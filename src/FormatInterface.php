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

namespace byrokrat\autogiro2xml;

use Symfony\Component\Console\Output\ConsoleOutputInterface;
use byrokrat\autogiro\Tree\Node;

interface FormatInterface
{
    /**
     * Initialize and set output
     */
    public function initialize(ConsoleOutputInterface $output): void;

    /**
     * Format a parsed node
     */
    public function formatNode(string $filename, Node $node): void;

    /**
     * Format a parser error
     */
    public function formatError(string $filename, \Exception $exception): void;

    /**
     * Finilize and return application exit status code
     */
    public function finalize(): void;
}
