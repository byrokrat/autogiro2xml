<?php

declare(strict_types = 1);

namespace byrokrat\autogiro2xml;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use byrokrat\autogiro\Parser\ParserFactory;

class Command extends \Symfony\Component\Console\Command\Command
{
    private const FLAG_OPTIONS = [
        'ignore-accounts' => [
            ParserFactory::VISITOR_IGNORE_ACCOUNTS,
            'Ignore accounts when parsing',
        ],
        'ignore-amounts' => [
            ParserFactory::VISITOR_IGNORE_AMOUNTS,
            'Ignore monetary amounts when parsing',
        ],
        'ignore-ids' => [
            ParserFactory::VISITOR_IGNORE_IDS,
            'Ignore state ids when parsing',
        ],
        'ignore-dates' => [
            ParserFactory::VISITOR_IGNORE_DATES,
            'Ignore dates when parsing',
        ],
        'ignore-messages' => [
            ParserFactory::VISITOR_IGNORE_MESSAGES,
            'Ignore messages when parsing',
        ],
        'ignore-basic-validation' => [
            ParserFactory::VISITOR_IGNORE_BASIC_VALIDATION,
            'Ignore basic validation when parsing',
        ],
        'ignore-strict-validation' => [
            ParserFactory::VISITOR_IGNORE_STRICT_VALIDATION,
            'Ignore strict validation when parsing',
        ],
        'ignore-objects' => [
            ParserFactory::VISITOR_IGNORE_OBJECTS,
            'Ignore objects when parsing',
        ],
        'ignore-all' => [
            ParserFactory::VISITOR_IGNORE_ALL,
            'Ignore all visitors when parsing',
        ],
    ];

    /**
     * @var FormatFactory
     */
    private $formatFactory;

    public function __construct(FormatFactory $formatFactory = null)
    {
        $this->formatFactory = $formatFactory ?: new FormatFactory;
        parent::__construct();
    }

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName('autogiro2xml')
            ->setDescription('Command line utility for converting autogiro files to XML.')
            ->addArgument(
                'path',
                InputArgument::OPTIONAL | InputArgument::IS_ARRAY,
                'One or more files or directories to convert'
            )
            ->addOption(
                'format',
                null,
                InputOption::VALUE_REQUIRED,
                "Set output format ({$this->formatFactory->getSupportedFormats()})",
                'xml'
            )
            ->addOption(
                'stop-on-failure',
                null,
                InputOption::VALUE_NONE,
                'Stop processing once an error occurs'
            )
        ;

        foreach (self::FLAG_OPTIONS as $option => list(, $desc)) {
            $this->addOption(
                $option,
                null,
                InputOption::VALUE_NONE,
                $desc
            );
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!$output instanceof ConsoleOutputInterface) {
            throw new \LogicException('Expecting a ConsoleOutputInterface');
        }

        $parserFlags = 0;

        foreach (self::FLAG_OPTIONS as $option => list($flag)) {
            if ($input->getOption($option)) {
                $parserFlags = $parserFlags | $flag;
            }
        }

        $parser = (new ParserFactory)->createParser($parserFlags);

        /** @var string */
        $formatId = $input->getOption('format');

        $format = $this->formatFactory->createFormat($formatId);

        $format->initialize($output);

        $returnCode = 0;

        /** @var array<string> $paths */
        $paths = $input->getArgument('path') ?: ['php://stdin'];

        foreach (new PathIterator($paths) as $filename => $content) {
            try {
                $format->formatNode($filename, $parser->parse($content));
            } catch (\Exception $exception) {
                $format->formatError($filename, $exception);
                $returnCode = 1;
                if ($input->getOption('stop-on-failure')) {
                    break;
                }
            }
        }

        $format->finalize();

        return $returnCode;
    }
}
