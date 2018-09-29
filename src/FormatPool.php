<?php

declare(strict_types = 1);

namespace byrokrat\autogiro2xml;

class FormatPool
{
    /**
     * @var FormatInterface[]
     */
    private $formats;

    public function __construct()
    {
        $this->formats = [
            'xml' => new XmlFormat,
        ];
    }

    public function getListOfFormats(): string
    {
        return implode(', ', array_keys($this->formats));
    }

    public function getFormat(string $formatId): FormatInterface
    {
        if (!isset($this->formats[$formatId])) {
            throw new \RuntimeException("Unknown format $formatId");
        }

        return $this->formats[$formatId];
    }
}
