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
        ;
        
        foreach (self::FLAG_OPTIONS as $option => list(, $desc) {
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
        $parserFlags = 0;

        foreach (self::FLAG_OPTIONS as $option => list($flag) {
            if ($input->getOption($option)) {
                $parserFlags = $parserFlags | $flag;
            }
        }
        
        $parser = (new ParserFactory)->createParser($parserFlags);

        $format = $this->formatPool->getFormat($input->getOption('format'));

        $format->initialize($output);

        foreach (new PathIterator($input->getArgument('path')) as $filename => $content) {
            try {
                $format->formatNode($filename, $parser->parse($content));
            } catch (AutogiroException $exception) {
                $format->formatError($filename, $exception);
                if ($input->getOption('stop-on-failure')) {
                    break;
                }
            }
        }

        return $format->finalize();
    }
}
