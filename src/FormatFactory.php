<?php

declare(strict_types = 1);

namespace byrokrat\autogiro2xml;

class FormatFactory
{
    /**
     * @var FormatInterface[]
     */
    private $formats;

    public function __construct()
    {
        $this->formats = [
            'xml' => new XmlFormat,
            'validate' => new ValidateFormat,
        ];
    }

    public function getSupportedFormats(): string
    {
        return implode(', ', array_keys($this->formats));
    }

    public function createFormat(string $formatId): FormatInterface
    {
        if (!isset($this->formats[$formatId])) {
            throw new \RuntimeException("Unknown format $formatId");
        }

        return $this->formats[$formatId];
    }
}
