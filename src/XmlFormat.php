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
use byrokrat\autogiro\Xml\XmlWriterFactory;
use byrokrat\autogiro\Xml\XmlWriter;

final class XmlFormat implements FormatInterface
{
    /**
     * @var XmlWriter
     */
    private $xmlWriter;

    /**
     * @var ConsoleOutputInterface
     */
    private $output;

    public function __construct(XmlWriter $xmlWriter = null)
    {
        $this->xmlWriter = $xmlWriter ?: (new XmlWriterFactory())->createXmlWriter();
    }

    public function initialize(ConsoleOutputInterface $output): void
    {
        $this->output = $output;
    }

    public function formatNode(string $filename, Node $node): void
    {
        $this->output->write(
            $this->xmlWriter->asXml($node)
        );
    }

    public function formatError(string $filename, \Exception $exception): void
    {
        $this->output->getErrorOutput()->writeln(
            $exception->getMessage()
        );

        if ($this->output->isVerbose()) {
            $this->output->getErrorOutput()->writeln(
                $exception->getTraceAsString()
            );
        }
    }

    public function finalize(): void
    {
    }
}
