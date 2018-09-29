<?php

declare(strict_types = 1);

namespace byrokrat\autogiro2xml;

use Symfony\Component\Console\Output\OutputInterface;
use byrokrat\autogiro\Exception as AutogiroException;
use byrokrat\autogiro\Tree\Node;

class XmlFormat implements FormatInterface
{
    public function initialize(OutputInterface $output): void
    {
    }

    public function formatNode(string $filename, Node $node): void
    {
    }

    public function formatError(string $filename, AutogiroException $exception): void
    {
    }

    public function finalize(): int
    {
        return 0;
    }
}
