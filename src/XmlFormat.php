<?php

declare(strict_types = 1);

namespace byrokrat\autogiro2xml;

use Symfony\Component\Console\Output\ConsoleOutputInterface;
use byrokrat\autogiro\Exception as AutogiroException;
use byrokrat\autogiro\Tree\Node;
use byrokrat\autogiro\Xml\XmlWriterFactory;
use byrokrat\autogiro\Xml\XmlWriterInterface;

class XmlFormat implements FormatInterface
{
    /**
     * @var XmlWriterInterface
     */
    private $xmlWriter;

    /**
     * @var ConsoleOutputInterface
     */
    private $output;

    public function __construct(XmlWriterInterface $xmlWriter = null)
    {
        $this->xmlWriter = $xmlWriter ?: (new XmlWriterFactory)->createXmlWriter();
    }

    public function initialize(ConsoleOutputInterface $output): void
    {
        $this->output = $output;
    }

    public function formatNode(string $filename, Node $node): void
    {
        $this->output->writeln(
            $this->xmlWriter->asXml($node)
        );
    }

    public function formatError(string $filename, AutogiroException $exception): void
    {
        $this->output->getErrorOutput()->writeln(
            $exception->getMessage()
        );
    }

    public function finalize(): void
    {
    }
}
