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

declare(strict_types=1);

namespace byrokrat\autogiro2xml;

use DirectoryIterator;

/**
 * @implements \IteratorAggregate<string, string>
 */
final class PathIterator implements \IteratorAggregate
{
    /**
     * @var array<string>
     */
    private $paths;

    /**
     * @param array<string> $paths
     */
    public function __construct(array $paths)
    {
        $this->paths = $paths;
    }

    /**
     * @return iterable<string, string>
     */
    public function getIterator(): iterable
    {
        foreach ($this->paths as $path) {
            yield from $this->iterate($path);
        }
    }

    /**
     * @return iterable<string, string>
     */
    private function iterate(string $path): iterable
    {
        if (is_dir($path) && is_readable($path)) {
            foreach (new DirectoryIterator($path) as $fileInfo) {
                if ($fileInfo->isDot()) {
                    continue;
                }
                yield from $this->iterate($fileInfo->getPathname());
            }
            return;
        }

        $content = @file_get_contents($path);

        if (false !== $content) {
            yield $path => $content;
        }
    }
}
