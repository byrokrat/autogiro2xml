<?php

namespace byrokrat\autogiro2xml;

use Symfony\Component\Console\Output\ConsoleOutputInterface;
use byrokrat\autogiro\Exception as AutogiroException;
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
    public function formatError(string $filename, AutogiroException $exception): void;

    /**
     * Finilize and return application exit status code
     */
    public function finalize(): void;
}
