<?php

declare(strict_types = 1);

namespace byrokrat\autogiro2xml;

use Symfony\Component\Console\Output\ConsoleOutputInterface;
use byrokrat\autogiro\Tree\Node;

class ValidateFormat implements FormatInterface
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
