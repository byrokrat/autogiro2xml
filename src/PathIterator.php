<?php

declare(strict_types = 1);

namespace byrokrat\autogiro2xml;

class PathIterator implements \IteratorAggregate
{
    /**
     * @var string[]
     */
    private $paths;

    /**
     * @param string[]
     */
    public function __construct(array $paths)
    {
        $this->paths = $paths;
    }

    public function getIterator(): iterable
    {
        foreach ($this->paths as $path) {
            yield from $this->iterate($path);
        }
    }

    private function iterate(string $path): iterable
    {
        if (is_file($path) && is_readable($path)) {
            yield $path => file_get_contents($path);
        }

        if (is_dir($path) && is_readable($path)) {
            // TODO yield from directory with recursion...
        }
    }
}
