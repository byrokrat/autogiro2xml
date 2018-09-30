<?php

declare(strict_types = 1);

namespace byrokrat\autogiro2xml;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use byrokrat\autogiro\Exception as AutogiroException;
use byrokrat\autogiro\Parser\ParserFactory;

class Command extends \Symfony\Component\Console\Command\Command
{
    /**
     * @var FormatPool
     */
    private $formatPool;

    public function __construct(FormatPool $formatPool = null)
    {
        $this->formatPool = $formatPool ?: new FormatPool;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('autogiro2xml')
            ->setDescription('Command line utility for converting autogiro files to XML.')
            ->addArgument(
                'path',
                InputArgument::REQUIRED | InputArgument::IS_ARRAY,
                'One or more files or directories to convert'
            )
            ->addOption(
                'format',
                null,
                InputOption::VALUE_REQUIRED,
                "Set format ({$this->formatPool->getListOfFormats()})",
                'xml'
            )
            ->addOption(
                'stop-on-failure',
                null,
                InputOption::VALUE_NONE,
                'Stop processing once an error occurs'
            )
            ->addOption(
                'ignore-accounts',
                null,
                InputOption::VALUE_NONE,
                'Ignore account numbers while parsing'
            )
            ->addOption(
                'ignore-ids',
                null,
                InputOption::VALUE_NONE,
                'Ignore state id numbers while parsing'
            )
            ->addOption(
                'ignore-amounts',
                null,
                InputOption::VALUE_NONE,
                'Ignore monetary amounts while parsing'
            )
            ->addOption(
                'ignore-dates',
                null,
                InputOption::VALUE_NONE,
                'Ignore dates while parsing'
            )
            ->addOption(
                'ignore-messages',
                null,
                InputOption::VALUE_NONE,
                'Ignore messages while parsing'
            )
            ->addOption(
                'ignore-basic-validation',
                null,
                InputOption::VALUE_NONE,
                'Ignore basic validation while parsing'
            )
            ->addOption(
                'ignore-strict-validation',
                null,
                InputOption::VALUE_NONE,
                'Ignore strict validation while parsing'
            )
            ->addOption(
                'ignore-objects',
                null,
                InputOption::VALUE_NONE,
                'Ignore all objects while parsing'
            )
            ->addOption(
                'ignore-all',
                null,
                InputOption::VALUE_NONE,
                'Ignore all visitors while parsing'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // TODO flags till factory ska läsas från options...
        $parserFlags = 0;

        $parser = (new ParserFactory)->createParser($parserFlags);

        $format = $this->formatPool->getFormat($input->getOption('format'));

        $format->initialize($output);

        foreach (new PathIterator($input->getArgument('path')) as $filename => $content) {
            try {
                $format->formatNode($filename, $parser->parse($content));
            } catch (AutogiroException $exception) {
                $format->formatError($filename, $exception);
            }
        }

        return $format->finalize();
    }
}
