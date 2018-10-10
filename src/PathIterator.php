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
     * @param string[] $paths
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
        if (is_dir($path) && is_readable($path)) {
            foreach (new \DirectoryIterator($path) as $fileInfo) {
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
